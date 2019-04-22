<?php 

namespace Gogs\API\Request {

    /** 
     * Request interface, used by any kind of request object.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1
     */
    interface RequestInterface {

        /** 
         * Load object. 
         *
         * @todo reconsider $force = false
         * @param bool $force Force update, default: true
         * @return object
         */
        public function load(bool $force = false);

        /** 
         * Get by identifier
         *
         * @param string $s The idientifier to look up
         * @return object
         */
        public function get(string $s);

        /**
         * Create object
         *
         * @param ... $args Arguments required by create.
         * @return bool
         */
        public function create(...$args);

        /**
         * Patch (update) object
         * 
         * @return bool
         */
        public function patch();

        /**
         * Delete object
         *
         * @return bool
         */
        public function delete();
    }

}
?>
