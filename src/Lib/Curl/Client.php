<?php 

namespace Gogs\Lib\Curl {

    /** 
     * A trait used for every class referencing the api-url and token.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @package curl
     * @version 0.1.3
     */
    trait Client {
        private $_version = "0.1.3";

        private static $log = array();

        protected $url;
        protected $token;
        protected $basic = false;

        protected $user_agent = "Gogs PHP API Client/%s (%s) PHP/%s Client\\%s %s";
        protected $timeout = 30;
        protected $max_redirects = 4;

        /** 
         * Basic sets the user for basic HTTP-authentication.
         *
         * @param string $user 
         */
        protected function basic(string $user) {
            $this->basic = $user;
        }

        /**
         * Set param into array
         *
         * The specified callback will only run if the expected
         * parameter is set. This callback can either overwrite
         * paramtere as passing them as reference or throw an exception
         * to indicate invalid data.
         *
         * @param array &$params Array to insert to
         * @param string $param_name Index in params-array
         * @param array $args Arguments array
         * @param int $index Index in arguments array
         * @param string $type Expected type of data
         * @param mixed $default Default if not expected type on index
         * @param callback $f Callback method if param is set
         */
        protected function set_param(array &$params, string $param_name, array $args, int $index, string $type, $default = null, callable $f = null) {

            switch ($type) {
            case "str":
                $type = "string";
                break;
            case "int":
                $type = "integer";
                break;
            case "float":
                $type = "double";
                break;
            case "bool":
                $type = "boolean";
                break;
            }

            $type = $type == "bool" ? "boolean" : ($type == "float" ? "double" : $type);
            $params[$param_name] = isset($args[$index]) && gettype($args[$index]) == $type ? $args[$index] : $default;

            if ($f != null && $params[$param_name] != $default)
                $f($params[$param_name]);
        }

        /** 
         * Filter out NULL values from parameters.
         *
         * Saves transferring size.
         * 
         * @param array &$params Parameters
         */
        protected function filter_params(array &$params) {
            $params = array_filter($params, function($val) {
                return $val != null;
            });
        }

        /** 
         * array_2_params takes an array and converts it into a
         * query string (e.g param=val&param2=val2).
         *
         * @param array $params parameters to pass
         * @return string
         */
        private function array_2_params(array $params) {
            return join("&", array_map(function($k, $v) {
                return sprintf("%s=%s", $k, rawurlencode(is_bool($v) ? ($v ? "true" : "false") : $v ));
            }, array_keys($params), $params));
        }

        /** 
         * array_2_json takes an array and converts it into a
         * json-string (e.g {'name': 'This'}) which is typically
         * used in a request body.
         * 
         * @param array $params paramters to pass
         * @return string
         */
        private function array_2_json(array $params) {
            return count($params) == 0 ? null : json_encode($params);
        }

        /** 
         * Initializes a curl request of different kinds, depending
         * on the specified method. This can be
         *
         * DELETE, PATCH, POST or GET. An unidentified value will
         * become a GET-request.
         * 
         * @param string $method either DELETE, PATCH, POST, GET
         * @param string &$req variable to store request body in
         * @param string $scope scope within the API (e.g /user/repos)
         * @param array $params parameters to pass
         * @param bool $ret return transfer
         * @return int the status code
         */
        protected function method(string $method, string &$req, string $scope, array $params, bool $ret) {

            $c = curl_init();

            if (!$c) {
                return false;
            }

            $headers = array();

            $url = sprintf("%s%s", $this->url, $scope);

            curl_setopt($c, CURLOPT_USERAGENT, $agent = sprintf($this->user_agent, $this->_version, PHP_OS, phpversion(), get_class($this), self::VERSION));

            self::$log[] = sprintf(
                "%s:[%s] %s, %s, %s", 
                date("y-m-d H:i:s"), 
                $method, 
                $url, 
                !empty($p = $this->array_2_json($params)) ? $p : "none",
                $agent
            );

            if (!$this->basic)
                $headers[] = sprintf("Authorization: token %s", $this->token);
            else
                curl_setopt($c, CURLOPT_USERPWD, sprintf("%s:%s", $this->basic, $this->token));

            if (in_array($method, array("DELETE", "PATCH", "POST"))) {
                $json = $this->array_2_json($params);
                curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($c, CURLOPT_POSTFIELDS, $json);
                array_unshift($headers, "Content-Type: application/json");
                array_push($headers, "Content-Length: " . strlen($json));
            } else {
                $url .= !empty($params = $this->array_2_params($params)) ? "?" . $params : "";
            }


            curl_setopt($c, CURLOPT_URL, $url);
            curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, $ret);
            curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($c, CURLOPT_MAXREDIRS, $this->max_redirects);
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);

