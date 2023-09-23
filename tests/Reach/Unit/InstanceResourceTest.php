<?php

namespace Reach\Tests\Unit;

use Reach\Http\CurlClient;
use Reach\InstanceResource;
use Reach\Rest\Client;
use Reach\Version;

class TestInstance extends InstanceResource {
    public function __construct(Version $version) {
        parent::__construct($version);

        $this->properties = [
            'someKey' => 'someValue'
        ];
    }
}

class InstanceResourceTest extends UnitTest {
    protected $curlClient;
    /** @var Client $client */
    protected $client;
    /** @var TestDomain $domain */
    protected $domain;
    /** @var TestVersion $version */
    protected $version;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->curlClient = $this->createMock(CurlClient::class);
        $this->client = new Client('username', 'password', $this->curlClient, null);
        $this->domain = new TestDomain($this->client);
        $this->version = new TestVersion($this->domain);
    }

    public function testIsset(): void {
        $resource = new TestInstance($this->version);
        $this->assertTrue(isset($resource->someKey));
        $this->assertFalse(isset($resource->someOtherKey));
    }
}
