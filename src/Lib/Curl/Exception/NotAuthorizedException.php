<?php 

namespace Gogs\Lib\Curl\Exception {

    /** 
     * When the request fails because of an unauthorized token,
     * this is thrown instead.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @package curl
     * @version 0.1.1
     */
    class NotAuthorizedException extends HTTPUnexpectedResponse {

        /**
         * Sets the exceptions.
         *
         * @string $message - the response from the server.
         * @string $code - the HTTP status code, @default 401
         * @exception $prev - Previous exceptions
         **/
        public function __construct($message, $code = 401, \Exception $previous = null) {
            parent::__construct($message, $code, $previous);
        }
    }

}
?>
