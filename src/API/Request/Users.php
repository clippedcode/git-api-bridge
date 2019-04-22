<?php 

namespace Gogs\API\Request {
    /** 
     * Returns one or more users in the Gogs installation, 
     * depending on the called method. 
     * 
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @package request
     * @version 0.1.3
     */
    final class Users extends Collection {

        /** 
         * @see Base
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "search":
                $this->scope = "/users/search";
                break;
            default:
                return false;
            }

            return true;
        }

        /**
         * Returns a new user object. If arguments
         * is specified the user will be "created".
         *
         * The arguments can be left out to "create" the
         * user through the user object iteself.
         *
         * @param ...$args User->create arguments
         * @return User
         */
        public function create(...$args) {

            $user = new User($this->url, $this->token);

            if (count($args) != 0) {
                $user->create(...$args);
                $this->add($user, $user->username);
            }

            return $user;
        }

        /** 
         * Return a user by username.
         * 
         * @param string $s The username
         * @return 
         */
        public function get(string $s) {

            if ($this->by_key($s))
                return $this->by_key($s);

            $user = (new User($this->url, $this->token, $s))->load();

            $this->add($user, $user->login);

            return $user;
        }

        /**
         * Search for an user
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

            if (!isset($params["name"]) && !isset($params['q']))
                throw new Exception\SearchParamException("Missing param <name>|<q>");

            if (isset($params["name"])) {
                $params["q"] = $params["name"];
                unset($params["name"]);
            }

            $this->set_scope("search");

            $old = $this->all();

            $this->json_set_property(
                $this->json_decode(
                    $this->method_get($params)
                )
            );

            $users = new Users($this->url, $this->token);

            $users->add(array_diff_key($this->all(), $old));

            return $users;
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
            $user = new User($this->url, $this->token, $obj->username);
            $user->json_set_property($obj);
            $this->add($user, $user->username);
        }

    }

}

?>
