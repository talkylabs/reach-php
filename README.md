# reach-php


## Documentation

The documentation for the Reach API can be found [here][apidocs].

The PHP library documentation can be found [here][libdocs].

## Versions

`reach-php` uses a modified version of [Semantic Versioning](https://semver.org) for all changes. [See this document](VERSIONS.md) for details.

### Supported PHP Versions

This library supports the following PHP implementations:

- PHP 7.2
- PHP 7.3
- PHP 7.4
- PHP 8.0
- PHP 8.1

## Installation

You can install `reach-php` via [composer](https://getcomposer.org/) or by downloading the source.

### Via Composer

`reach-php` is available on Packagist as the [`talkylabs/reach-sdk`](https://packagist.org/packages/talkylabs/reach-sdk) package:

```shell
composer require talkylabs/reach-sdk
```

### Test your installation

Here is an example of using the SDK to send a text message:

```php
// Send an SMS using Reach's REST API and PHP
<?php
// Required if your environment does not handle autoloading
require __DIR__ . '/vendor/autoload.php';

// Your ApiUser and ApiKey Token from the web application
$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";
$client = new Reach\Rest\Client($apiUser, $apiKey);

// Use the Client to make requests to the Reach REST API
$client->messaging->messagingItems->send(
    '+15558675309', '+15017250604', "Hey Jenny! Good luck on the bar exam!"
);
```

### Without Composer

While we recommend using a package manager to track the dependencies in your application, it is possible to download and use the PHP SDK manually. You can download the full source of the PHP SDK from GitHub, and browse the repo if you would like. To use the SDK in your application, unzip the SDK download file in the same directory as your PHP code. In your code, you can then require the autoload file bundled with the SDK.

```php
<?php
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require __DIR__ . '/reach-php-main/src/Reach/autoload.php';

// Your ApiUser and ApiKey Token from the web application
$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";
$client = new Reach\Rest\Client($apiUser, $apiKey);

// Use the Client to make requests to the Reach REST API
$client->messaging->messagingItems->send(
    // The number you'd like to send the message to
    '+15558675309', '+15017250604', "Hey Jenny! Good luck on the bar exam!"
);
```

> **Warning**
> It's okay to hardcode your credentials when testing locally, but you should use environment variables to keep them secret before committing any code or deploying to production.

## Usage

### Get a messagingItem

```php
<?php
require_once '/path/to/vendor/autoload.php';

$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";
$client = new Reach\Rest\Client($apiUser, $apiKey);

// Get an object using its SID. If you do not have a SID,
// check out the list resource examples on this page
$msg = $client->messaging->messagingItems()->fetch("XXed11f93dc08b952027ffbc406d0868");
print $msg->dest;
```

### Iterate through records

The library automatically handles paging for you. Collections, such `messagingItems`, have `read` and `stream` methods that page under the hood. With both `read` and `stream`, you can specify the number of records you want to receive (`limit`) and the maximum size you want each page fetch to be (`pageSize`). The library will then handle the task for you.

`read` eagerly fetches all records and returns them as a list, whereas `stream` returns an iterator and lazily retrieves pages of records as you iterate over the collection. You can also page manually using the `page` method.

### Use the `read` method

```php
<?php
require_once '/path/to/vendor/autoload.php';

$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";
$client = new Reach\Rest\Client($apiUser, $apiKey);

// Loop over the list of messagingItems and print a property from each one
foreach ($client->messaging->messagingItems->read() as $msg) {
    print $msg->dest;
}
```

### Enable Debug Logging

There are two ways to enable debug logging in the default HTTP client. You can create an environment variable called `REACH_TALKYLABS_LOG_LEVEL` and set it to `debug` or you can set the log level to debug:

```php
$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";

$client = new Reach\Rest\Client($apiUser, $apiKey);
$client->setLogLevel('debug');
```


### Handle exceptions

When something goes wrong during client initialization, in an API request, reach-php will throw an appropriate exception. You should handle these exceptions to keep your application running and avoid unnecessary crashes.

#### The Reach client

For example, it is possible to get an authentication exception when initiating your client, perhaps with the wrong credentials. This can be handled like so:

```php
<?php
require_once('/path/to/reach-php/Services/Reach.php');

use Reach\Exceptions\ConfigurationException;
use Reach\Rest\Client;

$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";

// Attempt to create a new Client, but your credentials may contain a typo
try {
    $client = new Reach\Rest\Client($apiUser, $apiKey);
} catch (ConfigurationException $e) {
    // You can `catch` the exception, and perform any recovery method of your choice
    print $e . getCode();
}

$msg = $client->messaging->messagingItems()->
    ->fetch("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");

print $msg->dest;
```

#### CurlClient

When initializing the curl client, you will see an EnvironmentException if curl is not installed on your system.

```php
<?php
require_once('/path/to/reach-php/Services/Reach.php');

use Reach\Exceptions\ReachException;
use Reach\Http\CurlClient;

$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";
$client = new Reach\Rest\Client($apiUser, $apiKey);

try {
    $client = new CurlClient();

    $client->options(
        'GET',
        'http://api.reach.talkylabs.com',
        array(),
        array(),
        array(),
        $apiUser,
        $apiKey
    );
} catch (EnvironmentException $e) {
    print $e . getCode();
}
```

#### ReachException

`ReachException` can be used to handle API errors, as shown below. This is the most common exception type that you will most likely use.

```php
<?php
require_once('/path/to/reach-php/Services/Reach.php');

use Reach\Exceptions\ReachException;

$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";
$client = new Reach\Rest\Client($apiUser, $apiKey);

try {
    $msg = $client->messaging->messagingItems()->fetch("XXed11f93dc08b952027ffbc406d0868");
} catch (ReachException $e) {
    print $e->getCode();
}

print $msg->dest;
```

### Debug API requests

To assist with debugging, the library allows you to access the underlying request and response objects. This capability is built into the default Curl client that ships with the library.

For example, you can retrieve the status code of the last response like so:

```php
<?php
$apiUser = "XXXXXXXX";
$apiKey = "YYYYYY";

$client = new Reach\Rest\Client($apiUser, $apiKey);
$message = $client->messaging->messagingItems->send(
    '+15558675309', '+15017250604', "Hey Jenny! Good luck on the bar exam!"
);

// Print the message's SID
print $message->messageId;

// Print details about the last request
print $client->lastRequest->method;
print $client->lastRequest->url;
print $client->lastRequest->auth;
print $client->lastRequest->params;
print $client->lastRequest->headers;
print $client->lastRequest->data;

// Print details about the last response
print $client->lastResponse->statusCode;
print $client->lastResponse->body;
```

## Use a custom HTTP Client

To use a custom HTTP client with this helper library, please see the [advanced example of how to do so](./advanced-examples/custom-http-client.md).

## Docker image

The `Dockerfile` present in this repository and its respective `talkylabs/reach-php` Docker image are currently used by TalkyLabs for testing purposes only.

## Getting help

If you've found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

[apidocs]: https://www.reach.talkylabs.com/docs/api
[libdocs]: https://talkylabs.github.io/reach-php
