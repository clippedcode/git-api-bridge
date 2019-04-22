<?php 

namespace Gogs\API\Request {

    /** 
     * Repos is a collection of repos.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.3
     */
    final class Repos extends Collection {

        const SORT_UPDATED = Collection::SORT_INDEX << 1;
        const SORT_CREATED = Collection::SORT_INDEX << 2;
        const SORT_OWNER = Collection::SORT_INDEX << 3;

        protected $owner;

        /**
         * Initialize a repos collection
         *
         * If owner is not set it will query the whole
         * repo archive on Gogs.
         *
         * @param string $api_url The api-url
         * @param string $api_token The api-token
         * @param User $owner The owner of the collection
         */
        public function __construct(string $api_url, string $api_token, User $owner = null) {
            parent::__construct($api_url, $api_token);
            $this->owner = $owner;
        }

        /** 
         * @see Base
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "get":
            case "load":
                if ($this->owner instanceof Org)
                    $this->scope = "/orgs/" . $this->owner->username . "/repos";
                else
                    $this->scope = ($this->owner == null || $this->owner->authenticated() ? "/user" : "/users/" . $this->owner->username ) . "/repos";
                break;
            case "search":
                $this->scope = "/repos/search";
                break;
            default:
                return false;
            }

            return true;
        }

        /** 
         * Get a single repository by name.
         *
         * If the `owner` is set, the name can be just the
         * actual name of the repo 
         * 
         * @param string $name 
         * @return 
         */
        public function get(string $name) {

            if (isset($this->owner) && strpos($name, "/") === false )
                $name = sprintf("%s/%s", $this->owner->username, $name);

            if ($repo = $this->by_key($name))
                return $repo;

            $owner = !empty($this->owner) ? $this->owner : (
                ($pos = strpos($name, "/")) !== false ? 
                new User($this->url, $this->token, substr($name, 0, $pos)) : null
            );

            $repo = (new Repo(
                $this->url, 
                $this->token, 
                $owner, 
                ($pos = strpos($name, "/")) ? substr($name, $pos + 1) : $name
            ))->load();

            $this->add($repo, $repo->full_name);

            return $repo;
        }

        /** 
         * @see Collection
         * @return Repo
         */
        public function create(...$args) {

            $repo = new Repo($this->url, $this->token, $this->owner);

            if (count($args) > 0) {
                $repo->create(...$args);
                $this->add($repo, $repo->full_name);
            }

            return $repo;
        }

        /**
         * Searches for a repo.
         *
         * If the owner is specified the search will be 
         * limited to the actual user.
         *
         * Params can be an array of 
         * ```php
         * $repos->search(array(
         *  "name"  => "name",      // alt. "q". required
         *  "limit" => 10,          // not required, default: 10
         * ));
         * ```
         *
         * If repositories is allready loaded it will do a match
         * on the existing collection. 
         *
         * @see Base
         * @see Collection
         * @throws Exception\SearchParamException on missing parameters
         * @return Repos
         */
        public function search(array $params = array(), bool $strict = false) {

            if (!isset($params["name"]) && !isset($params["q"]))
                throw new Exception\SearchParamException("Missing param <name>|<q>");

            if (isset($params["name"])) {
                $params["q"] = $params["name"];
                unset($params["name"]);
            }

            if (!isset($params["uid"]) || isset($this->owner)) {
                if (!isset($this->owner->id))
                    $this->owner->load();
                $params["uid"] = isset($this->owner) ? $this->owner->id : 0;
            }

            if (!isset($params["limit"]))
                $params["limit"] = 10;

            $repos = new Repos($this->url, $this->token, $this->owner);
            
            if ($this->loaded) {
                foreach($this->all() as $key => $repo) {
                    $search = $repo->search(array(
                            "name" => $params["q"], 
                            "description" => $params["q"]
                        ), 
                        $strict
                    );

                    if ($search)
                        $repos->add($repo, $key);

                    if ($repos->len() == $params["limit"])
                        break;
                }
            } else {
                $this->set_scope("search");
                $jenc = $this->method_get($params);
                $jdec = $this->json_decode($jenc);

                foreach($this->json_set_property($jdec) as $key)
                    $repos->add($this->by_key($key), $key);
            }
            
            return $repos;
        }

        /**
         * Sort repos by `method`.
         *
         * Valid methods:
         *
         *  * SORT_UPDATED: Sort on `updated_at` value
         *  * SORT_CREATED: Sort on `created_at` value
         *  * SORT_OWNER: Sort on `owner` (organization repos etc may appear)
         * 
         * @param int $flag Defines sorting algorithm to use
         * @param bool $asc Ascending order
         * @return Repos
         */
        public function sort_by(int $flag = Collection::SORT_INDEX, bool $asc = false) {

            $repos = new Repos($this->url, $this->token, $this->owner);

            switch ($flag) {
            case self::SORT_CREATED:
                $sort = $this->sort(function(Repo $a, Repo $b) {
                    $adate = new \DateTime($a->created_at);
                    $bdate = new \DateTime($b->created_at);
                    return ($adate == $bdate ? 0 : ($adate > $bdate ? 1 : -1));
                });
                break;
            case self::SORT_UPDATED:
                $sort = $this->sort(function(Repo $a, Repo $b) {
                    $adate = new \DateTime($a->updated_at);
                    $bdate = new \DateTime($b->updated_at);
                    return ($adate == $bdate ? 0 : ($adate > $bdate ? 1 : -1));
                });
                break;
            case self::SORT_OWNER:
                $sort = $this->sort(function(Repo $a, Repo $b) {
                    return strcmp($a->owner->username, $b->owner->username);
                });
                break;
            default:
                $sort = $this->sort("ksort");
            }

            if ($asc)
                $sort = $sort->reverse();

            $repos->add($sort->all());

            return $repos;
        }

        /**
         * @see Collection
         */
        protected function add_object(\stdClass $obj) {
            $repo = new Repo($this->url, $this->token);
            $repo->json_set_property($obj);
            return $this->add($repo, $repo->full_name);
        }

        /** 
         * Get private repositories
         *
         * @return Repos
         */
        public function privates() {
            $repos = new Repos($this->url, $this->token, $this->owner);

            $repos->add($this->filter(function(Repo $r) {
                return $r->private;
            })->all());

            return $repos;
        }

        /** 
         * Get public repositories
         *
         * @return Repos
         */
        public function publics() {
            $repos = new Repos($this->url, $this->token, $this->owner);

            $repos->add($this->filter(function(Repo $r) {
                return !$r->private;
            })->all());

            return $repos;
        }

        /** 
         * Get personal repositories
         *
         * @return Repos
         */
        public function personals() {
            $repos = new Repos($this->url, $this->token, $this->owner);

            if (empty($this->owner))
                return $repos;

            $repos->add($this->filter(function(Repo $r) {
                return $this->owner->username == $r->owner->username;
            })->all());

            return $repos;
        }

        /** 
         * Get repositories contributed to
         *
         * @return Repos
         */
        public function contributions() {
            $repos = new Repos($this->url, $this->token, $this->owner);

            if (empty($this->owner))
                return $repos;

            $repos->add($this->filter(function(Repo $r) {
                return $this->owner->username != $r->owner->username;
            })->all());

            return $repos;
        }
    }

}
