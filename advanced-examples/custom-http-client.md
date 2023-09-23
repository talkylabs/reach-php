# Custom HTTP Clients for the Reach PHP Helper Library

If you are working with the Reach PHP Helper Library and need to modify the HTTP requests that the library makes to the Reach servers you’re in the right place. The most common place you'll need to alter the HTTP request is to connect and authenticate with an enterprise’s proxy server. We’ll provide sample code that you can drop right into your app to handle this use case.

## Connect and authenticate with a proxy server

To connect and provide credentials to a proxy server between your app and Reach, you need a way to modify the HTTP requests that the Reach helper library makes to invoke the Reach REST API.

In PHP, the Reach helper library uses the [cURL](http://php.net/manual/en/book.curl.php) library under the hood to make HTTP requests. The Reach Helper Library allows you to provide your own `HttpClient` for making API requests.

How do we apply this to a typical Reach REST API example?

```php
<?php

$reach = new Client($apiUser, $apiKey);

$message = $reach->messaging->messagingItems
    ->send(
        "+15558675310", "+15017122661", "Hey there!"
    );
```

Where does `HttpClient` get created and used?

Out of the box, the helper library is creating a default `RequestClient` for you, using the Reach credentials you pass to the `init` method. However, there’s nothing stopping you from creating your own `RequestClient`.

Once you have your own `RequestClient`, you can pass it to any Reach REST API resource action you want. Here’s an example of sending an SMS message with a custom client:

```php
<?php
// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once "./vendor/autoload.php";
require_once "./MyRequestClass.php";

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

use Reach\Rest\Client;

// Your ApiUser and ApiKey Token from the web application
$apiUser = getenv('API_USER');
$apiKey = getenv('API_KEY');
$proxy = getenv('PROXY');

$httpClient = new MyRequestClass($proxy);
$reach = new Client($apiUser, $apiKey, $httpClient);

$message = $reach->messaging->messagingItems
    ->send(
        "+15558675310", "+15017122661", "Hey there!"
    );

print("Message SID: {$message->messageId}");
```

## Call Reach through a proxy server

Now that we understand how all the components fit together we can create our own `HttpClient` that can connect through a proxy server. To make this reusable, here’s a class that you can use to create this `HttpClient` whenever you need one.

```php
<?php

use Reach\Http\CurlClient;
use Reach\Http\Response;

class MyRequestClass extends CurlClient
{
    protected $http = null;
    protected $proxy = null;


    /**
     * MyRequestClass constructor.
     * @param $proxy Proxy Server
     * @param $cainfo CA Info for the proxy
     */
    public function __construct($proxy = null, $cainfo = null)
    {
        $this->proxy = $proxy;
        $this->cainfo = $cainfo;
        $this->http = new CurlClient();
    }

    public function request(
        $method,
        $url,
        $params = array(), $data = array(), $headers = array(), $user = null, $password = null, $timeout = null): Response
    {
        // Here you can change the URL, headers and other request parameters
        $options = $this->options(
            $method,
            $url,
            $params,
            $data,
            $headers,
            $user,
            $password,
            $timeout
        );

        $curl = curl_init($url);
        curl_setopt_array($curl, $options);
        if (!empty($this->proxy))
            curl_setopt($curl, CURLOPT_PROXY, $this->proxy);

        if (!empty($this->cainfo))
            curl_setopt($curl, CURLOPT_CAINFO, $this->cainfo);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $head = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        $responseHeaders = array();
        $headerLines = preg_split("/\r?\n/", $head);
        foreach ($headerLines as $line) {
            if (!preg_match("/:/", $line))
                continue;
            list($key, $value) = explode(':', $line, 2);
            $responseHeaders[trim($key)] = trim($value);
        }

        curl_close($curl);

        if (isset($buffer) && is_resource($buffer)) {
            fclose($buffer);
        }
        return new Response($statusCode, $body, $responseHeaders);
    }
}
```

In this example, we are using some environment variables loaded at the program startup to retrieve various configuration settings:

- Your Reach ApiUser and ApiKey
- A proxy address in IP:Port form, e.g. `127.0.0.1:8888`

Place these setting in an `.env` file like so:

```env
API_USER=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
API_KEY= your_auth_token

PROXY=127.0.0.1:8888
```

Here’s the full console program that loads the `.env` file and sends a text message to show everything fitting together.

```php
<?php
// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once "./vendor/autoload.php";
require_once "./MyRequestClass.php";

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

use Reach\Rest\Client;

// Your ApiUser and ApiKey Token from the web application
$apiUser = getenv('API_USER');
$apiKey = getenv('API_KEY');
$proxy = getenv('PROXY');

$httpClient = new MyRequestClass($proxy);
$reach = new Client($apiUser, $apiKey, $httpClient);

$message = $reach->messaging->messagingItems
    ->send(
        "+15558675310", "+15017122661", "Hey there!"
    );

print("Message SID: {$message->messageId}");
```

## What else can this technique be used for?

Now that you know how to inject your own httpClient into the Reach API request pipeline, you could use this technique to add custom HTTP headers and authorization to the requests (perhaps as required by an upstream proxy server).

You could also implement your own httpClient to mock the Reach API responses so your unit and integration tests can run without the need to make a connection to Reach.

We can’t wait to see what you build!
