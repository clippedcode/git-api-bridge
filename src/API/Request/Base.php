<?php 

namespace Gogs\API\Request {

    /**
     * Base class for request types.
     *
     * Each request shall inherit this class to ensure
     * it will have the correct methods required by interface,
     * and get the cURL functionality.
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     * @version 0.1.4
     */
    abstract class Base implements RequestInterface {
        const VERSION = "0.1.4";

        private $tag;

        protected $loaded = false;
        protected $scope;

        use \Gogs\Lib\Curl\Client {
            get as private mget;
            post as private mpost;
            delete as private mdelete;
        }

        /**
         * @param string $api_url The URL to the API.
         * @param string $api_token A token for an authorized user
         */
        public function __construct(string $api_url, string $api_token) {
            $this->url = $api_url;
            $this->token = $api_token;
            $this->tag = strtolower(basename(str_replace("\\", "/", get_class($this))));
        }

        /**
         * Load an object.
         *
         * If `$force = true` the object will be fetched
         * from the Gogs API again.
         *
         * @throws Exception\NotImplementedException when method doesnt support load
         * @return object
         */
        final public function load(bool $force = false) {
            if (!$this->set_scope("load"))
                throw new Exception\NotImplementedException("::load:: Not implemented for class '" . get_class($this) . "'");

            if ($this->loaded && !$force)
                return $this;

            $jenc =  $this->mget($this->scope);

            $this->json_set_property($this->json_decode($jenc));
            /*
             * JSON set property should also do this, but
             * to ensure its done, we'll also do it here
             */
            $this->loaded = true;

            return $this;
        }

        /**
         * Perform a GET-request against the Gogs API.
         *
         * Ensure the correct scope i set first, with
         * ```php
         * $this->set_scope("*valid scope*"); // e.g create
         * ```
         *
         * @param array $params The parameters
         * @return string
         */
        final protected function method_get(array $params = array()) {
            return $this->mget($this->scope, $params);
        }

        /**
         * Perform a POST-request against the Gogs API.
         *
         * Ensure the correct scope i set first, with
         * ```php
         * $this->set_scope("*valid scope*"); // e.g create
         * ```
         *
         * @param array $params The parameters
         * @return string
         */
        final protected function method_post(array $params = array()) {
            return $this->mpost($this->scope, $params);
        }

        /**
         * Perform a DELETE-request against the Gogs API.
         *
         * Ensure the correct scope i set first, with
         * ```php
         * $this->set_scope("*valid scope*"); // e.g delete
         * ```
         *
         * @return string
         */
        final protected function method_delete() {
            return $this->mdelete($this->scope);
        }

        /** 
         * Get object references by identifier.
         *
         * @param string $s Identifier to look up.
         * @return null
         */
        public function get(string $s) {
            //if (!$this->set_scope("get"))
            throw new Exception\NotImplementedException("::get:: Not implemented for class '" . get_class($this) . "'");

            return null;
        }

        /** 
         * Create object inherited by class.
         *
         * Child class must add a scope for 'create' and ensure child is not *loaded*,
         * otherwise will `create` throw an exception.
         *
         * @param string $args yeah, well
         * @return true
         * @throws Exception\InvalidMethodRequestException
         * @throws Exception\NotImplementedException
         */
        public function create(...$args) {

            if ($this->loaded)
                throw new Exception\InvalidMethodRequestException("::create:: Cant create on an git-initialized object. Create new object.");

            if (!$this->set_scope("create"))
                throw new Exception\NotImplementedException("::create:: Not implemented for class '" . get_class($this) . "'");

            $ret = $this->method_post(...$args);

            $this->json_set_property((object)$this->json_decode($ret));

            return $this;
        }

        /**
         * Patch (update) object
         *
         * @throws Exception\InvalidMethodRequestException
         * @throws Exception\NotImplementedException
         */
        public function patch() {

            if (!$this->loaded)
                throw new Exception\InvalidMethodRequestException("::patch:: Cant patch an git-uninitialized object. Load it first.");

            if (!$this->set_scope("patch"))
                throw new Exception\NotImplementedException("::patch:: Not implemented for class '" . get_class($this) . "'");
        }

