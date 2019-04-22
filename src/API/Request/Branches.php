<?php

namespace Gogs\API\Request {

    /** 
     * Holds a collection of Branches for a Repository.
     * 
     * Supported:
     *  * GET `/repos/username/repo/branches`
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1
     */
    final class Branches extends Collection {
        protected $repo;

        /** 
         * Initialize Brances for a given repo
         * 
         * @param string $api_url The API URL
         * @param string $api_token The API token
         * @param Repo $repo The repository
         */
        public function __construct(string $api_url, string $api_token, Repo $repo) {
            $this->repo = $repo;
            parent::__construct($api_url, $api_token);
        }

        /** 
         * @see Base
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "get":
            case "load":
                if ($this->repo == null)
                    throw new Exception\InvalidMethodRequestException("Missing repository for branches");

                $this->scope = sprintf("/repos/%s/%s/branches", $this->repo->owner->username, $this->repo->name);
                return true;
            }

            return false;
        }

        /**
         * Search for a branch.
         *
         * This method doesnt search by a uri, instead it will
         * load every branch from Gogs and do a match on this.
         *
         * Params can be an array of 
         * ```php
         * $branches->search(array(
         *  "name"  => "name",      // alt. "q". required
         *  "limit" => 10,          // not required, default: 10
         * ));
         * ```
         * By now, this method can be intensive, as it will load
         * every branch and then do a match on each entry.
         *
         * @see Base
         * @see Collection
         * @throws Exception\SearchParamException on missing parameters
         * @return Branches
         */
        public function search(array $params = array(), bool $strict = true) {

            if (!isset($params["name"]) && !isset($params["q"]))
                throw new Exception\SearchParamException("Missing <name|q> parameter");

            if (isset($params["name"])) {
                $params["q"] = $params["name"];
                unset($params["q"]);
            }

            if (!isset($params["limit"]))
                $params["limit"] = 10;

            $branches = new Branches($this->url, $this->token, $this->repo);

            if (!$this->loaded)
                $this->load();

            foreach($this->all() as $key => $branch) {
                if ($branch->search(array("name" => $params["q"]), $strict))
                    $branches->add($branch, $branch->name);

                if ($branches->len() == $params["limit"])
                    break;
            }

            return $branches;
        }

        /**
         * @see Collection
         */
        public function sort_by(int $flag = Collection::SORT_INDEX) {
            return $this->sort("ksort");
        }

        /**
         * @see Collection
         */
        protected function add_object(\stdClass $obj) {
            $branch = new Branch($this->url, $this->token, $this->repo, $obj->name);
            $branch->json_set_property($obj);
            return $this->add($branch, $branch->name);
        }

    }

}
