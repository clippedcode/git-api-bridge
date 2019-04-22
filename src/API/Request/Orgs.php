<?php 

namespace Gogs\API\Request {

    /** 
     * Orgs is a collection of organizations.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.3
     */
    final class Orgs extends Collection {
        protected $owner;

        /** 
         * Initialize an organization collection for user.
         * 
         * @see Base
         * @param string $api_url The API URL
         * @param string $api_token The API token
         * @param User $owner The user
         */
        public function __construct(string $api_url, string $api_token, User $owner) {
            $this->owner = $owner;
            parent::__construct($api_url, $api_token);
        }

        /**
         * @see Base
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "get":
            case "load":
                $this->scope = ($this->owner == null || $this->owner->authenticated() ? "/user" : "/users/" . $this->owner->username) . "/orgs";
                return true;
            default:
                return false;
            }
        }

        /**
         * Create a new organization
         *
         * If arguments are given, the User will be created,
         * otherise it will return an initialized object,
         * leaving the programmer to create the user.
         *
         * @see Base
         * @return Org
         */
        public function create(...$args) {

            $org = new Org($this->url, $this->token, $this->owner);

            if (count($args) > 0) {
                $org->create(...$args);
                $this->add($org, $org->username);
            }

            return $org;
        }

        /** 
         * Get an organization by indentifier.
         *
         * Method will first look through organizations
         * already loaded. If not found it will return a
         * new object.
         *
         * Method does not ensure the organization in loaded
         * from Gogs so the user should call `->load()` on 
         * returned element. 
         * 
         * @param string $s 
         * @return Org
         */
        public function get(string $s) {

            if (($org = $this->by_key($s)))
                return $org;

            return new Org($this->url, $this->token, $this->owner, $s);
        }

        /**
         * Search for an organization.
         *
         * Params can be an array of 
         * ```php
         * $orgs->search(array(
         *  "name"  => "name",      // alt. "q". required
         *  "limit" => 10,          // not required, default: 10
         * ));
         * ```
         * By now, this method can be intensive, as it will load
         * every organization and then do a match on each entry.
         *
         * @see Base
         * @see Collection
         * @throws Exception\SearchParamException on missing parameters
         * @return Orgs
         */
        public function search(array $params = array(), bool $strict = false) {

            if (!isset($params["name"]) && !isset($params["q"]))
                throw new Exception\SearchParamException("Missing param <name>|<q>");

            $q = isset($params["name"]) ? $params["name"] : $params["q"];
            $l = isset($params["limit"]) ? $params["limit"] : 10;

            $this->load();

            $orgs = new Orgs($this->url, $this->token, $this->owner);

            foreach ($this->all() as $key => $org) {
                if ($org->search(array("username" => $q), $strict))
                    $orgs->add($org, $org->username);
                if ($orgs->len() == $l)
                    break;
            }

            return $orgs;
        }

        /**
         * @see Collection
         */
        protected function add_object(\stdClass $obj) {
            $org = new Org($this->url, $this->token, $this->owner, $obj->username);
            $org->json_set_property($obj);
            return $this->add($org, $obj->username);
        }

        /**
         * @see Collection
         */
        public function sort_by(int $flag = Collection::SORT_INDEX) {
            return $this->sort("ksort");
        }
    }

}
