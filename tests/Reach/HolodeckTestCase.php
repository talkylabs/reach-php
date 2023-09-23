<?php

namespace Reach\Tests;

use Reach\Rest\Client;
use Reach\Tests\Unit\UnitTest;

class HolodeckTestCase extends UnitTest {
    /** @var Holodeck $holodeck */
    protected $holodeck;
    /** @var Client $reach */
    protected $reach;

    protected function setUp(): void {
        $this->holodeck = new Holodeck();
        $this->reach = new Client('ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'AUTHTOKEN', $this->holodeck);
    }

    protected function tearDown(): void {
        $this->reach = null;
        $this->holodeck = null;
    }

    public function assertRequest(Request $request): void {
        $this->holodeck->assertRequest($request);
        $this->assertTrue(true);
    }
}
