<?php

namespace Gitea\Api\Abstracts;

use Gitea\Client;

use Gitea\Api\Interfaces\ApiRequesterInterface;

/**
 * Abstract class for Api classes
 *
 * @author Benjamin Blake (sitelease.ca)
 */
abstract class AbstractApiRequester implements ApiRequesterInterface
{
    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * The API authentication token for Gitea
     *
     * @var string
     */
    private $authToken;

    /**
     * The default parameters that should be sent
     * in ALL requests to this api route
     *
     * @var array
     */
    protected $defaultParameters = [];

    /**
     * The default headers that should be sent
     * in ALL requests to this api route
     *
     * @var array
     */
    protected $defaultHeaders = [
      'Accept' => 'application/json'
    ];

    /**
     * The default parameters that should be sent
     * in all GET requests to this api route
     *
     * @var array
     */
    protected $getDefaultParameters = [];

    /**
     * The default headers that should be sent
     * in all GET requests to this api route
     *
     * @var array
     */
    protected $getDefaultHeaders = [];

    /**
     * The default headers that should be sent
     * in all POST requests to this api route
     *
     * @var array
     */
    protected $postDefaultHeaders = [
      'Content-Type' => 'application/json'
    ];

    /**
     * The default headers that should be sent
     * in all PUT requests to this api route
     *
     * @var array
     */
    protected $putDefaultHeaders = [
      'Content-Type' => 'application/json'
    ];

    /**
     * The default headers that should be sent
     * in all DELETE requests to this api route
     *
     * @var array
     */
    protected $deleteDefaultHeaders = [];

    /**
     * @param Client $client
     */
    public function __construct(Client $client, $authToken)
    {
        $this->client = $client;
        $this->authToken = $authToken;

        // Set authorization headers and parameters
        $this->getDefaultParameters["access_token"] = $authToken;
        $this->postDefaultHeaders["Authorization"] = "token $authToken";
        $this->putDefaultHeaders["Authorization"] = "token $authToken";
        $this->deleteDefaultHeaders["Authorization"] = "token $authToken";
    }

    public function getClient() {
      return $this->client;
    }

    public function getAuthToken() {
      return $this->authToken;
    }

    /**
     * Get the default parameters for a particular type of request
     *
     * If "all" is passed OR if the $type parameter is left blank
     * an array of defaults for ALL request types will be returned
     *
     * @author Benjamin Blake (sitelease.ca)
     *
     * @param string $type The type of request ("all","get","post","put",etc.)
     * @return array
     */
    public function getDefaultParametersForType($type = "all") {
      if (!$type || $type == "all") {
        return $this->defaultParameters;
      } else {
        $propertyName = $type."DefaultParameters";
        if (property_exists(__CLASS__, $propertyName) && is_array($this->$propertyName)) {
          return array_merge($this->defaultParameters, $this->$propertyName);
        } else {
          return [];
        }
      }
    }

    /**
     * Get the default headers for a particular type of request
     *
     * If "all" is passed OR if the $type parameter is left blank
     * an array of defaults for ALL request types will be returned
     *
     * @author Benjamin Blake (sitelease.ca)
     *
     * @param string $type The type of request ("all", "get","post","put",etc.)
     * @return array
     */
    public function getDefaultHeadersForType($type = "all") {
      if (!$type || $type == "all") {
        return $this->defaultHeaders;
      } else {
        $propertyName = $type."DefaultHeaders";
        if (property_exists(__CLASS__, $propertyName) && is_array($this->$propertyName)) {
          return array_merge($this->defaultParameters, $this->$propertyName);
        } else {
          return [];
        }
      }
    }

    /**
     * @return $this
     * @codeCoverageIgnore
     */
    public function configure()
    {
        return $this;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @return mixed
     */
    public function get($path, array $parameters = array(), $requestHeaders = array(), $debugRequest = false)
    {
      $client = $this->getClient();
      $guzzleClient = $client->getGuzzleClient();
      $defaultParameters = $this->getDefaultParametersForType("get");
      $defaultHeaders = $this->getDefaultParametersForType("get");

      // Create request options array
      // and populate it with defaults
      $requestOptions = [];
      $requestOptions['query'] = $defaultParameters;
      $requestOptions['headers'] = $defaultHeaders;
      if ($debugRequest) {
        $requestOptions['debug'] = true;
      }

      if ($parameters) {
        $requestOptions['query'] = array_merge($defaultParameters, $parameters);
      }

      if ($requestHeaders) {
        $requestOptions['headers'] = array_merge($defaultHeaders, $requestHeaders);
      }

      return $guzzleClient->request('GET', $path, $requestOptions);
    }

    /**
     * @param string $path
     * @param array $body
     * @param array $requestHeaders
     * @return mixed
     */
    public function post($path, $body, $requestHeaders = array(), $debugRequest = false)
    {
      $client = $this->getClient();
      $guzzleClient = $client->getGuzzleClient();
      $defaultHeaders = $this->getDefaultHeadersForType("post");

      // Create request options array
      // and populate it with defaults
      $requestOptions = [];
      $requestOptions['headers'] = $defaultHeaders;
      $requestOptions['body'] = "{}";
      if ($debugRequest) {
        $requestOptions['debug'] = true;
      }

      if ($body) {
        if (is_object($body)) {
          $requestOptions['body'] = json_encode($body);
        }

        if (is_string($body)) {
          $requestOptions['body'] = $body;
        }
      }

      if ($requestHeaders) {
        $requestOptions['headers'] = array_merge($defaultHeaders, $requestHeaders);
      }

      return $guzzleClient->request('POST', $path, $requestOptions);
    }

    /**
     * @param string $path
     * @param array $body
     * @param array $requestHeaders
     * @return mixed
     */
    public function put($path, $body, $requestHeaders = array(), $debugRequest = false)
    {
      $client = $this->getClient();
      $guzzleClient = $client->getGuzzleClient();
      $defaultHeaders = $this->getDefaultHeadersForType("put");

      // Create request options array
      // and populate it with defaults
      $requestOptions = [];
      $requestOptions['headers'] = $defaultHeaders;
      $requestOptions['body'] = "{}";
      if ($debugRequest) {
        $requestOptions['debug'] = true;
      }

      if ($body) {
        if (is_object($body)) {
          $requestOptions['body'] = json_encode($body);
        }

        if (is_string($body)) {
          $requestOptions['body'] = $body;
        }
      }

      if ($requestHeaders) {
        $requestOptions['headers'] = array_merge($defaultHeaders, $requestHeaders);
      }

      return $guzzleClient->request('PUT', $path, $requestOptions);
    }

    /**
     * @param string $path
     * @param array $requestHeaders
     * @return mixed
     */
    public function delete($path, $requestHeaders = array(), $debugRequest = false)
    {
      $client = $this->getClient();
      $guzzleClient = $client->getGuzzleClient();
      $defaultHeaders = $this->getDefaultHeadersForType("delete");

      // Create request options array
      // and populate it with defaults
      $requestOptions = [];
      $requestOptions['headers'] = $defaultHeaders;
      if ($debugRequest) {
        $requestOptions['debug'] = true;
      }

      if ($requestHeaders) {
        $requestOptions['headers'] = array_merge($defaultHeaders, $requestHeaders);
      }

      return $guzzleClient->request('DELETE', $path, $requestOptions);
    }
}