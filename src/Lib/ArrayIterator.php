<?php 

namespace Gogs\Lib {
    /**
     * Interface to store one or more elements in array
     * providing an iterator interface.
     * @version 0.1.1
     */
    interface ArrayIterator {
        // Default sorting method; ksort (array index)
        const SORT_INDEX = 1 << 1;
        /** 
         * Get current element in collection.
         * @return 
         */
        public function current();

        /** 
         * Get next element in collection.
         * 
         * @return mixed
         */
        public function next();

        /** 
         * Return previous element in collection.
         * @return mixed
         */
        public function prev();

        /**
         * Reset collection (set array to head).
         *
         * @return mixed Returns first elements value.
         */
        public function reset();
        
        /** 
         * Return collection size.
         *
         * @return int
         */
        public function len();
        
        /** 
         * Return the whole colection.
         *
         * @return array
         */
        public function all();
        
        /** 
         * Get element by index key.
         *
         * @param mixed $idx Index key.
         * @return mixed
         */
        public function by_key($idx);

        /**
         * Copy collection
         *
         * @return Colletion
         */
        public function copy();

        /**
         * Limit until in collection
         *
         * @param int $lim Maximum entries returned
         * @return Collection
         */
        public function limit(int $lim);

        /**
         * Get from offset collection
         *
         * @param int $off Offset from in collection
         * @return Collection
         */
        public function offset(int $off);

        /**
         * Reverse the collection
         *
         * @return Collection
         */
        public function reverse();
        /**
         * Sort collection
         *
         * @return Collection
         */
        public function sort(callable $f);

        /**
         * Filter collection
         *
         * @return Collection
         */
        public function filter(callable $f);
    }
}
