<?php


namespace Reach;


use Reach\Exceptions\DeserializeException;
use Reach\Exceptions\RestException;
use Reach\Http\Response;

abstract class Page implements \Iterator {
    protected static $metaKeys = [
        'outOfPageRange',
        'pageSize',
        'totalPages',
        'page',
    ];

    protected $version;
    protected $payload;
    protected $solution;
    protected $records;
    protected $url;

    abstract public function buildInstance(array $payload);

    public function __construct(string $url, Version $version, Response $response) {
        $payload = $this->processResponse($response);

        $this->url = $url;
        $this->version = $version;
        $this->payload = $payload;
        $this->solution = [];
        $this->records = new \ArrayIterator($this->loadPage());
    }

    protected function processResponse(Response $response) {
        if ($response->getStatusCode() !== 200) {
            $message = '[HTTP ' . $response->getStatusCode() . '] Unable to fetch page';
            $code = $response->getStatusCode();

            $content = $response->getContent();
            $details = [];
            $moreInfo = '';

            if (\is_array($content)) {
                $message .= isset($content['errorMessage']) ? ': ' . $content['errorMessage'] : '';
                $code = $content['errorCode'] ?? $code;
                $moreInfo = $content['more_info'] ?? '';
                $details = $content['errorDetails'] ?? [] ;
            }

            throw new RestException($message, $code, $response->getStatusCode(), $moreInfo, $details);
        }
        return $response->getContent();
    }

    protected function hasMeta(string $key): bool {
        return \array_key_exists('meta', $this->payload) && \array_key_exists($key, $this->payload['meta']);
    }

    protected function getMeta(string $key, string $default = null): ?string {
        return $this->hasMeta($key) ? $this->payload['meta'][$key] : $default;
    }

    protected function loadPage(): array {
        $key = $this->getMeta('key');
        if ($key) {
            return $this->payload[$key];
        }

        $keys = \array_keys($this->payload);
        $key = \array_diff($keys, self::$metaKeys);
        $key = \array_values($key);

        if (\count($key) === 1) {
            return $this->payload[$key[0]];
        }else if (\count($key) === 2) {
            $key1 = $key[0];
            $key2 = $key[1];
            if(\strlen($key1) > \strlen($key2)){
                $aux = $key1;
                $key1 = $key2;
                $key2 = $aux;
            }
            $key3 = "total" . \strtoupper(\substr($key1, 0, 1)) . \substr($key1, 1);
            if($key3 == $key2){
                return $this->payload[$key1];
            }
        }

        throw new DeserializeException('Page Records can not be deserialized');
    }

    public function getPreviousPageUrl(): ?string {
        $current_page = \array_key_exists('page', $this->payload) ? $this->payload['page'] : 0;
        $pageSize = \array_key_exists('pageSize', $this->payload) ? $this->payload['pageSize'] : 1;
        if($current_page > 0){
            $parsed = \parse_url($this->url);
            $query = "pageSize=".$pageSize."&page=".($current_page-1);
            if(!(!\array_key_exists('query', $parsed) || \strlen($parsed["query"])==0)){
                $query = $parsed["query"]."&".$query;
            }
            $parsed["query"] =  $query;
            return $this->version->unparse_url($parsed);
        }
        return null;
    }

    public function getNextPageUrl(): ?string {
        $current_page = \array_key_exists('page', $this->payload) ? $this->payload['page'] : 0;
        $pageSize = \array_key_exists('pageSize', $this->payload) ? $this->payload['pageSize'] : 1;
        $outOfPageRange = \array_key_exists('outOfPageRange', $this->payload) ? $this->payload['outOfPageRange'] : true;
        $totalPages = \array_key_exists('totalPages', $this->payload) ? $this->payload['totalPages'] : 1;
        if(!($outOfPageRange || ($current_page + 1 >= $totalPages))){
            $parsed = \parse_url($this->url);
            $query = "pageSize=".$pageSize."&page=".($current_page+1);
            if(!(!\array_key_exists('query', $parsed) || \strlen($parsed["query"])==0)){
                $query = $parsed["query"]."&".$query;
            }
            $parsed["query"] =  $query;
            return $this->version->unparse_url($parsed);
        }
        
        return null;
    }

    public function nextPage(): ?Page {
        if (!$this->getNextPageUrl()) {
            return null;
        }

        $response = $this->getVersion()->getDomain()->getClient()->request('GET', $this->getNextPageUrl());
        return new static($this->url, $this->getVersion(), $response, $this->solution);
    }

    public function previousPage(): ?Page {
        if (!$this->getPreviousPageUrl()) {
            return null;
        }

        $response = $this->getVersion()->getDomain()->getClient()->request('GET', $this->getPreviousPageUrl());
        return new static($this->url, $this->getVersion(), $response, $this->solution);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    #[\ReturnTypeWillChange]
    public function current() {
        return $this->buildInstance($this->records->current());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void {
        $this->records->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    #[\ReturnTypeWillChange]
    public function key() {
        return $this->records->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool {
        return $this->records->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void {
        $this->records->rewind();
    }


    public function getVersion(): Version {
        return $this->version;
    }

    public function __toString(): string {
        return '[Page]';
    }

}
