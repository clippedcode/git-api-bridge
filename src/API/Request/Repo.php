<?php 

namespace Gogs\API\Request {

    /** 
     * Stores data and methods related to a single repository.
     *
     * By now the following are supported:
     *
     *  * GET `/repos/username/reponame`
     *  * POST `/user/repos`
     *  * POST `/admin/user/username/repos`
     *  * POST `/org/orgname/repos`
     *  * DELETE `/repos/username/reponame`
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.4
     */
    final class Repo extends Base {
        
        public $repo_id;
        public $repo_owner;
        public $repo_name;
        public $repo_full_name;
        public $repo_description;
        public $repo_private;
        public $repo_fork;
        public $repo_parent;
        public $repo_empty;
        public $repo_mirror;
        public $repo_size;
        public $repo_html_url;
        public $repo_ssh_url;
        public $repo_clone_url;
        public $repo_website;
        public $repo_stars_count;
        public $repo_forks_count;
        public $repo_watchers_count;
        public $repo_open_issues_count;
        public $repo_default_branch;
        public $repo_created_at;
        public $repo_updated_at;
        public $repo_permissions;

        /**
         * Initialize a repo object.
         *
         * Note that the owner can also be an Org (organization),
         * or any other class that inherits a user.
         *
         * @see Base
         * @param string $api_url The API URL
         * @param string $api_token The API token
         * @param User $owner The owner of the repo
         * @param string $name The repo name
         */
        public function __construct(string $api_url, string $api_token, User $owner = null, string $name = null) {
            parent::__construct($api_url, $api_token);
            $this->owner = $owner;
            $this->name = $name;
        }

        /** 
         * @see Base
         * @throws Exception\RequestErrorException on missing repo or user data
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "create":
                if (empty($this->owner) || !$this->owner->authenticated() && empty($this->owner->username))
                    throw new Exception\RequestErrorException("Missing userdata of unauthorized user 'username'");

                if ($this->owner instanceof Org)
                    $this->scope = "/org/" . $this->owner->username . "/repos";
                elseif ($this->owner->authenticated())
                    $this->scope = "/user/repos";
                else
                    $this->scope = "/admin/users/" . $this->owner->username . "/repos";
                break;
            case "delete":
                if (empty($this->owner) || empty($this->owner->username))
                    throw new Exception\RequestErrorException("Missing userdata 'username'");

                $this->scope = "/repos/" . $this->owner->username . "/" . $this->name;
                break;
            case "get":
            case "load":
                if ((empty($this->owner) || empty($this->owner->username)) && empty($this->full_name))
                    throw new Exception\RequestErrorException("Missing userdata 'username' and/or 'full_name'");

                $this->scope = "/repos/" . ($this->owner ? $this->owner->username . "/" . $this->name : $this->full_name);
                break;
            case "migrate":
                $this->scope = "/repos/migrate";
                break;
            case "sync":
                if (empty($this->owner) || empty($this->owner->username))
                    throw new Exception\RequestErrorException("Missing userdata 'username'");

                $this->scope = sprintf("/repos/%s/%s/mirror-sync", $this->owner->username, $this->name);
                break;
            default:
                return false;
            }

            return true;
        }

        /** 
         * Return branches for repository.
         *
         * @return Branches
         */
        public function branches() {
            return new Branches($this->url, $this->token, $this);
        }

        /** 
         * Overrides Base method as this should set owner as well
         *
         * @see Base
         */
        protected function json_set_property(\stdClass $obj) {
            foreach($obj as $key => $val) {
                if ($this->property_exists($key)) {
                    switch ($key) {
                    case "owner":
                        if (!$this->owner) {
                            $user = new User($this->url, $this->token);
                            $user->json_set_property($val);
                            $this->{$key} = $user;
                        }
                        break;
                    default:
                        $this->{$key} = $val;
                    }
                }
            }
            $this->loaded = true;

            return true;
        }

        /** 
         * Create a new repo
         *
         * Valid paramters:
         *
         *  1. name, required
         *  2. description
         *  3. private (default: false)
         *  4. auto_init (default: false)
         *  5. gitignore
         *  6. license
         *  7. readme (default: "Default")
         *   
         *   This reflects the API v1 documentation, but is in an order
         *   where the required fields are first.
         *
         * @param ...$args The parameter values
         * @return Repo
         */
        public function create(...$args) {

            $params = array();

            $this->set_param($params, "name", $args, 0,  "string", null);
            $this->set_param($params, "description", $args, 1, "string", null);
            $this->set_param($params, "private", $args, 2, "bool", false);
            $this->set_param($params, "auto_init", $args, 3, "bool", false);
            $this->set_param($params, "gitignores", $args, 4, "string", null);
            $this->set_param($params, "license", $args, 5, "string", null);
            $this->set_param($params, "readme", $args, 6, "string", "Default");
            
            $this->filter_params($params);

            return parent::create($params);
        }

        /**
         * Migrate a repository from other Git hosting sources.
         *
         * Valid parameters:
         *
         *  1. clone_addr, required
         *  3. repo_name, required
         *  4. auth_username
         *  5. auth_password
         *  6. mirror (default: false)
         *  7. private (default: false)
         *  8. description
         *
         *  **UID** will be set to `owner`. Either a User or an Organization.
         *  **From API doc**: To migrate a repository for a organization, 
         *  the authenticated user must be a owner of the specified organization.
         *
         *  This reflects the API v1 documentation, but is in an order
         *  where the required fields as first.
         *
         *  @throws Exception\RequestErrorException when owner not set
         *  @param ...$args The parameter values
         *  @return Repo
         */

        public function migrate(...$args) {

            $params = array();

            if (empty($this->owner))
                throw new Exception\RequestErrorException("Missing required userdata 'uid' or owner must be set");

            $this->set_param($params, "clone_addr", $args, 0, "string", null, function(string $url) {
                // @todo: URL/PATH validation here?
            });
            $this->set_param($params, "repo_name", $args, 1, "string", null);
            $this->set_param($params, "auth_username", $args, 2, "string", null);
            $this->set_param($params, "auth_password", $args, 3, "string", null);
            $this->set_param($params, "mirror", $args, 4, "bool", false);
            $this->set_param($params, "private", $args, 5, "bool", false);
            $this->set_param($params, "description", $args, 6, "string", null);

            $this->set_param($params, "uid", array(), 0, "int", $this->owner->id);

            $this->filter_params($params);

            $this->set_scope("migrate");
            $resp = parent::method_post($params);

            $this->json_set_property($this->json_decode($resp));

            return $this;
        }

        /** 
         * Add repo to sync queue. 
         *
         * Requires the repository to be a mirror.
         * 
         * @return bool
         */
        public function sync() {
            if ($this->mirror) {
                $this->set_scope("sync");
                $this->method_post();
                return true;
            }
            return false;
        }
    }

}
?>
