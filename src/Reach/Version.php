<?php

namespace Reach;

use Reach\Exceptions\RestException;
use Reach\Exceptions\ReachException;
use Reach\Http\Response;

abstract class Version {
    /**
     * @const int MAX_PAGE_SIZE largest page the Reach API will return
     */
    public const MAX_PAGE_SIZE = 1000;

    /**
     * @var Domain $domain
     */
    protected $domain;

    /**
     * @var string $version
     */
    protected $version;

    /**
     * @param Domain $domain
     */
    public function __construct(Domain $domain) {
        $this->domain = $domain;
        $this->version = null;
    }

    /**
    * Generate an URL without pagination info
    * @param string $url original url 
    * @param array $params query parameters associated with the url
    * @return string URL without pagination info
    */
    public function urlWithoutPaginationInfo(string $url, array $params = []): string {
        $query = \http_build_query($params, '', '&');
        $parsed = \parse_url($url);
        if(!(!\array_key_exists('query', $parsed) || \strlen($parsed["query"])==0)){
            $query = $parsed["query"].(\strlen($query) > 0 ? "&": "").$query;
        }
        if(\strlen($query)==0){
            return $url;
        }
        $queryParams = \explode("&", $query);
        $q = array("page", "pageSize");
        foreach ($q as $par) {
            $prefix = $par."=";
            $i = 0;
            while($i < \sizeof($queryParams)) {
                $pos = \strpos($queryParams[$i], $prefix);
                if(!($pos===false || $pos > 0)){
                    \array_splice($queryParams, $i, 1);
                }else{
                    $i++;
                }                
            }
        }
        $query = \sizeof($queryParams) == 0 ? "" : \implode("&", $queryParams);
        $parsed["query"] = $query;
        return $this->unparse_url($parsed);
    }

    /**
     * Generate an absolute URL from a version relative uri
     * @param string $uri Version relative uri
     * @return string Absolute URL
     */
    public function absoluteUrl(string $uri): string {
        return $this->getDomain()->absoluteUrl($this->relativeUri($uri));
    }

    /**
     * Generate a domain relative uri from a version relative uri
     * @param string $uri Version relative uri
     * @return string Domain relative uri
     */
    public function relativeUri(string $uri): string {
        return \trim($this->version ?? '', '/') . '/' . \trim($uri, '/');
    }

    public function request(string $method, string $uri,
                            array $params = [], array $data = [], array $headers = [],
                            string $username = null, string $password = null,
                            int $timeout = null): Response {
        $uri = $this->relativeUri($uri);
        return $this->getDomain()->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );
    }

    public function unparse_url($parsed_url) {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';      
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';      
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';      
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';      
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';      
        $pass     = ($user || $pass) ? "$pass@" : '';      
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';      
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';      
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';      
        return "$scheme$user$pass$host$port$path$query$fragment";      
    }

    /**
     * Create the best possible exception for the response.
     *
     * Attempts to parse the response for Reach Standard error messages and use
     * those to populate the exception, falls back to generic error message and
     * HTTP status code.
     *
     * @param Response $response Error response
     * @param string $header Header for exception message
     * @return ReachException
     */
    protected function exception(Response $response, string $header): ReachException {
        $message = '[HTTP ' . $response->getStatusCode() . '] ' . $header;

        $content = $response->getContent();
        if (\is_array($content)) {
            $message .= isset($content['errorMessage']) ? ': ' . $content['errorMessage'] : '';
            $code = isset($content['errorCode']) ? $content['errorCode'] : $response->getStatusCode();
            $moreInfo = $content['more_info'] ?? '';
            $details = $content['errorDetails'] ?? [];
            return new RestException($message, $code, $response->getStatusCode(), $moreInfo, $details);
        }

        return new RestException($message, $response->getStatusCode(), $response->getStatusCode());
    }

    /**
     * @throws ReachException
     */
    public function fetch(string $method, string $uri,
                          array $params = [], array $data = [], array $headers = [],
                          string $username = null, string $password = null,
                          int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        // 3XX response codes are allowed here to allow for 307 redirect from Deactivations API.
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 400) {
            throw $this->exception($response, 'Unable to fetch record');
        }

        return $response->getContent();
    }

    /**
     * @throws ReachException
     */
    public function update(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to update record');
        }

        return $response->getContent();
    }

    /**
     * @throws ReachException
     */
    public function unschedule(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to unschedule record');
        }

        return $response->getContent();
    }

    /**
     * @throws ReachException
     */
    public function delete(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null): bool {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to delete record');
        }

        return $response->getStatusCode() === 204;
    }

    public function readLimits(int $limit = null, int $pageSize = null): array {
        if ($limit && $pageSize === null) {
            $pageSize = $limit;
        }

        $pageSize = \min($pageSize, self::MAX_PAGE_SIZE);

        return [
            'limit' => $limit ?: Values::NONE,
            'pageSize' => $pageSize ?: Values::NONE,
            'pageLimit' => Values::NONE,
        ];
    }

    public function page(string $method, string $uri,
                         array $params = [], array $data = [], array $headers = [],
                         string $username = null, string $password = null,
                         int $timeout = null): Response {
        return $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );
    }

    public function stream(Page $page, $limit = null, $pageLimit = null): Stream {
        return new Stream($page, $limit, $pageLimit);
    }

    /**
     * @throws ReachException
     */
    public function create(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to create record');
        }

        return $response->getContent();
    }

    /**
     * @throws ReachException
     */
    public function send(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to send record');
        }

        return $response->getContent();
    }

    /**
     * @throws ReachException
     */
    public function start(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to start record');
        }

        return $response->getContent();
    }

    /**
     * @throws ReachException
     */
    public function check(string $method, string $uri,
                           array $params = [], array $data = [], array $headers = [],
                           string $username = null, string $password = null,
                           int $timeout = null) {
        $response = $this->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw $this->exception($response, 'Unable to check record');
        }

        return $response->getContent();
    }

    public function getDomain(): Domain {
        return $this->domain;
    }

    public function __toString(): string {
        return '[Version]';
    }
}
