<?php
require(__DIR__.'/../src/Reach/autoload.php');

use Reach\Rest\Client;

$apiUser = getenv('REACH_TALKYLABS_API_USER');
$apiKey = getenv('REACH_TALKYLABS_API_KEY');
$client = new Client($apiUser, $apiKey);

// This parameter determines the destination phone number for your SMS message. Format this number with a '+' and a country code
$phoneNumber = "+XXXXXXXXXX";

// This must be a phone number that you own, formatted with a '+' and country code
$reachFromNumber = "+XXXXXXXXXX";

// Send a text message
$message = $client->messaging->messagingItems->send(
    $phoneNumber, $reachFromNumber, "Hey Jenny! Good luck on the bar exam!"
);
print("Message sent successfully with sid = " . $message->messageId ."\n\n");

// Print the last 10 messages
$messageList = $client->messaging->messagingItems->read([],10);
foreach ($messageList as $msg) {
    print("ID:: ". $msg->messageId . " | " . "From:: " . $msg->src . " | " . "TO:: " . $msg->dest . " | "  .  " Status:: " . $msg->status . " | " . " Body:: ". $msg->body ."\n");
}
