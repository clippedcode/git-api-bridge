<?php

namespace Gogs\API\Request {

    /** 
     * Collection of tokens for a given user.
     *
     * Supports:
     *  * GET `/users/{username}/tokens`
     * 
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.1
     */
    final class Tokens extends Collection {
        const VERSION = "0.1.1";

        private $owner;

        /** 
         * Initialize a token collection.
         * 
         * @see Base
         * @param string $api_url The API URL
         * @param string $password User's personal password
         * @param User $user Owner of tokens
         */
        public function __construct(string $api_url, string $password, User $user) {
            parent::__construct($api_url, $password);

            if (!$user->email)
                $user->load();
        
            $this->basic($user->email);
            $this->owner = $user;
        }

        /** 
         * @see Base
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "load":
                $this->scope = sprintf("/users/%s/tokens", $this->owner->username);
                return true;
            }

            return false;
        }

        /** 
         * Create a new token.
         *
         * Returns a new token object. If arguments is specified the "token"
         * will be created. 
         *
         * Arguments can be left empty to "create" the token, leaving
         * the programmer to call create on the token object with the arguments
         * itself, to create it.
         *
         * Creating the token through this function will store the function in
         * the collection. If not created, it wont be added, and collection
         * must be reloaded (`->load(true)`)  to add it.
         *
         * @see Token
         * @return Token
         */
        public function create(...$args) {

            $token = new Token($this->url, $this->token, $this->owner);

            if (count($args) != 0) {
                $token->create(...$args);
                $this->add($token, $token->name);
            }

            return $token;
        }

        /** 
         * @see Collection
         */
        protected function add_object(\stdClass $obj) {
            $token = new Token($this->url, $this->token, $this->owner);
            $token->json_set_property($obj);
            return $this->add($token, $token->name);
        }

        /** 
         * Return a token by name
         *
         * @return Token
         */
        public function get(string $s) {
            if ($token = $this->by_key($s))
                return $token;

            $token = (new Token($this->url, $this->token, $this->owner))->load();
            
            $this->add($token, $token->name);

            return $token;
        }

        /**
         * Search for a token.
         *
         * Params can be an array of 
         * ```php
         * $orgs->search(array(
         *  "name"  => "name",      // alt. "q". required
         *  "limit" => 10,          // not required, default: 10
         * ));
         * ```
         * By now, this method can be intensive, as it will load
         * every token and then do a match on each entry.
         *
         * @see Base
         * @see Collection
         * @throws Exception\SearchParamException on missing parameters
         * @return Token
         */
        public function search(array $params = array(), bool $strict = false) {

            if (!isset($params["name"]) && !isset($params["q"]))
                throw new Exception\SearchParamException("Missing parameter <name|q>");

            if (!isset($params["name"])) {
                $params["name"] = $params["q"];
                unset($params["q"]);
            }

            if (!isset($params["limit"]))
                $params["limit"];

            if (!$this->loaded)
                $this->load();

            $tokens = new Tokens($this->url, $this->token, $this->owner);

            foreach ($this->all() as $key => $token) {

                if ($token->search(array("name" => $params["name"]), $strict))
                    $tokens->add($token, $token->name);

                if ($tokens->len() == $params["limit"])
                    break;

            }

            return $tokens;

        }

        /**
         * @see Collection
         */
        public function sort_by(int $flag = Collection::SORT_INDEX) {
            return $this->sort("ksort");
        }
    }
}
