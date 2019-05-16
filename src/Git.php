<?php
namespace Clippedcode\Git;
/**
 * Main Interface for the Git API Bridge
 * @author Shashwat Mishra <shashwat@clippedcode.com>
 * @copyright Clipped Code <https://clippedcode.com>
 * @version 0.0.1-Alpha.1
 */

use Clippedcode\GitClient\API\Client;
use Clippedcode\GitClient\Lib\Curl\Exception as ApiException;

class Git {
    /**
     * Client Interface
     * @var object client
     * @access private
     */
    private $client;

    /**
     * Initial Construction
     * @access public
     * @return object client
     */
    public function __construct(string $endpoint = '', string $token = '')
    {
        // Define if Empty
        $endpoint = empty($endpoint) ?? config('ccgogs.api.endpoint');
        $token = empty($token) ?? config('ccgogs.api.token');

        // Creating Connection
        $client = new Client($endpoint, $token);
        
        self::setClient($client); // Storing Connection
        return self::getClient(); // Returning the Client
    }

    /**
     * Store the Client
     * 
     * @access protected
     * @return null
     */
    protected function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Get the Stored Client
     * 
     * @access public
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

}