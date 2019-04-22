<?php

namespace Gogs\API\Request {

    /** 
     * A token related to a user
     *
     * Supports:
     *  * POST `/users/{username}/tokens`
     *
     * Note! Tokens doesnt have a "GET" method. @see Tokens
     * as this can load them.
     * 
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.2
     */
    final class Token extends Base {
        const VERSION = "0.1.1";
        protected $owner;

        public $token_name;
        public $token_sha1;

        /** 
         * Initializes a token
         * 
         * @see Base
         * @param string $api_url The API URL
         * @param string $password The users personal password
         * @param User $user 
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
            case "create":
                $this->scope = sprintf("/users/%s/tokens", $this->owner->username);
                return true;
            }

            return false;
        }

        /** 
         * Create a new token
         *
         * Valid parameters:
         *
         *  1. name
         *
         *  This reflects the API v1 documentation.
         *
         *  @param ...$args The parameter values
         *  @return Token
         */
        public function create(...$args) {

            $params = array();

            $this->set_param($params, "name", $args, 0, "string", null);
            $this->filter_params($params);

            return parent::create($params);
        }
    }
}
