<?php

namespace Gogs\API\Request {

    /** 
     * A single Branch
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1
     */
    final class Branch extends Base {
        private $repo;

        public $branch_name;
        public $branch_commit;

        /** 
         * Initialize a branch for the given repository.
         *
         * @see Base
         * @param string $api_url The API URL
         * @param string $api_token The API token
         * @param Repo $repo Related repository
         */
        public function __construct(string $api_url, string $api_token, Repo $repo) {
            parent::__construct($api_url, $api_token);
            $this->repo = $repo;
        }

        /**
         * @see Base
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "load":
                $this->scope = sprintf("/repos/%s/%s/branches/%s", $this->repo->owner, $this->repo->name, $this->name);
                return true;
            }
            return false;
        }
    }

}

?>