        /**
         * Delete object.
         *
         * @throws Exception\NotImplementedException
         */
        public function delete() {
            if (!$this->set_scope("delete"))
                throw new Exception\NotImplementedException("::delete:: Not implemented for class '" . get_class($this) . "'");

            return $this->method_delete();
        }

        /** 
         * Decode JSON-string.
         *
         * Will ensure that there weren't any errors by calling `$this->json_error`.
         *
         * @param string $jenc Encoded JSON string
         * @return object
         */
        final protected function json_decode(string $jenc) {
            $obj = json_decode($jenc);

            $this->json_error();

            return (object)$obj;
        }

        /** 
         * Encode JSON-object/array.
         *
         * Will ensure that there weren't any errors by calling `$this->json_error`.
         *
         * @param iterable $jdec JSON-data
         * @return string
         */
        final protected function json_encode(iterable $jdec) {
            $jenc = json_encode($jdec);

            $this->json_error();

            return $jenc;
        }

        /** 
         * Check for errors on encoding/decoding.
         *
         * @throws Exception\RequestErrorException
         */
        final protected function json_error() {
            if (($err = json_last_error()) != JSON_ERROR_NONE)
                throw new Exception\RequestErrorException(json_last_error_msg(), $err);
        }

        /** 
         * Set properties for the current object.
         *
         * Each child class must implement this to set its data. Will
         * be called by methods such as `load` and from collection
         * classes.
         *
         * Will return true/false for singel objects but an array on collections.
         * The array will contain the newly inserted elements. This to prevent
         * additional iterations.
         *
         * This method should also set loaded to true or false, depending
         * on success or failure.
         *
         * @see Collection
         * @param mixed $obj
         * @return true|array Array with keys on collections
         */
        protected function json_set_property(\stdClass $obj) {

            foreach ($obj as $key => $value) {
                if ($this->property_exists($key))
                    $this->{$key} = $value;
                else
                    echo "Unknown proerty " . $key . "\n";
            }

            $this->loaded = true;   

            return true;
        }

        /** 
         * Get basename of a class (remove namespace).
         * 
         * @param string $class The FQN
         * @return string
         */
        private function basename_class(string $class) {
            return strtolower(basename(str_replace("\\", "/", $class)));
        }

        /** 
         * Return basename of parent class.
         * 
         * @return string
         */
        private function get_parent() {
            $parent = get_parent_class($this);

            if ($parent != __CLASS__)
                return $this->basename_class($parent);

            return null;
        }

        /**
         * Get property key by name.
         *
         * Classes sets property from json directly, but they are
         * named within the class by `classname_propertyname`. This
         * method returns the key name.
         *
         * @param string $name Name of the key
         * @param bool $parent Get key in parent
         * @return string
         */
        final private function key(string $name, bool $parent = false) {

            $tag = sprintf("%s_", $this->tag);

            if (strpos($name, $tag) === 0) {
                if ($parent && !empty($ptag = $this->get_parent()))
                    return sprintf("%s_%s", $ptag, substr($name, strlen($tag)));

                return $name . "?";
            }

            if ($parent && !empty($ptag = $this->get_parent()))
                return sprintf("%s_%s", $ptag, $name);

            return $tag . $name;
        }

        /** 
         * Checks if the property (key) exists within self
         * or parent class.
         *
         * Returns the actual key if it does. A class key (aka property)
         * start with the tag `classname_` followed by property name, 
         * reflecting the JSON-object, and can be reached by
         *
         *  * `$class->parameter`,
         *  * `$class->classname_parameter` or alternatively (for classes that inherits another class).
         *  * `$class->parentclassname_parameter`.
         *
         *  If a class override a parent class with the same parameter,
         *  the class's own parameter will be favoured.
         *
         * As this is public properties this wont be an security issue;
         * 
         * @param $name Name of the key.
         * @return string|false False on failure
         */
        final protected function property_exists($name) {
            if (property_exists($this, $key = $this->key($name)))
                return $key;

            if (property_exists($this, $key = $this->key($name, true)))
                return $key;

            return false;
        }

