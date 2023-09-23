<?php
namespace Reach\Base;

use Reach\Exceptions\ConfigurationException;
use Reach\Exceptions\ReachException;
use Reach\Http\Client as HttpClient;
use Reach\Http\CurlClient;
use Reach\Rest\Api;
use Reach\Security\RequestValidator;
use Reach\VersionInfo;

/**
 * @property Api $api
 */
class BaseClient
{
    const ENV_ACCOUNT_SID = 'REACH_TALKYLABS_API_USER';
    const ENV_AUTH_TOKEN = 'REACH_TALKYLABS_API_KEY';
    const ENV_LOG = 'REACH_TALKYLABS_LOG_LEVEL';

    protected $username;
    protected $password;
    protected $accountSid;
    protected $httpClient;
    protected $environment;
    protected $userAgentExtensions;
    protected $logLevel;
    protected $_account;

    /**
     * Initializes the Reach Client
     *
     * @param string $username Username to authenticate with
     * @param string $password Password to authenticate with
     * @param HttpClient $httpClient HttpClient, defaults to CurlClient
     * @param mixed[] $environment Environment to look for auth details, defaults
     *                             to $_ENV
     * @param string[] $userAgentExtensions Additions to the user agent string
     * @throws ConfigurationException If valid authentication is not present
     */
    public function __construct(
        string $username = null,
        string $password = null,
        HttpClient $httpClient = null,
        array $environment = null,
        array $userAgentExtensions = null
    ) {
        $this->environment = $environment ?: \getenv();

        $this->username = $this->getArg($username, self::ENV_ACCOUNT_SID);
        $this->password = $this->getArg($password, self::ENV_AUTH_TOKEN);
        $this->logLevel = $this->getArg(null, self::ENV_LOG);
        $this->userAgentExtensions = $userAgentExtensions ?: [];

        if (!$this->username || !$this->password) {
            throw new ConfigurationException('Credentials are required to create a Client');
        }

        $this->accountSid = $this->username;

        if ($httpClient) {
            $this->httpClient = $httpClient;
        } else {
            $this->httpClient = new CurlClient();
        }
    }

    /**
     * Determines argument value accounting for environment variables.
     *
     * @param string $arg The constructor argument
     * @param string $envVar The environment variable name
     * @return ?string Argument value
     */
    public function getArg(?string $arg, string $envVar): ?string
    {
        if ($arg) {
            return $arg;
        }

        if (\array_key_exists($envVar, $this->environment)) {
            return $this->environment[$envVar];
        }

        return null;
    }

    /**
     * Makes a request to the Reach API using the configured http client
     * Authentication information is automatically added if none is provided
     *
     * @param string $method HTTP Method
     * @param string $uri Fully qualified url
     * @param string[] $params Query string parameters
     * @param string[] $data POST body data
     * @param string[] $headers HTTP Headers
     * @param string $username User for Authentication
     * @param string $password Password for Authentication
     * @param int $timeout Timeout in seconds
     * @return \Reach\Http\Response Response from the Reach API
     */
    public function request(
        string $method,
        string $uri,
        array $params = [],
        array $data = [],
        array $headers = [],
        string $username = null,
        string $password = null,
        int $timeout = null
    ): \Reach\Http\Response{
        $username = $username ?: $this->username;
        $password = $password ?: $this->password;
        $logLevel = (getenv('DEBUG_HTTP_TRAFFIC') === 'true' ? 'debug' : $this->getLogLevel());

        $headers['User-Agent'] = 'reach-php/' . VersionInfo::string() .
            ' (' . php_uname("s") . ' ' . php_uname("m") . ')' .
            ' PHP/' . PHP_VERSION;
        $headers['Accept-Charset'] = 'utf-8';

        if ($this->userAgentExtensions) {
            $headers['User-Agent'] .= ' ' . implode(' ', $this->userAgentExtensions);
        }

        if (!\array_key_exists('Accept', $headers)) {
            $headers['Accept'] = 'application/json';
        }

        $uri = $this->buildUri($uri);

        if ($logLevel === 'debug') {
            error_log('-- BEGIN Reach API Request --');
            error_log('Request Method: ' . $method);
            $u = parse_url($uri);
            if (isset($u['path'])) {
                error_log('Request URL: ' . $u['path']);
            }
            if (isset($u['query']) && strlen($u['query']) > 0) {
                error_log('Query Params: ' . $u['query']);
            }
            error_log('Request Headers: ');
            foreach ($headers as $key => $value) {
                if (strpos(strtolower($key), 'authorization') === false && strpos(strtolower($key), 'apikey') === false && strpos(strtolower($key), 'apiuser') === false) {
                    error_log("$key: $value");
                }
            }
            error_log('-- END Reach API Request --');
        }

        $response = $this->getHttpClient()->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($logLevel === 'debug') {
            error_log('Status Code: ' . $response->getStatusCode());
            error_log('Response Headers:');
            $responseHeaders = $response->getHeaders();
            foreach ($responseHeaders as $key => $value) {
                error_log("$key: $value");
            }
        }

        return $response;
    }

    /**
     * Build the final request uri
     *
     * @param string $uri The original request uri
     * @return string Request uri
     */
    public function buildUri(string $uri): string
    {
        return $uri;
    }

    /**
     * Magic getter to lazy load domains
     *
     * @param string $name Domain to return
     * @return \Reach\Domain The requested domain
     * @throws ReachException For unknown domains
     */
    public function __get(string $name)
    {
        $method = 'get' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return $this->$method();
        }

        throw new ReachException('Unknown domain ' . $name);
    }

    /**
     * Magic call to lazy load contexts
     *
     * @param string $name Context to return
     * @param mixed[] $arguments Context to return
     * @return \Reach\InstanceContext The requested context
     * @throws ReachException For unknown contexts
     */
    public function __call(string $name, array $arguments)
    {
        $method = 'context' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return \call_user_func_array([$this, $method], $arguments);
        }

        throw new ReachException('Unknown context ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Client ' . $this->getAccountSid() . ']';
    }

    /**
     * Validates connection to new SSL certificate endpoint
     *
     * @param CurlClient $client
     * @throws ReachException if request fails
     */
    public function validateSslCertificate(CurlClient $client): void
    {
        $response = $client->request('GET', 'https://api.reach.talkylabs.com:8443');

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 300) {
            throw new ReachException('Failed to validate SSL certificate');
        }
    }


    /**
     * Retrieve the Username
     *
     * @return string Current Username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Retrieve the Password
     *
     * @return string Current Password
     */
    public function getPassword(): string
    {
        return $this->password;
    }



    /**
     * Retrieve the HttpClient
     *
     * @return HttpClient Current HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * Set the HttpClient
     *
     * @param HttpClient $httpClient HttpClient to use
     */
    public function setHttpClient(HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Retrieve the log level
     *
     * @return ?string Current log level
     */
    public function getLogLevel(): ?string
    {
        return $this->logLevel;
    }

    /**
     * Set log level to debug
     *
     * @param string $logLevel log level to use
     */
    public function setLogLevel(string $logLevel = null): void
    {
        $this->logLevel = $this->getArg($logLevel, self::ENV_LOG);
    }
}
