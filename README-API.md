
Gogs PHP API Client
===

# Table of content    
1. [Gogs](#gogs "Gogs")
	1. [API](#gogsapi "Gogs\API")
		1. [Request](#gogsapirequest "Gogs\API\Request")
			1. [Base](#base-gogsapirequest "Gogs\API\Request\Base") Base class for request types.
			2. [Branch](#branch-gogsapirequest "Gogs\API\Request\Branch") A single Branch
			3. [Branches](#branches-gogsapirequest "Gogs\API\Request\Branches") Holds a collection of Branches for a Repository.
			4. [Collection](#collection-gogsapirequest "Gogs\API\Request\Collection") Collection is a collection of data of one type.
			5. [Exception](#gogsapirequestexception "Gogs\API\Request\Exception")
				1. [InvalidMethodRequestException](#invalidmethodrequestexception-gogsapirequestexception "Gogs\API\Request\Exception\InvalidMethodRequestException") Thrown whenever a class that inherits the base-classis used wrong (e.g tries to create on a loaded object)
				2. [NotImplementedException](#notimplementedexception-gogsapirequestexception "Gogs\API\Request\Exception\NotImplementedException") Thrown when the requested method for a class isn'timplemented.
				3. [RequestErrorException](#requesterrorexception-gogsapirequestexception "Gogs\API\Request\Exception\RequestErrorException") Typically thrown when needed data to build the queryis missing.
				4. [SearchParamException](#searchparamexception-gogsapirequestexception "Gogs\API\Request\Exception\SearchParamException") Thrown when needed parameters for a search is missing.
			6. [Org](#org-gogsapirequest "Gogs\API\Request\Org") Stores data and methods related to a single organization.
			7. [Orgs](#orgs-gogsapirequest "Gogs\API\Request\Orgs") Orgs is a collection of organizations.
			8. [Repo](#repo-gogsapirequest "Gogs\API\Request\Repo") Stores data and methods related to a single repository.
			9. [Repos](#repos-gogsapirequest "Gogs\API\Request\Repos") Repos is a collection of repos.
			10. [RequestInterface](#requestinterface-gogsapirequest "Gogs\API\Request\RequestInterface") Request interface, used by any kind of request object.
			11. [Token](#token-gogsapirequest "Gogs\API\Request\Token") A token related to a user
			12. [Tokens](#tokens-gogsapirequest "Gogs\API\Request\Tokens") Collection of tokens for a given user.
			13. [User](#user-gogsapirequest "Gogs\API\Request\User") Stores user data and methods related to a single user.
			14. [Users](#users-gogsapirequest "Gogs\API\Request\Users") Returns one or more users in the Gogs installation,depending on the called method.
		2. [Client](#client-gogsapi "Gogs\API\Client") Gogs API client.
	2. [Lib](#gogslib "Gogs\Lib")
		1. [Curl](#gogslibcurl "Gogs\Lib\Curl")
			1. [Client](#client-gogslibcurl "Gogs\Lib\Curl\Client") A trait used for every class referencing the api-url and token.
			2. [Exception](#gogslibcurlexception "Gogs\Lib\Curl\Exception")
				1. [HTTPUnexpectedResponse](#httpunexpectedresponse-gogslibcurlexception "Gogs\Lib\Curl\Exception\HTTPUnexpectedResponse") Defines an unexpected response.
				2. [NotAuthorizedException](#notauthorizedexception-gogslibcurlexception "Gogs\Lib\Curl\Exception\NotAuthorizedException") When the request fails because of an unauthorized token,this is thrown instead.
		2. [ArrayIterator](#arrayiterator-gogslib "Gogs\Lib\ArrayIterator") Interface to store one or more elements in arrayproviding an iterator interface.
		3. [Collection](#collection-gogslib "Gogs\Lib\Collection") Base class for collections. Implements basicfunctions and typically used to return collectionswhich wont be a part of the "request package"


---
Documentation
---

# Gogs

 * [Gogs\API](#gogsapi "Namespace: Gogs\API")
 * [Gogs\API\Request](#gogsapirequest "Namespace: Gogs\API\Request")
 * [Gogs\API\Request\Exception](#gogsapirequestexception "Namespace: Gogs\API\Request\Exception")
 * [Gogs\Lib](#gogslib "Namespace: Gogs\Lib")
 * [Gogs\Lib\Curl](#gogslibcurl "Namespace: Gogs\Lib\Curl")
 * [Gogs\Lib\Curl\Exception](#gogslibcurlexception "Namespace: Gogs\Lib\Curl\Exception")

# Gogs\API

## Classes

### Client `Gogs\API`


* Class uses [Gogs\Lib\Curl\Client](#client-gogslibcurl)

Gogs API client.

This class initially provide the programmer with a starting
point (a kind of an "interface" to start from), to keep
track of the context when going from a user, to a repo,
organization etc.

#### Methods

|Name                                     |Return                        |Access|Description                                                                      |
|:---                                     |:---                          |:---  |:---                                                                             |
|[__construct](#__construct-gogsapiclient)|                              |public|                                                                                 |
|[users](#users-gogsapiclient)            |[Users](#users-gogsapirequest)|public| Returns a Request\Users to fetch users from theGogs installation.               |
|[user](#user-gogsapiclient)              |[User](#user-gogsapirequest)  |public| Get a single user from Gogs.                                                    |
|[repos](#repos-gogsapiclient)            |[Repos](#repos-gogsapirequest)|public| Returns an \Request\Repos to fetch repositorieson the Gogs installation.        |
|[get_log](#get_log-gogsapiclient)        |array                         |public| A wrapper function as get_log on Client wontreturn anything. This is bogus, but.|

#### Method details

##### __construct `Gogs\API\Client`
```php
public function __construct(string $api_url, string $api_token);
```

Parameters

| Type | Variable | Description                                                     |
|---   |---       |---                                                              |
|string|$api_url  |The base URL for the Gogs API (e.g https://git.domain.tld/api/v1)|
|string|$api_token|The token for an authorized user to query Gogs API.              |
---


##### users `Gogs\API\Client`
```php
public function users();
```
 Returns a Request\Users to fetch users from the
Gogs installation.


Returns: [Users](#users-gogsapirequest)

---


##### user `Gogs\API\Client`
```php
public function user(string $name = "me");
```
 Get a single user from Gogs.

Returns either
* the authorized user ($name = "" or "me")
* the specified user ($name = anything else)


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$name     |*None*       |

Returns: [User](#user-gogsapirequest)

---


##### repos `Gogs\API\Client`
```php
public function repos();
```
 Returns an \Request\Repos to fetch repositories
on the Gogs installation.


Returns: [Repos](#repos-gogsapirequest)

---


##### get_log `Gogs\API\Client`
```php
public function get_log();
```
 A wrapper function as get_log on Client wont
return anything. This is bogus, but.

...
this workaround WORKS!


Returns: array

---

# Gogs\API\Request

## Classes

### Base `Gogs\API\Request`


* Class implements[Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)
* Class uses [Gogs\Lib\Curl\Client](#client-gogslibcurl)

Base class for request types.

Each request shall inherit this class to ensure
it will have the correct methods required by interface,
and get the cURL functionality.

#### Methods

|Name                                                      |Return                                       |Access            |Description                                                     |
|:---                                                      |:---                                         |:---              |:---                                                            |
|[__construct](#__construct-gogsapirequestbase)            |                                             |public            |                                                                |
|[load](#load-gogsapirequestbase)                          |object                                       |final public      | Load an object.                                                |
|[method_get](#method_get-gogsapirequestbase)              |string                                       |final protected   | Perform a GET-request against the Gogs API.                    |
|[method_post](#method_post-gogsapirequestbase)            |string                                       |final protected   | Perform a POST-request against the Gogs API.                   |
|[method_delete](#method_delete-gogsapirequestbase)        |string                                       |final protected   | Perform a DELETE-request against the Gogs API.                 |
|[get](#get-gogsapirequestbase)                            |object                                       |public            | Get object references by identifier.                           |
|[create](#create-gogsapirequestbase)                      |bool                                         |public            | Create object inherited by class.                              |
|[patch](#patch-gogsapirequestbase)                        |bool                                         |public            | Patch (update) object                                          |
|[delete](#delete-gogsapirequestbase)                      |bool                                         |public            | Delete object.                                                 |
|[json_decode](#json_decode-gogsapirequestbase)            |object                                       |final protected   | Decode JSON-string.                                            |
|[json_encode](#json_encode-gogsapirequestbase)            |string                                       |final protected   | Encode JSON-object/array.                                      |
|[json_error](#json_error-gogsapirequestbase)              |                                             |final protected   | Check for errors on encoding/decoding.                         |
|[json_set_property](#json_set_property-gogsapirequestbase)|[true](#true-gogsapirequest) ***v*** array   |protected         | Set properties for the current object.                         |
|[property_exists](#property_exists-gogsapirequestbase)    |string ***v*** [false](#false-gogsapirequest)|final protected   | Checks if the property (key) exists within selfor parent class.|
|[__get](#__get-gogsapirequestbase)                        |mixed ***v*** null                           |final public      | Get property by name.                                          |
|[__set](#__set-gogsapirequestbase)                        |mixed ***v*** null                           |final public      | Set property by name.                                          |
|[__isset](#__isset-gogsapirequestbase)                    |bool                                         |final public      | Checks if property is set.                                     |
|[set_scope](#set_scope-gogsapirequestbase)                |bool                                         |abstract protected| Set the scope for the request methods accepted by the child.   |
|[search](#search-gogsapirequestbase)                      |[true](#true-gogsapirequest)                 |protected         | Search for an matching object.                                 |

#### Method details

##### __construct `Gogs\API\Request\Base`
```php
public function __construct(string $api_url, string $api_token);
```

Parameters

| Type | Variable | Description                  |
|---   |---       |---                           |
|string|$api_url  |The URL to the API.           |
|string|$api_token|A token for an authorized user|
---


##### load `Gogs\API\Request\Base`
```php
final public function load(bool $force = false);
```
 Load an object.

If `$force = true` the object will be fetched
from the Gogs API again.


Parameters

| Type | Variable | Description               |
|---   |---       |---                        |
|bool  |$force    |Force update, default: true|

Returns: object

Throws: 

* [NotImplementedException](#notimplementedexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\NotImplementedException")

---


##### method_get `Gogs\API\Request\Base`
```php
final protected function method_get(array $params = array());
```
 Perform a GET-request against the Gogs API.

Ensure the correct scope i set first, with
```php
$this->set_scope("*valid scope*"); // e.g create
```


Parameters

| Type | Variable | Description |
|---   |---       |---           |
|array|$params   |The parameters|

Returns: string

---


##### method_post `Gogs\API\Request\Base`
```php
final protected function method_post(array $params = array());
```
 Perform a POST-request against the Gogs API.

Ensure the correct scope i set first, with
```php
$this->set_scope("*valid scope*"); // e.g create
```


Parameters

| Type | Variable | Description |
|---   |---       |---           |
|array|$params   |The parameters|

Returns: string

---


##### method_delete `Gogs\API\Request\Base`
```php
final protected function method_delete();
```
 Perform a DELETE-request against the Gogs API.

Ensure the correct scope i set first, with
```php
$this->set_scope("*valid scope*"); // e.g delete
```


Returns: string

---


##### get `Gogs\API\Request\Base`
```php
public function get(string $s);
```
 Get object references by identifier.


Parameters

| Type | Variable | Description              |
|---   |---       |---                       |
|string|$s        |The idientifier to look up|

Returns: object

---


##### create `Gogs\API\Request\Base`
```php
public function create(... $args);
```
 Create object inherited by class.

Child class must add a scope for 'create' and ensure child is not *loaded*,
otherwise will `create` throw an exception.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

Throws: 

* [InvalidMethodRequestException](#invalidmethodrequestexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\InvalidMethodRequestException")
* [NotImplementedException](#notimplementedexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\NotImplementedException")

---


##### patch `Gogs\API\Request\Base`
```php
public function patch();
```
 Patch (update) object


Returns: bool

Throws: 

* [InvalidMethodRequestException](#invalidmethodrequestexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\InvalidMethodRequestException")
* [NotImplementedException](#notimplementedexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\NotImplementedException")

---


##### delete `Gogs\API\Request\Base`
```php
public function delete();
```
 Delete object.


Returns: bool

Throws: 

* [NotImplementedException](#notimplementedexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\NotImplementedException")

---


##### json_decode `Gogs\API\Request\Base`
```php
final protected function json_decode(string $jenc);
```
 Decode JSON-string.

Will ensure that there weren't any errors by calling `$this->json_error`.


Parameters

| Type | Variable | Description       |
|---   |---       |---                |
|string|$jenc     |Encoded JSON string|

Returns: object

---


##### json_encode `Gogs\API\Request\Base`
```php
final protected function json_encode(iterable $jdec);
```
 Encode JSON-object/array.

Will ensure that there weren't any errors by calling `$this->json_error`.


Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$jdec     |JSON-data    |

Returns: string

---


##### json_error `Gogs\API\Request\Base`
```php
final protected function json_error();
```
 Check for errors on encoding/decoding.

Throws: 

* [RequestErrorException](#requesterrorexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\RequestErrorException")

---


##### json_set_property `Gogs\API\Request\Base`
```php
protected function json_set_property(iterable $obj);
```
 Set properties for the current object.

Each child class must implement this to set its data. Will
be called by methods such as `load` and from collection
classes.

Will return true/false for singel objects but an array on collections.
The array will contain the newly inserted elements. This to prevent
additional iterations.

This method should also set loaded to true or false, depending
on success or failure.


Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: [true](#true-gogsapirequest) ***v*** array

---


##### property_exists `Gogs\API\Request\Base`
```php
final protected function property_exists(mixed $name);
```
 Checks if the property (key) exists within self
or parent class.

Returns the actual key if it does. A class key (aka property)
start with the tag `classname_` followed by property name,
reflecting the JSON-object, and can be reached by

 * `$class->parameter`,
 * `$class->classname_parameter` or alternatively (for classes that inherits another class).
 * `$class->parentclassname_parameter`.

 If a class override a parent class with the same parameter,
 the class's own parameter will be favoured.

As this is public properties this wont be an security issue;


Parameters

| Type | Variable | Description    |
|---   |---       |---             |
|mixed|$name     |Name of the key.|

Returns: string ***v*** [false](#false-gogsapirequest)

---


##### __get `Gogs\API\Request\Base`
```php
final public function __get(string $name);
```
 Get property by name.

Checks both self and parent for the property.

Returns the value if property exists, otherwise an `E_USER_NOTICE`
is triggered.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$name     |*None*       |

Returns: mixed ***v*** null

---


##### __set `Gogs\API\Request\Base`
```php
final public function __set(string $name, mixed $value);
```
 Set property by name.

Checks both self and parent for the property.

Returns the value if property exists, otherwise an `E_USER_NOTICE`
is triggered.


Parameters

| Type | Variable | Description |
|---   |---       |---           |
|string|$name     |Property name|
|mixed|$value    |Property value|

Returns: mixed ***v*** null

---


##### __isset `Gogs\API\Request\Base`
```php
final public function __isset(string $name);
```
 Checks if property is set.

Checks both self and parent for property.

Triggers E_USER_NOTICE if property is unknown.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$name     |Property name|

Returns: bool

---


##### set_scope `Gogs\API\Request\Base`
```php
abstract protected function set_scope(string $method);
```
 Set the scope for the request methods accepted by the child.

This can be
 * `get`,
 * `search`,
 * `delete` etc.

 Must return true if scope exists of false otherwise. Methods
 the calls this will throw an exception if not true is returned.


Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### search `Gogs\API\Request\Base`
```php
protected function search(array $params = array(), bool $strict = false);
```
 Search for an matching object.

Methods do OR-ing and not AND-ing by default.

Params should be key (object property) and value that
this parameter should match, e.g

```
$repo->search(
 "name" => "this",
 "owner" => array(
     "username" => "that"
 )
);
```

will match `"this" IN $repo->name OR "that" IN $repo->owner->username` .


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [SearchParamException](#searchparamexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\SearchParamException")

---


### Branch `Gogs\API\Request`


* Class extends [Gogs\API\Request\Base](#base-gogsapirequest)
* Class implements[Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

A single Branch

#### Methods

|Name                                            |Return|Access   |Description                                   |
|:---                                            |:---  |:---     |:---                                          |
|[__construct](#__construct-gogsapirequestbranch)|      |public   | Initialize a branch for the given repository.|
|[set_scope](#set_scope-gogsapirequestbranch)    |bool  |protected|                                              |

#### Method details

##### __construct `Gogs\API\Request\Branch`
```php
public function __construct(string $api_url, string $api_token, Repo $repo);
```
 Initialize a branch for the given repository.


Parameters

| Type                   | Variable | Description                  |
|---                     |---       |---                           |
|string                  |$api_url  |The URL to the API.           |
|string                  |$api_token|A token for an authorized user|
|[Repo](#request-gogsapi)|$repo     |Related repository            |
---


##### set_scope `Gogs\API\Request\Branch`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


### Branches `Gogs\API\Request`


* Class extends [Gogs\API\Request\Collection](#collection-gogsapirequest)
* Class implements
	* [Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)
	* [Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Holds a collection of Branches for a Repository.

Supported:
 * GET `/repos/username/repo/branches`

#### Methods

|Name                                              |Return                           |Access   |Description                         |
|:---                                              |:---                             |:---     |:---                                |
|[__construct](#__construct-gogsapirequestbranches)|                                 |public   | Initialize Brances for a given repo|
|[set_scope](#set_scope-gogsapirequestbranches)    |bool                             |protected|                                    |
|[search](#search-gogsapirequestbranches)          |[true](#true-gogsapirequest)     |public   | Search for a branch.               |
|[sort_by](#sort_by-gogsapirequestbranches)        |[Collection](#collection-gogslib)|public   |                                    |
|[add_object](#add_object-gogsapirequestbranches)  |array                            |protected|                                    |

#### Method details

##### __construct `Gogs\API\Request\Branches`
```php
public function __construct(string $api_url, string $api_token, Repo $repo);
```
 Initialize Brances for a given repo


Parameters

| Type                   | Variable | Description                  |
|---                     |---       |---                           |
|string                  |$api_url  |The URL to the API.           |
|string                  |$api_token|A token for an authorized user|
|[Repo](#request-gogsapi)|$repo     |The repository                |
---


##### set_scope `Gogs\API\Request\Branches`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### search `Gogs\API\Request\Branches`
```php
public function search(array $params = array(), bool $strict = true);
```
 Search for a branch.

This method doesnt search by a uri, instead it will
load every branch from Gogs and do a match on this.

Params can be an array of
```php
$branches->search(array(
 "name"  => "name",      // alt. "q". required
 "limit" => 10,          // not required, default: 10
));
```
By now, this method can be intensive, as it will load
every branch and then do a match on each entry.


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [SearchParamException](#searchparamexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\SearchParamException")

---


##### sort_by `Gogs\API\Request\Branches`
```php
public function sort_by(int $flag = Collection::SORT_INDEX);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|int   |$flag     |Sorting flag|

Returns: [Collection](#collection-gogslib)

---


##### add_object `Gogs\API\Request\Branches`
```php
protected function add_object(iterable $obj);
```

Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: array

---


### Collection `Gogs\API\Request`


* Class extends [Gogs\API\Request\Base](#base-gogsapirequest)
* Class implements
	* [Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)
	* [Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Collection is a collection of data of one type.

#### Methods

|Name                                                            |Return                                    |Access            |Description                                             |
|:---                                                            |:---                                      |:---              |:---                                                    |
|[__construct](#__construct-gogsapirequestcollection)            |                                          |public            | Initialize a collection.                               |
|[add](#add-gogsapirequestcollection)                            |mixed ***v*** int                         |protected         | Add an object to the collection.                       |
|[remove](#remove-gogsapirequestcollection)                      |bool                                      |protected         | Remove an element in collection.                       |
|[copy](#copy-gogsapirequestcollection)                          |[Colletion](#colletion-gogslib)           |public            |                                                        |
|[all](#all-gogsapirequestcollection)                            |array                                     |public            |                                                        |
|[len](#len-gogsapirequestcollection)                            |int                                       |public            |                                                        |
|[by_key](#by_key-gogsapirequestcollection)                      |mixed                                     |public            |                                                        |
|[next](#next-gogsapirequestcollection)                          |mixed                                     |public            |                                                        |
|[prev](#prev-gogsapirequestcollection)                          |mixed                                     |public            |                                                        |
|[current](#current-gogsapirequestcollection)                    |                                          |public            |                                                        |
|[reset](#reset-gogsapirequestcollection)                        |mixed                                     |public            |                                                        |
|[sort](#sort-gogsapirequestcollection)                          |[Collection](#collection-gogslib)         |public            |                                                        |
|[filter](#filter-gogsapirequestcollection)                      |[Collection](#collection-gogslib)         |public            |                                                        |
|[limit](#limit-gogsapirequestcollection)                        |[Collection](#collection-gogslib)         |public            |                                                        |
|[offset](#offset-gogsapirequestcollection)                      |[Collection](#collection-gogslib)         |public            |                                                        |
|[reverse](#reverse-gogsapirequestcollection)                    |[Collection](#collection-gogslib)         |public            |                                                        |
|[json_set_property](#json_set_property-gogsapirequestcollection)|[true](#true-gogsapirequest) ***v*** array|protected         |                                                        |
|[search](#search-gogsapirequestcollection)                      |[true](#true-gogsapirequest)              |public            | Search an collection.                                  |
|[add_object](#add_object-gogsapirequestcollection)              |array                                     |abstract protected| Add an object to the collection with the specific type.|
|[sort_by](#sort_by-gogsapirequestcollection)                    |[Collection](#collection-gogslib)         |abstract public   | Sort the object                                        |

#### Method details

##### __construct `Gogs\API\Request\Collection`
```php
public function __construct(string $api_url, string $api_token, Collection $other = null);
```
 Initialize a collection.


Parameters

| Type                         | Variable | Description                  |
|---                           |---       |---                           |
|string                        |$api_url  |The URL to the API.           |
|string                        |$api_token|A token for an authorized user|
|[Collection](#request-gogsapi)|$other    |Collection to initialize from|
---


##### add `Gogs\API\Request\Collection`
```php
protected function add(mixed $obj, mixed $key = null);
```
 Add an object to the collection.

When adding a key the object will be stored
on the particual key, also overwriting existing data.


Parameters

| Type | Variable | Description         |
|---   |---       |---                  |
|mixed|$obj      |Element to store     |
|mixed|$key      |Index key to store on|

Returns: mixed ***v*** int

---


##### remove `Gogs\API\Request\Collection`
```php
protected function remove(mixed $any, bool $deep = true);
```
 Remove an element in collection.

The function will first look for the element as a
index key, but if its not found it will look for the
element as a value.

Deep functions only when the value is given and not the key.


Parameters

| Type | Variable | Description                            |
|---   |---       |---                                     |
|mixed|$any      |Index key or element value              |
|bool  |$deep     |Delete every item and not just the first|

Returns: bool

---


##### copy `Gogs\API\Request\Collection`
```php
public function copy();
```

Returns: [Colletion](#colletion-gogslib)

---


##### all `Gogs\API\Request\Collection`
```php
public function all();
```

Returns: array

---


##### len `Gogs\API\Request\Collection`
```php
public function len();
```

Returns: int

---


##### by_key `Gogs\API\Request\Collection`
```php
public function by_key(mixed $idx);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|mixed|$idx      |Index key.   |

Returns: mixed

---


##### next `Gogs\API\Request\Collection`
```php
public function next();
```

Returns: mixed

---


##### prev `Gogs\API\Request\Collection`
```php
public function prev();
```

Returns: mixed

---


##### current `Gogs\API\Request\Collection`
```php
public function current();
```
---


##### reset `Gogs\API\Request\Collection`
```php
public function reset();
```

Returns: mixed

---


##### sort `Gogs\API\Request\Collection`
```php
public function sort(callable $f);
```

Parameters

| Type   | Variable | Description |
|---     |---       |---          |
|callable|$f        |*None*       |

Returns: [Collection](#collection-gogslib)

---


##### filter `Gogs\API\Request\Collection`
```php
public function filter(callable $f);
```

Parameters

| Type   | Variable | Description |
|---     |---       |---          |
|callable|$f        |*None*       |

Returns: [Collection](#collection-gogslib)

---


##### limit `Gogs\API\Request\Collection`
```php
public function limit(int $lim);
```

Parameters

| Type | Variable | Description            |
|---   |---       |---                     |
|int   |$lim      |Maximum entries returned|

Returns: [Collection](#collection-gogslib)

---


##### offset `Gogs\API\Request\Collection`
```php
public function offset(int $off);
```

Parameters

| Type | Variable | Description             |
|---   |---       |---                      |
|int   |$off      |Offset from in collection|

Returns: [Collection](#collection-gogslib)

---


##### reverse `Gogs\API\Request\Collection`
```php
public function reverse();
```

Returns: [Collection](#collection-gogslib)

---


##### json_set_property `Gogs\API\Request\Collection`
```php
protected function json_set_property(iterable $obj);
```

Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: [true](#true-gogsapirequest) ***v*** array

---


##### search `Gogs\API\Request\Collection`
```php
public function search(array $params = array(), bool $strict = false);
```
 Search an collection.


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [NotImplementedException](#notimplementedexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\NotImplementedException")

---


##### add_object `Gogs\API\Request\Collection`
```php
abstract protected function add_object(iterable $object);
```
 Add an object to the collection with the specific type.

Typically it will create an instance of the type that
the collection will consist of.

Should call json set property


Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$object   |*None*       |

Returns: array

---


##### sort_by `Gogs\API\Request\Collection`
```php
abstract public function sort_by(int $flag = \Gogs\Lib\ArrayIterator::SORT_INDEX);
```
 Sort the object

Should call sort on parent with the specified sort method,
given by $flag


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|int   |$flag     |Sorting flag|

Returns: [Collection](#collection-gogslib)

---


### Org `Gogs\API\Request`


* Class extends [Gogs\API\Request\User](#user-gogsapirequest)
* Class implements[Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Stores data and methods related to a single organization.

By now the following are supported:

 * GET `/orgs/username`
 * POST `/admin/users/username/orgs` (**Requires** admin rights. Curl will throw NotAuthorized exception if not).

#### Methods

|Name                                         |Return|Access   |Description                 |
|:---                                         |:---  |:---     |:---                        |
|[__construct](#__construct-gogsapirequestorg)|      |public   | Initialize an organization.|
|[set_scope](#set_scope-gogsapirequestorg)    |bool  |protected|                            |
|[create](#create-gogsapirequestorg)          |bool  |public   | Create a new user          |

#### Method details

##### __construct `Gogs\API\Request\Org`
```php
public function __construct(string $api_url, string $api_token, User $owner = null, string $oname = null);
```
 Initialize an organization.


Parameters

| Type                   | Variable | Description                  |
|---                     |---       |---                           |
|string                  |$api_url  |The URL to the API.           |
|string                  |$api_token|A token for an authorized user|
|[User](#request-gogsapi)|$owner    |The owner of the organization|
|string                  |$oname    |Organization name             |
---


##### set_scope `Gogs\API\Request\Org`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

Throws: 

* [InvalidMethodRequestException](#invalidmethodrequestexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\InvalidMethodRequestException")
* [RequestErrorException](#requesterrorexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\RequestErrorException")

---


##### create `Gogs\API\Request\Org`
```php
public function create(... $args);
```
 Create a new user

Valid parameters:

 1. username
 2. full_name
 3. description
 4. website
 5. location

 This reflects the API v1 doc, but is in an order
 where the required fields is first.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


### Orgs `Gogs\API\Request`


* Class extends [Gogs\API\Request\Collection](#collection-gogsapirequest)
* Class implements
	* [Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)
	* [Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Orgs is a collection of organizations.

#### Methods

|Name                                          |Return                           |Access   |Description                                     |
|:---                                          |:---                             |:---     |:---                                            |
|[__construct](#__construct-gogsapirequestorgs)|                                 |public   | Initialize an organization collection for user.|
|[set_scope](#set_scope-gogsapirequestorgs)    |bool                             |protected|                                                |
|[create](#create-gogsapirequestorgs)          |bool                             |public   | Create a new organization                      |
|[get](#get-gogsapirequestorgs)                |object                           |public   | Get an organization by indentifier.            |
|[search](#search-gogsapirequestorgs)          |[true](#true-gogsapirequest)     |public   | Search for an organization.                    |
|[add_object](#add_object-gogsapirequestorgs)  |array                            |protected|                                                |
|[sort_by](#sort_by-gogsapirequestorgs)        |[Collection](#collection-gogslib)|public   |                                                |

#### Method details

##### __construct `Gogs\API\Request\Orgs`
```php
public function __construct(string $api_url, string $api_token, User $owner);
```
 Initialize an organization collection for user.


Parameters

| Type                   | Variable | Description                  |
|---                     |---       |---                           |
|string                  |$api_url  |The URL to the API.           |
|string                  |$api_token|A token for an authorized user|
|[User](#request-gogsapi)|$owner    |The user                      |
---


##### set_scope `Gogs\API\Request\Orgs`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### create `Gogs\API\Request\Orgs`
```php
public function create(... $args);
```
 Create a new organization

If arguments are given, the User will be created,
otherise it will return an initialized object,
leaving the programmer to create the user.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### get `Gogs\API\Request\Orgs`
```php
public function get(string $s);
```
 Get an organization by indentifier.

Method will first look through organizations
already loaded. If not found it will return a
new object.

Method does not ensure the organization in loaded
from Gogs so the user should call `->load()` on
returned element.


Parameters

| Type | Variable | Description              |
|---   |---       |---                       |
|string|$s        |The idientifier to look up|

Returns: object

---


##### search `Gogs\API\Request\Orgs`
```php
public function search(array $params = array(), bool $strict = false);
```
 Search for an organization.

Params can be an array of
```php
$orgs->search(array(
 "name"  => "name",      // alt. "q". required
 "limit" => 10,          // not required, default: 10
));
```
By now, this method can be intensive, as it will load
every organization and then do a match on each entry.


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [SearchParamException](#searchparamexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\SearchParamException")

---


##### add_object `Gogs\API\Request\Orgs`
```php
protected function add_object(iterable $obj);
```

Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: array

---


##### sort_by `Gogs\API\Request\Orgs`
```php
public function sort_by(int $flag = Collection::SORT_INDEX);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|int   |$flag     |Sorting flag|

Returns: [Collection](#collection-gogslib)

---


### Repo `Gogs\API\Request`


* Class extends [Gogs\API\Request\Base](#base-gogsapirequest)
* Class implements[Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Stores data and methods related to a single repository.

By now the following are supported:

 * GET `/repos/username/reponame`
 * POST `/user/repos`
 * POST `/admin/user/username/repos`
 * POST `/org/orgname/repos`
 * DELETE `/repos/username/reponame`

#### Methods

|Name                                                      |Return                                    |Access   |Description                                            |
|:---                                                      |:---                                      |:---     |:---                                                   |
|[__construct](#__construct-gogsapirequestrepo)            |                                          |public   | Initialize a repo object.                             |
|[set_scope](#set_scope-gogsapirequestrepo)                |bool                                      |protected|                                                       |
|[branches](#branches-gogsapirequestrepo)                  |[Branches](#branches-gogsapirequest)      |public   | Return branches for repository.                       |
|[json_set_property](#json_set_property-gogsapirequestrepo)|[true](#true-gogsapirequest) ***v*** array|protected| Overrides Base method as this should set owner as well|
|[create](#create-gogsapirequestrepo)                      |bool                                      |public   | Create a new repo                                     |
|[migrate](#migrate-gogsapirequestrepo)                    |                                          |public   | Migrate a repository from other Git hosting sources.  |
|[sync](#sync-gogsapirequestrepo)                          |bool                                      |public   | Add repo to sync queue.                               |

#### Method details

##### __construct `Gogs\API\Request\Repo`
```php
public function __construct(string $api_url, string $api_token, User $owner = null, string $name = null);
```
 Initialize a repo object.

Note that the owner can also be an Org (organization),
or any other class that inherits a user.


Parameters

| Type                   | Variable | Description                  |
|---                     |---       |---                           |
|string                  |$api_url  |The URL to the API.           |
|string                  |$api_token|A token for an authorized user|
|[User](#request-gogsapi)|$owner    |The owner of the repo         |
|string                  |$name     |The repo name                 |
---


##### set_scope `Gogs\API\Request\Repo`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

Throws: 

* [RequestErrorException](#requesterrorexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\RequestErrorException")

---


##### branches `Gogs\API\Request\Repo`
```php
public function branches();
```
 Return branches for repository.


Returns: [Branches](#branches-gogsapirequest)

---


##### json_set_property `Gogs\API\Request\Repo`
```php
protected function json_set_property(iterable $obj);
```
 Overrides Base method as this should set owner as well


Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: [true](#true-gogsapirequest) ***v*** array

---


##### create `Gogs\API\Request\Repo`
```php
public function create(... $args);
```
 Create a new repo

Valid paramters:

 1. name, required
 2. description
 3. private (default: false)
 4. auto_init (default: false)
 5. gitignore
 6. license
 7. readme (default: "Default")

  This reflects the API v1 documentation, but is in an order
  where the required fields are first.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### migrate `Gogs\API\Request\Repo`
```php
public function migrate(mixed $args);
```
 Migrate a repository from other Git hosting sources.

Valid parameters:

 1. clone_addr, required
 3. repo_name, required
 4. auth_username
 5. auth_password
 6. mirror (default: false)
 7. private (default: false)
 8. description

 **UID** will be set to `owner`. Either a User or an Organization.
 **From API doc**: To migrate a repository for a organization,
 the authenticated user must be a owner of the specified organization.

 This reflects the API v1 documentation, but is in an order
 where the required fields as first.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|mixed|$args     |*None*       |
Throws: 

* [RequestErrorException](#requesterrorexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\RequestErrorException")

---


##### sync `Gogs\API\Request\Repo`
```php
public function sync();
```
 Add repo to sync queue.

Requires the repository to be a mirror.


Returns: bool

---


### Repos `Gogs\API\Request`


* Class extends [Gogs\API\Request\Collection](#collection-gogsapirequest)
* Class implements
	* [Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)
	* [Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Repos is a collection of repos.

#### Methods

|Name                                                                 |Return                           |Access   |Description                       |
|:---                                                                 |:---                             |:---     |:---                              |
|[__construct](#__construct-gogsapirequestrepos)                      |                                 |public   | Initialize a repos collection    |
|[set_scope](#set_scope-gogsapirequestrepos)                          |bool                             |protected|                                  |
|[get](#get-gogsapirequestrepos)                                      |object                           |public   | Get a single repository by name.|
|[create](#create-gogsapirequestrepos)                                |bool                             |public   |                                  |
|[search](#search-gogsapirequestrepos)                                |[true](#true-gogsapirequest)     |public   | Searches for a repo.             |
|[sort_by](#sort_by-gogsapirequestrepos)                              |[Collection](#collection-gogslib)|public   | Sort repos by `method`.          |
|[add_object](#add_object-gogsapirequestrepos)                        |array                            |protected|                                  |
|[privates](#privates-gogsapirequestrepos)                            |[Collection](#collection-gogslib)|public   | Get private repositories         |
|[publics](#publics-gogsapirequestrepos)                              |[Collection](#collection-gogslib)|public   | Get public repositories          |
|[personals](#personals-gogsapirequestrepos)                          |[Collection](#collection-gogslib)|public   | Get personal repositories        |
|[contributions](#contributions-gogsapirequestrepos)                  |[Collection](#collection-gogslib)|public   | Get repositories contributed to  |
|[personals_privates](#personals_privates-gogsapirequestrepos)        |[Collection](#collection-gogslib)|public   | Get personal private repositories|
|[personals_publics](#personals_publics-gogsapirequestrepos)          |[Collection](#collection-gogslib)|public   | Get personal public repositories|
|[contributions_privates](#contributions_privates-gogsapirequestrepos)|[Collection](#collection-gogslib)|public   | Get private contributions        |
|[contributions_publics](#contributions_publics-gogsapirequestrepos)  |[Collection](#collection-gogslib)|public   | Get public contributions         |

#### Method details

##### __construct `Gogs\API\Request\Repos`
```php
public function __construct(string $api_url, string $api_token, User $owner = null);
```
 Initialize a repos collection

If owner is not set it will query the whole
repo archive on Gogs.


Parameters

| Type                   | Variable | Description                  |
|---                     |---       |---                           |
|string                  |$api_url  |The URL to the API.           |
|string                  |$api_token|A token for an authorized user|
|[User](#request-gogsapi)|$owner    |The owner of the collection   |
---


##### set_scope `Gogs\API\Request\Repos`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### get `Gogs\API\Request\Repos`
```php
public function get(string $name);
```
 Get a single repository by name.

If the `owner` is set, the name can be just the
actual name of the repo


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$name     |*None*       |

Returns: object

---


##### create `Gogs\API\Request\Repos`
```php
public function create(... $args);
```

Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### search `Gogs\API\Request\Repos`
```php
public function search(array $params = array(), bool $strict = false);
```
 Searches for a repo.

If the owner is specified the search will be
limited to the actual user.

Params can be an array of
```php
$repos->search(array(
 "name"  => "name",      // alt. "q". required
 "limit" => 10,          // not required, default: 10
));
```

If repositories is allready loaded it will do a match
on the existing collection.


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [SearchParamException](#searchparamexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\SearchParamException")

---


##### sort_by `Gogs\API\Request\Repos`
```php
public function sort_by(int $flag = Collection::SORT_INDEX, bool $asc = false);
```
 Sort repos by `method`.

Valid methods:

 * SORT_UPDATED: Sort on `updated_at` value
 * SORT_CREATED: Sort on `created_at` value
 * SORT_OWNER: Sort on `owner` (organization repos etc may appear)


Parameters

| Type | Variable | Description   |
|---   |---       |---            |
|int   |$flag     |Sorting flag   |
|bool  |$asc      |Ascending order|

Returns: [Collection](#collection-gogslib)

---


##### add_object `Gogs\API\Request\Repos`
```php
protected function add_object(iterable $obj);
```

Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: array

---


##### privates `Gogs\API\Request\Repos`
```php
public function privates();
```
 Get private repositories


Returns: [Collection](#collection-gogslib)

---


##### publics `Gogs\API\Request\Repos`
```php
public function publics();
```
 Get public repositories


Returns: [Collection](#collection-gogslib)

---


##### personals `Gogs\API\Request\Repos`
```php
public function personals();
```
 Get personal repositories


Returns: [Collection](#collection-gogslib)

---


##### contributions `Gogs\API\Request\Repos`
```php
public function contributions();
```
 Get repositories contributed to


Returns: [Collection](#collection-gogslib)

---


##### personals_privates `Gogs\API\Request\Repos`
```php
public function personals_privates();
```
 Get personal private repositories


Returns: [Collection](#collection-gogslib)

---


##### personals_publics `Gogs\API\Request\Repos`
```php
public function personals_publics();
```
 Get personal public repositories


Returns: [Collection](#collection-gogslib)

---


##### contributions_privates `Gogs\API\Request\Repos`
```php
public function contributions_privates();
```
 Get private contributions


Returns: [Collection](#collection-gogslib)

---


##### contributions_publics `Gogs\API\Request\Repos`
```php
public function contributions_publics();
```
 Get public contributions


Returns: [Collection](#collection-gogslib)

---


### Token `Gogs\API\Request`


* Class extends [Gogs\API\Request\Base](#base-gogsapirequest)
* Class implements[Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

A token related to a user

Supports:
 * POST `/users/{username}/tokens`

Note! Tokens doesnt have a "GET" method. @see Tokens
as this can load them.

#### Methods

|Name                                           |Return|Access   |Description         |
|:---                                           |:---  |:---     |:---                |
|[__construct](#__construct-gogsapirequesttoken)|      |public   | Initializes a token|
|[set_scope](#set_scope-gogsapirequesttoken)    |bool  |protected|                    |
|[create](#create-gogsapirequesttoken)          |bool  |public   | Create a new token|

#### Method details

##### __construct `Gogs\API\Request\Token`
```php
public function __construct(string $api_url, string $password, User $user);
```
 Initializes a token


Parameters

| Type                   | Variable | Description               |
|---                     |---       |---                        |
|string                  |$api_url  |The URL to the API.        |
|string                  |$password|The users personal password|
|[User](#request-gogsapi)|$user     |*None*                     |
---


##### set_scope `Gogs\API\Request\Token`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### create `Gogs\API\Request\Token`
```php
public function create(... $args);
```
 Create a new token

Valid parameters:

 1. name

 This reflects the API v1 documentation.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


### Tokens `Gogs\API\Request`


* Class extends [Gogs\API\Request\Collection](#collection-gogsapirequest)
* Class implements
	* [Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)
	* [Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Collection of tokens for a given user.

Supports:
 * GET `/users/{username}/tokens`

#### Methods

|Name                                            |Return                           |Access   |Description                    |
|:---                                            |:---                             |:---     |:---                           |
|[__construct](#__construct-gogsapirequesttokens)|                                 |public   | Initialize a token collection.|
|[set_scope](#set_scope-gogsapirequesttokens)    |bool                             |protected|                               |
|[create](#create-gogsapirequesttokens)          |bool                             |public   | Create a new token.           |
|[add_object](#add_object-gogsapirequesttokens)  |array                            |protected|                               |
|[get](#get-gogsapirequesttokens)                |object                           |public   | Return a token by name        |
|[search](#search-gogsapirequesttokens)          |[true](#true-gogsapirequest)     |public   | Search for a token.           |
|[sort_by](#sort_by-gogsapirequesttokens)        |[Collection](#collection-gogslib)|public   |                               |

#### Method details

##### __construct `Gogs\API\Request\Tokens`
```php
public function __construct(string $api_url, string $password, User $user);
```
 Initialize a token collection.


Parameters

| Type                   | Variable | Description            |
|---                     |---       |---                     |
|string                  |$api_url  |The URL to the API.     |
|string                  |$password|User's personal password|
|[User](#request-gogsapi)|$user     |Owner of tokens         |
---


##### set_scope `Gogs\API\Request\Tokens`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### create `Gogs\API\Request\Tokens`
```php
public function create(... $args);
```
 Create a new token.

Returns a new token object. If arguments is specified the "token"
will be created.

Arguments can be left empty to "create" the token, leaving
the programmer to call create on the token object with the arguments
itself, to create it.

Creating the token through this function will store the function in
the collection. If not created, it wont be added, and collection
must be reloaded (`->load(true)`)  to add it.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### add_object `Gogs\API\Request\Tokens`
```php
protected function add_object(iterable $obj);
```

Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: array

---


##### get `Gogs\API\Request\Tokens`
```php
public function get(string $s);
```
 Return a token by name


Parameters

| Type | Variable | Description              |
|---   |---       |---                       |
|string|$s        |The idientifier to look up|

Returns: object

---


##### search `Gogs\API\Request\Tokens`
```php
public function search(array $params = array(), bool $strict = false);
```
 Search for a token.

Params can be an array of
```php
$orgs->search(array(
 "name"  => "name",      // alt. "q". required
 "limit" => 10,          // not required, default: 10
));
```
By now, this method can be intensive, as it will load
every token and then do a match on each entry.


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [SearchParamException](#searchparamexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\SearchParamException")

---


##### sort_by `Gogs\API\Request\Tokens`
```php
public function sort_by(int $flag = Collection::SORT_INDEX);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|int   |$flag     |Sorting flag|

Returns: [Collection](#collection-gogslib)

---


### User `Gogs\API\Request`


* Class extends [Gogs\API\Request\Base](#base-gogsapirequest)
* Class implements[Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Stores user data and methods related to a single user.

By now the following are supported:

 * GET `/user`
 * GET `/users/username`
 * POST `/admin/users` (**Requires** admin rights. Curl will throw NotAuthorized exception if not).
 * DELETE `/admin/users` (**Requires** admin rights. Curl will throw NotAuthorized exception if not).

A user can also list it's repos and organizations.

#### Methods

|Name                                              |Return                          |Access   |Description                                    |
|:---                                              |:---                            |:---     |:---                                           |
|[__construct](#__construct-gogsapirequestuser)    |                                |public   | Initialize an user object.                    |
|[set_scope](#set_scope-gogsapirequestuser)        |bool                            |protected|                                               |
|[authenticated](#authenticated-gogsapirequestuser)|bool                            |public   | Returns if the user is the authenticated user.|
|[repos](#repos-gogsapirequestuser)                |[Repos](#repos-gogsapirequest)  |public   | Returns every repo under user.                |
|[repo](#repo-gogsapirequestuser)                  |[Repo](#repo-gogsapirequest)    |public   | Return a single repo.                         |
|[organizations](#organizations-gogsapirequestuser)|[Orgs](#orgs-gogsapirequest)    |public   | Return every organization under user.         |
|[orgs](#orgs-gogsapirequestuser)                  |                                |public   |                                               |
|[organization](#organization-gogsapirequestuser)  |[Org](#org-gogsapirequest)      |public   | Return a single organization.                 |
|[org](#org-gogsapirequestuser)                    |                                |public   |                                               |
|[create](#create-gogsapirequestuser)              |bool                            |public   | Create a new user.                            |
|[tokens](#tokens-gogsapirequestuser)              |[Tokens](#tokens-gogsapirequest)|public   | Returns user tokens                           |

#### Method details

##### __construct `Gogs\API\Request\User`
```php
public function __construct(string $api_url, string $api_token, string $user = "");
```
 Initialize an user object.


Parameters

| Type | Variable | Description                                                |
|---   |---       |---                                                         |
|string|$api_url  |The URL to the API.                                         |
|string|$api_token|A token for an authorized user                              |
|string|$user     |The username. "Empty" or "me" will return authenticated user|
---


##### set_scope `Gogs\API\Request\User`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

Throws: 

* [InvalidMethodRequest](#invalidmethodrequest-gogsapirequestexception "Exception: Gogs\API\Request\Exception\InvalidMethodRequest")
* [RequestErrorException](#requesterrorexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\RequestErrorException")

---


##### authenticated `Gogs\API\Request\User`
```php
public function authenticated();
```
 Returns if the user is the authenticated user.


Returns: bool

---


##### repos `Gogs\API\Request\User`
```php
public function repos();
```
 Returns every repo under user.


Returns: [Repos](#repos-gogsapirequest)

---


##### repo `Gogs\API\Request\User`
```php
public function repo(string $name);
```
 Return a single repo.

Note: This will also load the repo.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$name     |Repo name    |

Returns: [Repo](#repo-gogsapirequest)

---


##### organizations `Gogs\API\Request\User`
```php
public function organizations();
```
 Return every organization under user.


Returns: [Orgs](#orgs-gogsapirequest)

---


##### orgs `Gogs\API\Request\User`
```php
public function orgs();
```
---


##### organization `Gogs\API\Request\User`
```php
public function organization(string $name);
```
 Return a single organization.

Note: This will also load the repo.


Parameters

| Type | Variable | Description     |
|---   |---       |---              |
|string|$name     |Organization name|

Returns: [Org](#org-gogsapirequest)

---


##### org `Gogs\API\Request\User`
```php
public function org(string $name);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$name     |*None*       |
---


##### create `Gogs\API\Request\User`
```php
public function create(... $args);
```
 Create a new user.

Valid parameters

 1. username
 2. email
 3. source_id
 4. login_name
 5. password
 6. send_notify

This reflects the API v1 documentation, but is in an order
where the required fields are first.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### tokens `Gogs\API\Request\User`
```php
public function tokens(string $password);
```
 Returns user tokens


Parameters

| Type | Variable | Description           |
|---   |---       |---                    |
|string|$password|User personal password.|

Returns: [Tokens](#tokens-gogsapirequest)

---


### Users `Gogs\API\Request`


* Class extends [Gogs\API\Request\Collection](#collection-gogsapirequest)
* Class implements
	* [Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)
	* [Gogs\API\Request\RequestInterface](#requestinterface-gogsapirequest)

Returns one or more users in the Gogs installation,
depending on the called method.

#### Methods

|Name                                         |Return                           |Access   |Description                                                                     |
|:---                                         |:---                             |:---     |:---                                                                            |
|[set_scope](#set_scope-gogsapirequestusers)  |bool                             |protected|                                                                                |
|[create](#create-gogsapirequestusers)        |bool                             |public   | Returns a new user object. If argumentsis specified the user will be "created".|
|[get](#get-gogsapirequestusers)              |object                           |public   | Return a user by username.                                                     |
|[search](#search-gogsapirequestusers)        |[true](#true-gogsapirequest)     |public   | Search for an user                                                             |
|[sort_by](#sort_by-gogsapirequestusers)      |[Collection](#collection-gogslib)|public   |                                                                                |
|[add_object](#add_object-gogsapirequestusers)|array                            |protected|                                                                                |

#### Method details

##### set_scope `Gogs\API\Request\Users`
```php
protected function set_scope(string $method);
```

Parameters

| Type | Variable | Description          |
|---   |---       |---                   |
|string|$method   |Method type, e.g "get"|

Returns: bool

---


##### create `Gogs\API\Request\Users`
```php
public function create(... $args);
```
 Returns a new user object. If arguments
is specified the user will be "created".

The arguments can be left out to "create" the
user through the user object iteself.


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### get `Gogs\API\Request\Users`
```php
public function get(string $s);
```
 Return a user by username.


Parameters

| Type | Variable | Description              |
|---   |---       |---                       |
|string|$s        |The idientifier to look up|

Returns: object

---


##### search `Gogs\API\Request\Users`
```php
public function search(array $params = array(), bool $strict = false);
```
 Search for an user

Params can be an array of
```php
$orgs->search(array(
 "name"  => "name",      // alt. "q". required
 "limit" => 10,          // not required, default: 10
));
```
By now, this method can be intensive, as it will load
every organization and then do a match on each entry.


Parameters

| Type | Variable | Description                                          |
|---   |---       |---                                                   |
|array|$params   |Parameters                                            |
|bool  |$strict   |Turn search into AND-ing, require match in each field.|

Returns: [true](#true-gogsapirequest)

Throws: 

* [SearchParamException](#searchparamexception-gogsapirequestexception "Exception: Gogs\API\Request\Exception\SearchParamException")

---


##### sort_by `Gogs\API\Request\Users`
```php
public function sort_by(int $flag = Collection::SORT_INDEX);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|int   |$flag     |Sorting flag|

Returns: [Collection](#collection-gogslib)

---


##### add_object `Gogs\API\Request\Users`
```php
protected function add_object(iterable $obj);
```

Parameters

| Type                                                                                                  | Variable | Description |
|---                                                                                                    |---       |---          |
|[iterable](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.types)|$obj      |*None*       |

Returns: array

---

## Interfaces

### RequestInterface `Gogs\API\Request`

Request interface, used by any kind of request object.

#### Methods

|Name                                            |Return|Access|Description           |
|:---                                            |:---  |:---  |:---                  |
|[load](#load-gogsapirequestrequestinterface)    |object|public| Load object.         |
|[get](#get-gogsapirequestrequestinterface)      |object|public| Get by identifier    |
|[create](#create-gogsapirequestrequestinterface)|bool  |public| Create object        |
|[patch](#patch-gogsapirequestrequestinterface)  |bool  |public| Patch (update) object|
|[delete](#delete-gogsapirequestrequestinterface)|bool  |public| Delete object        |

#### Method details

##### load `Gogs\API\Request\RequestInterface`
```php
public function load(bool $force = false);
```
 Load object.


Parameters

| Type | Variable | Description               |
|---   |---       |---                        |
|bool  |$force    |Force update, default: true|

Returns: object

---


##### get `Gogs\API\Request\RequestInterface`
```php
public function get(string $s);
```
 Get by identifier


Parameters

| Type | Variable | Description              |
|---   |---       |---                       |
|string|$s        |The idientifier to look up|

Returns: object

---


##### create `Gogs\API\Request\RequestInterface`
```php
public function create(... $args);
```
 Create object


Parameters

| Type                                                                              | Variable | Description                 |
|---                                                                                |---       |---                          |
|[...](http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list)|$args     |Arguments required by create.|

Returns: bool

---


##### patch `Gogs\API\Request\RequestInterface`
```php
public function patch();
```
 Patch (update) object


Returns: bool

---


##### delete `Gogs\API\Request\RequestInterface`
```php
public function delete();
```
 Delete object


Returns: bool

---

# Gogs\API\Request\Exception

## Exceptions

### InvalidMethodRequestException `Gogs\API\Request\Exception`


* Class extends [Exception](https://www.google.no/search?q=Exception)

Thrown whenever a class that inherits the base-class
is used wrong (e.g tries to create on a loaded object)


### NotImplementedException `Gogs\API\Request\Exception`


* Class extends [BadMethodCallException](https://www.google.no/search?q=BadMethodCallException)

Thrown when the requested method for a class isn't
implemented.


### RequestErrorException `Gogs\API\Request\Exception`


* Class extends [Exception](https://www.google.no/search?q=Exception)

Typically thrown when needed data to build the query
is missing.


### SearchParamException `Gogs\API\Request\Exception`


* Class extends [Exception](https://www.google.no/search?q=Exception)

Thrown when needed parameters for a search is missing.

# Gogs\Lib

## Classes

### Collection `Gogs\Lib`


* Class implements[Gogs\Lib\ArrayIterator](#arrayiterator-gogslib)

Base class for collections. Implements basic
functions and typically used to return collections
which wont be a part of the "request package"

#### Methods

|Name                                         |Return                           |Access|Description                      |
|:---                                         |:---                             |:---  |:---                             |
|[__construct](#__construct-gogslibcollection)|                                 |public|                                 |
|[set](#set-gogslibcollection)                |                                 |public| Set value(e) to the collection.|
|[by_key](#by_key-gogslibcollection)          |mixed                            |public|                                 |
|[copy](#copy-gogslibcollection)              |[Colletion](#colletion-gogslib)  |public| Copy collection                 |
|[all](#all-gogslibcollection)                |array                            |public|                                 |
|[len](#len-gogslibcollection)                |int                              |public|                                 |
|[next](#next-gogslibcollection)              |mixed                            |public|                                 |
|[prev](#prev-gogslibcollection)              |mixed                            |public|                                 |
|[current](#current-gogslibcollection)        |                                 |public|                                 |
|[reset](#reset-gogslibcollection)            |mixed                            |public|                                 |
|[sort](#sort-gogslibcollection)              |[Collection](#collection-gogslib)|public|                                 |
|[filter](#filter-gogslibcollection)          |[Collection](#collection-gogslib)|public| Filter collection               |
|[limit](#limit-gogslibcollection)            |[Collection](#collection-gogslib)|public|                                 |
|[offset](#offset-gogslibcollection)          |[Collection](#collection-gogslib)|public|                                 |
|[reverse](#reverse-gogslibcollection)        |[Collection](#collection-gogslib)|public|                                 |
|[remove](#remove-gogslibcollection)          |bool                             |public| Remove an element in collection.|

#### Method details

##### __construct `Gogs\Lib\Collection`
```php
public function __construct(array $arr = array());
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|array|$arr      |*None*       |
---


##### set `Gogs\Lib\Collection`
```php
public function set(mixed $val, mixed $key = null);
```
 Set value(e) to the collection.

If the value is an array it will overwrite
the whole object-array, aka everything.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|mixed|$val      |*None*       |
|mixed|$key      |*None*       |
---


##### by_key `Gogs\Lib\Collection`
```php
public function by_key(mixed $idx);
```

Parameters

| Type | Variable | Description |
|---   |---       |---          |
|mixed|$idx      |Index key.   |

Returns: mixed

---


##### copy `Gogs\Lib\Collection`
```php
public function copy();
```
 Copy collection


Returns: [Colletion](#colletion-gogslib)

---


##### all `Gogs\Lib\Collection`
```php
public function all();
```

Returns: array

---


##### len `Gogs\Lib\Collection`
```php
public function len();
```

Returns: int

---


##### next `Gogs\Lib\Collection`
```php
public function next();
```

Returns: mixed

---


##### prev `Gogs\Lib\Collection`
```php
public function prev();
```

Returns: mixed

---


##### current `Gogs\Lib\Collection`
```php
public function current();
```
---


##### reset `Gogs\Lib\Collection`
```php
public function reset();
```

Returns: mixed

---


##### sort `Gogs\Lib\Collection`
```php
public function sort(callable $f);
```

Parameters

| Type   | Variable | Description |
|---     |---       |---          |
|callable|$f        |*None*       |

Returns: [Collection](#collection-gogslib)

---


##### filter `Gogs\Lib\Collection`
```php
public function filter(callable $f);
```
 Filter collection


Parameters

| Type   | Variable | Description |
|---     |---       |---          |
|callable|$f        |*None*       |

Returns: [Collection](#collection-gogslib)

---


##### limit `Gogs\Lib\Collection`
```php
public function limit(int $lim);
```

Parameters

| Type | Variable | Description            |
|---   |---       |---                     |
|int   |$lim      |Maximum entries returned|

Returns: [Collection](#collection-gogslib)

---


##### offset `Gogs\Lib\Collection`
```php
public function offset(int $off);
```

Parameters

| Type | Variable | Description             |
|---   |---       |---                      |
|int   |$off      |Offset from in collection|

Returns: [Collection](#collection-gogslib)

---


##### reverse `Gogs\Lib\Collection`
```php
public function reverse();
```

Returns: [Collection](#collection-gogslib)

---


##### remove `Gogs\Lib\Collection`
```php
public function remove(mixed $any, bool $deep = true);
```
 Remove an element in collection.

The function will first look for the element as a
index key, but if its not found it will look for the
element as a value.

Deep functions only when the value is given and not the key.


Parameters

| Type | Variable | Description                            |
|---   |---       |---                                     |
|mixed|$any      |Index key or element value              |
|bool  |$deep     |Delete every item and not just the first|

Returns: bool

---

## Interfaces

### ArrayIterator `Gogs\Lib`

Interface to store one or more elements in array
providing an iterator interface.

#### Methods

|Name                                    |Return                           |Access|Description                            |
|:---                                    |:---                             |:---  |:---                                   |
|[current](#current-gogslibarrayiterator)|                                 |public| Get current element in collection.    |
|[next](#next-gogslibarrayiterator)      |mixed                            |public| Get next element in collection.       |
|[prev](#prev-gogslibarrayiterator)      |mixed                            |public| Return previous element in collection.|
|[reset](#reset-gogslibarrayiterator)    |mixed                            |public| Reset collection (set array to head).|
|[len](#len-gogslibarrayiterator)        |int                              |public| Return collection size.               |
|[all](#all-gogslibarrayiterator)        |array                            |public| Return the whole colection.           |
|[by_key](#by_key-gogslibarrayiterator)  |mixed                            |public| Get element by index key.             |
|[copy](#copy-gogslibarrayiterator)      |[Colletion](#colletion-gogslib)  |public| Copy collection                       |
|[limit](#limit-gogslibarrayiterator)    |[Collection](#collection-gogslib)|public| Limit until in collection             |
|[offset](#offset-gogslibarrayiterator)  |[Collection](#collection-gogslib)|public| Get from offset collection            |
|[reverse](#reverse-gogslibarrayiterator)|[Collection](#collection-gogslib)|public| Reverse the collection                |
|[sort](#sort-gogslibarrayiterator)      |[Collection](#collection-gogslib)|public| Sort collection                       |
|[filter](#filter-gogslibarrayiterator)  |[Collection](#collection-gogslib)|public| Filter collection                     |

#### Method details

##### current `Gogs\Lib\ArrayIterator`
```php
public function current();
```
 Get current element in collection.

---


##### next `Gogs\Lib\ArrayIterator`
```php
public function next();
```
 Get next element in collection.


Returns: mixed

---


##### prev `Gogs\Lib\ArrayIterator`
```php
public function prev();
```
 Return previous element in collection.


Returns: mixed

---


##### reset `Gogs\Lib\ArrayIterator`
```php
public function reset();
```
 Reset collection (set array to head).


Returns: mixed

---


##### len `Gogs\Lib\ArrayIterator`
```php
public function len();
```
 Return collection size.


Returns: int

---


##### all `Gogs\Lib\ArrayIterator`
```php
public function all();
```
 Return the whole colection.


Returns: array

---


##### by_key `Gogs\Lib\ArrayIterator`
```php
public function by_key(mixed $idx);
```
 Get element by index key.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|mixed|$idx      |Index key.   |

Returns: mixed

---


##### copy `Gogs\Lib\ArrayIterator`
```php
public function copy();
```
 Copy collection


Returns: [Colletion](#colletion-gogslib)

---


##### limit `Gogs\Lib\ArrayIterator`
```php
public function limit(int $lim);
```
 Limit until in collection


Parameters

| Type | Variable | Description            |
|---   |---       |---                     |
|int   |$lim      |Maximum entries returned|

Returns: [Collection](#collection-gogslib)

---


##### offset `Gogs\Lib\ArrayIterator`
```php
public function offset(int $off);
```
 Get from offset collection


Parameters

| Type | Variable | Description             |
|---   |---       |---                      |
|int   |$off      |Offset from in collection|

Returns: [Collection](#collection-gogslib)

---


##### reverse `Gogs\Lib\ArrayIterator`
```php
public function reverse();
```
 Reverse the collection


Returns: [Collection](#collection-gogslib)

---


##### sort `Gogs\Lib\ArrayIterator`
```php
public function sort(callable $f);
```
 Sort collection


Parameters

| Type   | Variable | Description |
|---     |---       |---          |
|callable|$f        |*None*       |

Returns: [Collection](#collection-gogslib)

---


##### filter `Gogs\Lib\ArrayIterator`
```php
public function filter(callable $f);
```
 Filter collection


Parameters

| Type   | Variable | Description |
|---     |---       |---          |
|callable|$f        |*None*       |

Returns: [Collection](#collection-gogslib)

---

# Gogs\Lib\Curl

## Traits

### Client `Gogs\Lib\Curl`

A trait used for every class referencing the api-url and token.

#### Methods

|Name                                             |Return|Access       |Description                                                                                                                                                                                                                                                                              |
|:---                                             |:---  |:---         |:---                                                                                                                                                                                                                                                                                     |
|[basic](#basic-gogslibcurlclient)                |      |protected    | Basic sets the user for basic HTTP-authentication.                                                                                                                                                                                                                                      |
|[set_param](#set_param-gogslibcurlclient)        |      |protected    | Set param into array                                                                                                                                                                                                                                                                    |
|[filter_params](#filter_params-gogslibcurlclient)|      |protected    | Filter out NULL values from parameters.                                                                                                                                                                                                                                                 |
|[method](#method-gogslibcurlclient)              |int   |protected    | Initializes a curl request of different kinds, dependingon the specified method. This can be                                                                                                                                                                                            |
|[authorized](#authorized-gogslibcurlclient)      |bool  |protected    | Checks if the user is authorized for the scope. Shouldn'tbe used frequently. One test for one scope should be enough,but if you know for sure thats you're programming with theuse of an authorized user you should leave this and justhandle the NotAuthorizedExeption whenever thrown.|
|[get_log](#get_log-gogslibcurlclient)            |array|public static| Returns log entries for the client.                                                                                                                                                                                                                                                     |

#### Method details

##### basic `Gogs\Lib\Curl\Client`
```php
protected function basic(string $user);
```
 Basic sets the user for basic HTTP-authentication.


Parameters

| Type | Variable | Description |
|---   |---       |---          |
|string|$user     |*None*       |
---


##### set_param `Gogs\Lib\Curl\Client`
```php
protected function set_param(array $params, string $param_name, array $args, int $index, string $type, mixed $default = null, callable $f = null);
```
 Set param into array

The specified callback will only run if the expected
parameter is set. This callback can either overwrite
paramtere as passing them as reference or throw an exception
to indicate invalid data.


Parameters

| Type   | Variable | Description                         |
|---     |---        |---                                  |
|array   |$params    |&$params Array to insert to          |
|string  |$param_name|Index in params-array                |
|array   |$args      |Arguments array                      |
|int     |$index     |Index in arguments array             |
|string  |$type      |Expected type of data                |
|mixed   |$default   |Default if not expected type on index|
|callable|$f         |Callback method if param is set      |
---


##### filter_params `Gogs\Lib\Curl\Client`
```php
protected function filter_params(array $params);
```
 Filter out NULL values from parameters.

Saves transferring size.


Parameters

| Type | Variable | Description       |
|---   |---       |---                |
|array|$params   |&$params Parameters|
---


##### method `Gogs\Lib\Curl\Client`
```php
protected function method(string $method, string $req, string $scope, array $params, bool $ret);
```
 Initializes a curl request of different kinds, depending
on the specified method. This can be

DELETE, PATCH, POST or GET. An unidentified value will
become a GET-request.


Parameters

| Type | Variable | Description                           |
|---   |---       |---                                    |
|string|$method   |either DELETE, PATCH, POST, GET        |
|string|$req      |&$req variable to store request body in|
|string|$scope    |scope within the API (e.g /user/repos)|
|array|$params   |parameters to pass                     |
|bool  |$ret      |return transfer                        |

Returns: int

---


##### authorized `Gogs\Lib\Curl\Client`
```php
protected function authorized(string $scope = "");
```
 Checks if the user is authorized for the scope. Shouldn't
be used frequently. One test for one scope should be enough,
but if you know for sure thats you're programming with the
use of an authorized user you should leave this and just
handle the NotAuthorizedExeption whenever thrown.


Parameters

| Type | Variable | Description              |
|---   |---       |---                       |
|string|$scope    |the scope, a relative uri.|

Returns: bool

Throws: 

* [Not](#not-gogslibcurl "Exception: Gogs\Lib\Curl\Not")

---


##### get_log `Gogs\Lib\Curl\Client`
```php
public static function get_log();
```
 Returns log entries for the client.


Returns: array

---

# Gogs\Lib\Curl\Exception

## Exceptions

### HTTPUnexpectedResponse `Gogs\Lib\Curl\Exception`


* Class extends [Exception](https://www.google.no/search?q=Exception)

Defines an unexpected response.

#### Methods

|Name                                                                  |Return|Access|Description                                           |
|:---                                                                  |:---  |:---  |:---                                                  |
|[__construct](#__construct-gogslibcurlexceptionhttpunexpectedresponse)|      |public| Sets the exceptions.                                 |
|[__toString](#__tostring-gogslibcurlexceptionhttpunexpectedresponse)  |string|public| Visual representation of the exception.              |
|[getResponse](#getresponse-gogslibcurlexceptionhttpunexpectedresponse)|string|public| Get the actual response from the body or the request.|

#### Method details

##### __construct `Gogs\Lib\Curl\Exception\HTTPUnexpectedResponse`
```php
public function __construct(string $message, int $code, Exception $previous = null);
```
 Sets the exceptions.


Parameters

| Type    | Variable | Description |
|---      |---       |---          |
|string   |$message  |*None*       |
|int      |$code     |*None*       |
|Exception|$previous|*None*       |
---


##### __toString `Gogs\Lib\Curl\Exception\HTTPUnexpectedResponse`
```php
public function __toString();
```
 Visual representation of the exception.


Returns: string

---


##### getResponse `Gogs\Lib\Curl\Exception\HTTPUnexpectedResponse`
```php
public function getResponse();
```
 Get the actual response from the body or the request.


Returns: string

---


### NotAuthorizedException `Gogs\Lib\Curl\Exception`


* Class extends [Gogs\Lib\Curl\Exception\HTTPUnexpectedResponse](#httpunexpectedresponse-gogslibcurlexception)

When the request fails because of an unauthorized token,
this is thrown instead.

#### Methods

|Name                                                                  |Return|Access|Description          |
|:---                                                                  |:---  |:---  |:---                 |
|[__construct](#__construct-gogslibcurlexceptionnotauthorizedexception)|      |public| Sets the exceptions.|

#### Method details

##### __construct `Gogs\Lib\Curl\Exception\NotAuthorizedException`
```php
public function __construct(string $message, int $code = 401, Exception $previous = null);
```
 Sets the exceptions.


Parameters

| Type    | Variable | Description |
|---      |---       |---          |
|string   |$message  |*None*       |
|int      |$code     |*None*       |
|Exception|$previous|*None*       |
---


 - Genetated using Sami and the [Sami/Twig Markdown Extension](https://git.giaever.org/joachimmg/sami-markdown)