        /** 
         * Get property by name.
         *
         * Checks both self and parent for the property.
         *
         * Returns the value if property exists, otherwise an `E_USER_NOTICE`
         * is triggered.
         *
         * @param string $name 
         * @return mixed|null Null when unknown
         */
        final public function __get(string $name) {

            $key = $this->property_exists($name);

            if ($key)
                return $this->{$key};

            $trace = debug_backtrace();

            trigger_error(
                sprintf(
                    "Undefined property '%s' {%s} in '%s' on line %s. Neither does its parent '%s'",
                    $name,
                    $this->key($name),
                    $trace[0]["file"],
                    $trace[0]["line"],
                    $this->basename_class(get_parent_class($this))
                ),
                E_USER_NOTICE
            ); 

            return null;
        }

        /** 
         * Set property by name.
         *
         * Checks both self and parent for the property.
         *
         * Returns the value if property exists, otherwise an `E_USER_NOTICE`
         * is triggered.
         *
         * @param string $name Property name
         * @param mixed $value Property value
         * @return mixed|null Null when unknown
         */
        final public function __set(string $name, $value) {

            $key = $this->property_exists($name);

            if ($key)
                return $this->{$key} = $value;

            $trace = debug_backtrace();

            trigger_error(
                sprintf(
                    "Undefined property '%s' {%s} in '%s' on line %s. Neither does its parent '%s'",
                    $name,
                    $this->key($name),
                    $trace[0]["file"],
                    $trace[0]["line"],
                    $this->basename_class(get_parent_class($this))
                ),
                E_USER_NOTICE
            ); 

            return null;
        }

        /** 
         * Checks if property is set.
         *
         * Checks both self and parent for property.
         *
         * Triggers E_USER_NOTICE if property is unknown.
         *
         * @param string $name Property name
         * @return bool
         */
        final public function __isset(string $name) {

            $key = $this->property_exists($name);

            if ($key)
                return isset($this->{$key});

            $trace = debug_backtrace();

            trigger_error(
                sprintf(
                    "Undefined property '%s' {%s} in '%s' on line %s. Neither does its parent '%s'",
                    $name,
                    $this->key($name),
                    $trace[0]["file"],
                    $trace[0]["line"],
                    $this->basename_class(get_parent_class($this))
                ),
                E_USER_NOTICE
            ); 

            return false;
        }

        /** 
         * Set the scope for the request methods accepted by the child.
         *
         * This can be
         *  * `get`,
         *  * `search`,
         *  * `delete` etc.
         *
         *  Must return true if scope exists of false otherwise. Methods
         *  the calls this will throw an exception if not true is returned.
         *
         * @param string $method Method type, e.g "get"
         * @return bool
         */
        abstract protected function set_scope(string $method);

        /** 
         * Search for an matching object.
         *
         * Methods do OR-ing and not AND-ing by default.
         *
         * Params should be key (object property) and value that 
         * this parameter should match, e.g
         *
         * ```
         * $repo->search(
         *  "name" => "this",
         *  "owner" => array(
         *      "username" => "that"
         *  )
         * );
         * ```
         *
         * will match `"this" IN $repo->name OR "that" IN $repo->owner->username` .
         *
         * @param array $params Parameters
         * @param bool $strict Turn search into AND-ing, require match in each field.
         * @throws Exception\SearchParamException when invalid property
         * @return true
         */
        protected function search(array $params = array(), bool $strict = false) {

            if (empty($params))
                return false;

            foreach ($params as $key => $value) {
                if (!$this->property_exists($key))
                    throw new Exception\SearchParamException("Invalid property exception");

                if (is_array($value) && !$strict && $this->{$key}->search($value, $strict))
                    return true;
                else if (is_array($value) && $strict && $this->{$key}->search($value, $strict))
                    return false;
                else if (!$strict && stripos($this->{$key}, $value) !== false)
                    return true;
                else if ($strict && stripos($this->{$key},$value) === false)
                    return false;
            }

            return (!$strict ? false : true);
        }

    }

}

?>
