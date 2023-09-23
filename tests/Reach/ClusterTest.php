<?php

namespace Reach\Tests;
require "vendor/autoload.php";

class ClusterTest extends \PHPUnit\Framework\TestCase
{
    public static $apiUser = "";
    public static $toNumber = "";
    public static $apiKey = "";
    public static $fromNumber = "";
    public static $reach;

    public static function setUpBeforeClass(): void
    {
        self::$apiUser = getenv("REACH_TALKYLABS_API_USER");
        self::$toNumber = getenv("REACH_TALKYLABS_TO_NUMBER");
        self::$apiKey = getenv("REACH_TALKYLABS_API_KEY");
        self::$fromNumber = getenv("REACH_TALKYLABS_FROM_NUMBER");
        self::$reach = new \Reach\Rest\Client($username = self::$apiUser, $password = self::$apiKey);
    }

    public function testSendingAText(): void
    {
        $message = self::$reach->messaging->messagingItems->send(self::$toNumber,
            [
                "src" => self::$fromNumber,
                "body" => "reach-php Cluster test message"
            ]
        );
        $this->assertNotNull($message);
        $this->assertEquals("reach-php Cluster test message", $message->body);
        $this->assertEquals(self::$fromNumber, $message->src);
        $this->assertEquals(self::$toNumber, $message->dest);
    }
}