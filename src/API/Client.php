<?php 

namespace Gogs\API {

    /** 
     * Gogs API client. 
     *
     * This class initially provide the programmer with a starting 
     * point (a kind of an "interface" to start from), to keep 
     * track of the context when going from a user, to a repo,
     * organization etc.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1
     */

    final class Client {
        use \Gogs\Lib\Curl\Client;
        const VERSION = 0.1;

        /** 
         * @param string $api_url The base URL for the Gogs API (e.g https://git.domain.tld/api/v1) 
         * @param string $api_token The token for an authorized user to query Gogs API.
         */
        public function __construct(string $api_url, string $api_token) {
            $this->url = $api_url;
            $this->token = $api_token;
        }

        /** 
         * Returns a Request\Users to fetch users from the
         * Gogs installation. 
         *
         * @see Request\Users class to understand usage (e.g ->load() to fetch all, 
         * ->search(array params) to search for one or several 
         * users etc).
         * 
         * @return Request\Users
         */
        public function users() {
            return new Request\Users($this->url, $this->token);
        }

        /** 
         * Get a single user from Gogs.
         *
         * Returns either
         * * the authorized user ($name = "" or "me")
         * * the specified user ($name = anything else)
         * 
         * @return Request\User
         */
        public function user(string $name = "me") {
            return new Request\User($this->url, $this->token, $name);
        }

        /** 
         * Returns an \Request\Repos to fetch repositories
         * on the Gogs installation. 
         *
         * @see \Request\Repos to understand usage. Inherits 
         * the same class as \R\Users, but the usage may differ!
         *
         * Note! To fetch a particular repo under a user, you
         * should go through the user (see method below).
         * 
         * @return Request\Repos
         */
        public function repos() {
            return new Request\Repos($this->url, $this->token);
        }

        /** 
         * A wrapper function as get_log on Client wont 
         * return anything. This is bogus, but.... 
         * this workaround WORKS!
         *
         * @return array
         */
        public function get_log() {
            return (new Request\User($this->url, $this->token))->get_log();
        }
    }
}
?>
