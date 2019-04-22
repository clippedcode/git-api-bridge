<?php 

namespace Gogs\API\Request {

    /** 
     * Stores data and methods related to a single organization.
     *
     * By now the following are supported:
     *
     *  * GET `/orgs/username`
     *  * POST `/admin/users/username/orgs` (**Requires** admin rights. Curl will throw NotAuthorized exception if not).
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.4
     */
    final class Org extends User {
        public $org_description;
        public $org_website;
        public $org_location;

        private $owner;

        /**
         * Initialize an organization.
         *
         * @see Base
         * @param string $api_url The API URL
         * @param string $api_token The API token
         * @param User $owner The owner of the organization
         * @param string $oname Organization name
         */
        public function __construct(string $api_url, string $api_token, User $owner = null, string $oname = null)  {
            parent::__construct($api_url, $api_token);
            $this->username = $oname;
            $this->owner = $owner;
        }

        /** 
         * @see Base
         * @throws Exception\InvalidMethodRequestException when owner is not set
         * @throws Exception\RequestErrorException when missing organization data
         */
        protected function set_scope(string $method) {
            switch ($method) {
            case "create":
                if ($this->owner == null)
                    throw new Exception\InvalidMethodRequestException("Cant create organization without a related User");

                $this->scope = "/admin/users/" . $this->owner->username . "/orgs";
                return true;
            case "get":
                if (!$this->username)
                    throw new Exception\RequestErrorException("Missing organization-data 'username'.");

                $this->scope = "/orgs/" . $this->username;
                return true;
            }
            return false;
        }

        /** 
         * Create a new user
         *
         * Valid parameters:
         *
         *  1. username
         *  2. full_name
         *  3. description
         *  4. website
         *  5. location
         *
         *  This reflects the API v1 doc, but is in an order
         *  where the required fields is first.
         *
         * @todo Create team within org with user
         * @param ...$args The parameter values.
         * @return Org
         */
        public function create(...$args) {

            $params = array();

            $this->set_param($params, "username", $args, 0, "string", null);
            $this->set_param($params, "full_name", $args, 1, "string", null);
            $this->set_param($params, "description", $args, 2, "string", null);
            $this->set_param($params, "website", $args, 3, "string", null);
            $this->set_param($params, "location", $args, 4, "string", null);

            $this->filter_params($params);

            return Base::create($params);
        }
    }
}

?>
