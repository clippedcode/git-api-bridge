<?php

namespace Gogs\Lib {

    /** 
     * Base class for collections. Implements basic
     * functions and typically used to return collections
     * which wont be a part of the "request package"
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.3
     */
    class Collection implements ArrayIterator {
        private $objs = array();

        public function __construct(array $arr = array()) {
            $this->objs = $arr;
        }
        /**
         * Set value(e) to the collection.
         *
         * If the value is an array it will overwrite
         * the whole object-array, aka everything.
         */
        public function set($val, $key = null) {
            if ($key == null && is_array($val))
                $this->objs = $val;
            else if ($key != null)
                $this->objs[$key] = $val;
            else 
                array_push($this->objs, $val);
        }

        /** 
         * @see ArrayIterator
         */
        public function by_key($idx) {
            return isset($this->objs[$idx]) ? $this->objs[$idx] : false;
        }

        public function copy() {
            return new Collection($this->all());
        }

        /** 
         * @see ArrayIterator
         */
        public function all() {
            return $this->objs;
        }

        /** 
         * @see ArrayIterator
         */
        public function len() {
            return count($this->objs);
        }

        /** 
         * @see ArrayIterator
         */
        public function next() {
            return next($this->objs);
        }

        /**
         * @see ArrayIterator
         */
        public function prev() {
            return prev($this->objs);
        }

        /** 
         * @see ArrayIterator
         */
        public function current() {
            return current($this->objs);
        }

        /** 
         * @see ArrayIterator
         */
        public function reset() {
            return reset($this->objs);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function sort(callable $f) {
            if ($f == "" || $f == "ksort")
                return ksort($this->objs) ? $this : false;

            return uasort($this->objs, $f) ? $this : false;
        }

        public function filter(callable $f) {
            $this->objs = array_filter($this->objs, $f);
            return $this;
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function limit(int $lim) {
            $this->objs = array_slice($this->objs, 0, $lim);
            return $this;
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function offset(int $off) {
            $this->objs = array_slice($this->objs, $off);
            return $this;
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function reverse() {
            $this->objs = array_reverse($this->objs);
            return $this;
        }
        /** 
         * Remove an element in collection.
         *
         * The function will first look for the element as a
         * index key, but if its not found it will look for the
         * element as a value.
         *
         * Deep functions only when the value is given and not the key.
         *
         * @deprecated 0.1.1 Will be removed in future release
         * @param mixed $any Index key or element value
         * @param bool $deep Delete every item and not just the first 
         * @return bool
         */
        public function remove($any, bool $deep = true) {
            if (isset($this->objs[$any])) {
                unset($this->objs[$any]);
                return true;
            } else if (in_array($any, $this->objs)) {
                $key = array_search($any, $this->objs, true);

                // No need to add deep ($key deletion)
                $val = $this->remove($key);

                if ($val && $deep) // Delete every object
                    $this->remove($any, $deep);

                return $val;
            }
            return false;
        }
    }

}
