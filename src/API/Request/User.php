<?php  

namespace Gogs\API\Request {

    /** 
     * Stores user data and methods related to a single user.
     *
     * By now the following are supported:
     *
     *  * GET `/user`
     *  * GET `/users/username`
     *  * POST `/admin/users` (**Requires** admin rights. Curl will throw NotAuthorized exception if not).
     *  * DELETE `/admin/users` (**Requires** admin rights. Curl will throw NotAuthorized exception if not).
     *
     * A user can also list it's repos and organizations.
     * 
     * @see Repos
     * @see Orgs
     * 
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.4
     */
    class User extends Base {

        private $authenticated;

        public $user_id;
        public $user_login;
        public $user_full_name;
        public $user_email;
        public $user_avatar_url;
        public $user_username;

        /**
         * Initialize an user object.
         *
         * @param string $api_url The api-url
         * @param string $api_token The api-token
         * @param string $user The username. "Empty" or "me" will return authenticated user
         */
        public function __construct(string $api_url, string $api_token, string $user = "") {
            $this->authenticated = (empty($user) || $user == "me");
            parent::__construct($api_url, $api_token);
            if (!$this->authenticated())
                $this->username = $user;
        }

        /** 
         * @see Base
         * @throws Exception\InvalidMethodRequest when create on loaded
         * @throws Exception\RequestErrorException when userdata is missing 
         */
        protected function set_scope(string $method) {
            switch($method) {
            case "create":
                if ($this->loaded)
                    throw new Exception\InvalidMethodRequestException("Cannot create user of existing user");

                $this->scope = "/admin/users";
                break;
            case "delete":
                if (!$this->username)
                    throw new Exception\RequestErrorException("Missing userdata 'username'.");

                $this->scope = "/admin/users/" . $this->username;
                break;
            case "get":
            case "load":
                $this->scope = empty($this->username) ? "/user" : "/users/" . $this->username;
                break;
            default:
                return false;
            }

            return true;
        }

        /** 
         * Returns if the user is the authenticated user.
         *
         * @return bool
         */
        public function authenticated() {
            return $this->authenticated;
        }

        /** 
         * Returns every repo under user.
         *
         * @return Repos
         */
        public function repos() {
            return new Repos($this->url, $this->token, $this);
        }

        /** 
         * Return a single repo.
         *
         * Note: This will also load the repo.
         *
         * @param string $name Repo name
         * @return Repo
         */
        public function repo(string $name) {
            return (new Repo($this->url, $this->token, $this, $name))->load();
        }

        /** 
         * Return every organization under user.
         *
         * @return Orgs
         */
        public function organizations() {
            return new Orgs($this->url, $this->token, $this);
        }

        /**
         * @alias organizations
         */
        public function orgs() {
            return $this->organizations();
        }

        /** 
         * Return a single organization.
         *
         * Note: This will also load the repo.
         * 
         * @param string $name Organization name
         * @return Org
         */
        public function organization(string $name) {
            return (new Org($this->url, $this->token, $this, $name))->load();
        }

        /**
         * @alias organization
         */
        public function org(string $name) {
            return $this->organization($name);
        }

        /** 
         * Create a new user.
         *
         * Valid parameters
         * 
         *  1. username
         *  2. email
         *  3. source_id
         *  4. login_name
         *  5. password
         *  6. send_notify
         *
         * This reflects the API v1 documentation, but is in an order
         * where the required fields are first.
         *
         * @param ...$args The parameter values.
         * @return User
         */
        public function create(...$args) {

            $params = array();

            $this->set_param($params, "username", $args, 0, "string", null);
            $this->set_param($params, "email", $args, 1, "string", null);
            $this->set_param($params, "source_id", $args, 2, "int", null);
            $this->set_param($params, "login_name", $args, 3, "string", null);
            $this->set_param($params, "password", $args, 4, "string", null);
            $this->set_param($params, "send_notify", $args, 5, "bool", null);

            $this->filter_params($params);

            return parent::create($params);
        }

        /** 
         * Returns user tokens
         * 
         * @param string $password User personal password.
         * @return Tokens
         */
        public function tokens(string $password) {
            return new Tokens($this->url, $password, $this);
        }

    }

}
?>
