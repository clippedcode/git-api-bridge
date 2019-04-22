<?php

namespace Gogs\API\Request {

    /** 
     * Collection is a collection of data of one type.
     *
     * @see Users
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.3
     */
    abstract class Collection extends Base implements \Gogs\Lib\ArrayIterator {

        private $objs;

        /**
         * Initialize a collection.
         *
         * @see Base
         * @param string $api_url The API URL
         * @param string $api_token The API token
         * @param Collection $other Collection to initialize from
         */
        public function __construct(string $api_url, string $api_token, Collection $other = null) {
            parent::__construct($api_url, $api_token);

            if ($other != null)
                $this->objs = $others->all();
            else
                $this->objs = new \Gogs\Lib\Collection();
        }

        /**
         * Add an object to the collection.
         *
         * When adding a key the object will be stored
         * on the particual key, also overwriting existing data.
         *
         * @param mixed $obj Element to store
         * @param mixed $key Index key to store on
         * @return mixed|int The index key. If key is null the returned value will be an integer.
         */
        protected function add($obj, $key = null) {
            $this->objs->set($obj, $key);
            return $key == null ? $this->objs->len() - 1 : $key;
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
        protected function remove($any, bool $deep = true) {
            return $objs->remove($any, $deep);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function copy() {
            return new Collection($this->url, $this->token, $this);
        }
        //abstract public function copy();

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function all() {
            return $this->objs->copy()->all();
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function len() {
            return $this->objs->len();
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function by_key($idx) {
            return $this->objs->by_key($idx);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function next() {
            return $this->objs->next();
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function prev() {
            return $this->objs->prev();
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function current() {
            return $this->objs->current();
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function reset() {
            return $this->objs->reset();
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function sort(callable $f) {
            return $this->objs->copy()->sort($f);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */

        public function filter(callable $f) {
            return $this->objs->copy()->filter($f);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function limit(int $lim) {
            return $this->objs->copy()->limit($lim);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function offset(int $off) {
            return $this->objs->copy()->offset($off);
        }

        /**
         * @see \Gogs\Lib\ArrayIterator
         */
        public function reverse() {
            return $this->objs->copy()->reverse();
        }

        /**
         * @see Base
         * @return array
         */
        protected function json_set_property(\stdClass $obj) {

            $keys = array();

            if (!is_object($obj) && !is_array($obj))
                return array();

            if (isset($obj->data))
                return $this->json_set_property((object)$obj->data);

            foreach($obj as $key => $val)
                $keys[] = $this->add_object($val);

            return $keys;
        }

        /**
         * Search an collection.
         *
         * @see Base
         * @throws Exception\NotImplementedException When not implemented by Collection class.
         * @return Collection
         */
        public function search(array $params = array(), bool $strict = false) {
            throw new NotImplementedException("::search:: Not implemented by class '" . get_class($this) . "'");
        }

        /** 
         * Add an object to the collection with the specific type.
         *
         * Typically it will create an instance of the type that 
         * the collection will consist of.
         *
         * Should call json set property
         *
         * @param \stdClass $object 
         * @return array Key of entry in collection
         */
        abstract protected function add_object(\stdClass $object);

        /**
         * Sort the object
         *
         * Should call sort on parent with the specified sort method,
         * given by $flag
         *
         * @param int $flag Sorting flag
         * @return \Gogs\Lib\Collection
         */
        abstract public function sort_by(int $flag = \Gogs\Lib\ArrayIterator::SORT_INDEX);
    }

}
?>