            $req = curl_exec($c);

            $status_code = curl_getinfo($c, CURLINFO_HTTP_CODE);

            curl_close($c);

            array_push(
                self::$log,
                sprintf(
                    "%s:[%s] %s, %d, %s", 
                    date("y-m-d H:i:s"), 
                    $method, 
                    $url,
                    $status_code,
                    substr($req, 0, 100) . (strlen($req) > 100 ? "..." : ".")
                )
            );

            return $status_code;
        }

        /** 
         * Checks if the user is authorized for the scope. Shouldn't
         * be used frequently. One test for one scope should be enough,
         * but if you know for sure thats you're programming with the
         * use of an authorized user you should leave this and just
         * handle the NotAuthorizedExeption whenever thrown.
         *
         * @param $scope the scope, a relative uri.
         * @throws Not AuthorizedException if server responde with a 401
         * @return bool
         */
        protected function authorized(string $scope = "") {
            $ret = "";
            if (in_array(($code = $this->method("GET", $ret, $scope, array(), false)),
                array(400, 401, 402, 403)
            )) {
                throw new NotAuthorizedException("Not authorized", 401);
            }
            return true;
        }

        /**
         * Post method.
         *
         * @param string $scope the scope, a relative uri.
         * @param array $params the parameters to post.
         * @throws NotAuthorizedException on 401, 403
         * @throws HTTPUnexpectedResponse when not 200,201,401,403
         * @return string the request content.
         */
        private function post(string $scope = "", array $params = array()) {
            $req = "";

            $code = $this->method("POST", $req, $scope, $params, true);

            switch ($code) {
            case 200:
            case 201:
            case 202:
                return $req;
            case 400:
            case 401:
            case 403:
                throw new Exception\NotAuthorizedException($req, $code);
            default:
                throw new Exception\HTTPUnexpectedResponse($req, $code);
            }
        }

        /**
         * Delete method.
         *
         * @param string $scope the scope, a relative uri.
         * @throws NotAuthorizedException on 401, 403
         * @throws HTTPUnexpectedResponse when not 200,204,401,403
         * @return string the request content.
         */
        private function delete(string $scope = "") {
            $req = "";

            $code = $this->method("DELETE", $req, $scope, array(), true);

            switch ($code) {
            case 200:
            case 204:
                return true;
            case 401:
            case 403:
                throw new Exception\NotAuthorizedException($req, $code);
            default:
                throw new Exception\HTTPUnexpectedResponse($req, $code);
            }
        }

        /**
         * GET method.
         *
         * @param string $scope the scope, a relative uri.
         * @param array $params the parameters to post.
         * @throws NotAuthorizedException on 401, 403
         * @throws HTTPUnexpectedResponse when not 200,401,403
         * @return string the request content.
         */
        private function get($scope = "", $params = array()) {
            $req = "";

            $code = $this->method("GET", $req, $scope, $params, true);

            switch ($code) {
            case 200:
                return $req;
            case 401:
            case 403:
                throw new Exception\NotAuthorizedException($req, $code);
            default:
                throw new Exception\HTTPUnexpectedResponse($req, $code);

            }
        }

        /**
         * Returns log entries for the client.
         * 
         * @return array
         */
        public static function get_log() {
            if (empty(self::$log))
                return self::$log;

            return array_merge(self::$log, array("\n"));
        }

    }

}
?>
