<?php

/**
 * This code was generated by
 *  ___ ___   _   ___ _  _    _____ _   _    _  ___   ___      _   ___ ___      ___   _   ___     ___ ___ _  _ ___ ___    _ _____ ___  ___ 
 * | _ \ __| /_\ / __| || |__|_   _/_\ | |  | |/ | \ / / |    /_\ | _ ) __|___ / _ \ /_\ |_ _|__ / __| __| \| | __| _ \  /_\_   _/ _ \| _ \
 * |   / _| / _ \ (__| __ |___|| |/ _ \| |__| ' < \ V /| |__ / _ \| _ \__ \___| (_) / _ \ | |___| (_ | _|| .` | _||   / / _ \| || (_) |   /
 * |_|_\___/_/ \_\___|_||_|    |_/_/ \_\____|_|\_\ |_| |____/_/ \_\___/___/    \___/_/ \_\___|   \___|___|_|\_|___|_|_\/_/ \_\_| \___/|_|_\
 * 
 * Reach Authentix API
 * Reach Authentix API helps you easily integrate user authentification in your application. The authentification allows to verify that a user is indeed at the origin of a request from your application.  At the moment, the Reach Authentix API supports the following channels:    * SMS      * Email   We are continuously working to add additionnal channels. ## Base URL All endpoints described in this documentation are relative to the following base URL: ``` https://api.reach.talkylabs.com/rest/authentix/v1/ ```  The API is provided over HTTPS protocol to ensure data privacy.  ## API Authentication Requests made to the API must be authenticated. You need to provide the `ApiUser` and `ApiKey` associated with your applet. This information could be found in the settings of the applet. ```curl curl -X GET [BASE_URL]/configurations -H \"ApiUser:[Your_Api_User]\" -H \"ApiKey:[Your_Api_Key]\" ``` ## Reach Authentix API Workflow Three steps are needed in order to authenticate a given user using the Reach Authentix API. ### Step 1: Create an Authentix configuration A configuration is a set of settings used to define and send an authentication code to a user. This includes, for example: ```   - the length of the authentication code,    - the message template,    - and so on... ``` A configuaration could be created via the web application or directly using the Reach Authentix API. This step does not need to be performed every time one wants to use the Reach Authentix API. Indeed, once created, a configuartion could be used to authenticate several users in the future.    ### Step 2: Send an authentication code A configuration is used to send an authentication code via a selected channel to a user. For now, the supported channels are `sms`, and `email`. We are working hard to support additional channels. Newly created authentications will have a status of `awaiting`. ### Step 3: Verify the authentication code This step allows to verify that the code submitted by the user matched the one sent previously. If, there is a match, then the status of the authentication changes from `awaiting` to `passed`. Otherwise, the status remains `awaiting` until either it is verified or it expires. In the latter case, the status becomes `expired`.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Reach\Rest\Api\Authentix;

use Reach\Exceptions\ReachException;
use Reach\ListResource;
use Reach\Options;
use Reach\Stream;
use Reach\Values;
use Reach\Version;
use Reach\Serialize;


class ConfigurationItemList extends ListResource
    {
    /**
     * Construct the ConfigurationItemList
     *
     * @param Version $version Version that contains the resource
     */
    public function __construct(
        Version $version
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        ];

        $this->uri = '/authentix/v1/configurations';
    }

    /**
     * Create the ConfigurationItemInstance
     *
     * @param string $serviceName The name of the authentication service attached to this configuration. It can be up to 40 characters long.
     * @param array|Options $options Optional Arguments
     * @return ConfigurationItemInstance Created ConfigurationItemInstance
     * @throws ReachException When an HTTP error occurs.
     */
    public function create(string $serviceName, array $options = []): ConfigurationItemInstance
    {

        $options = new Values($options);

        $data = Values::of([
            'serviceName' =>
                $serviceName,
            'codeLength' =>
                $options['codeLength'],
            'allowCustomCode' =>
                Serialize::booleanToString($options['allowCustomCode']),
            'usedForDigitalPayment' =>
                Serialize::booleanToString($options['usedForDigitalPayment']),
            'defaultExpiryTime' =>
                $options['defaultExpiryTime'],
            'defaultMaxTrials' =>
                $options['defaultMaxTrials'],
            'defaultMaxControls' =>
                $options['defaultMaxControls'],
            'smtpSettingId' =>
                $options['smtpSettingId'],
            'emailTemplateId' =>
                $options['emailTemplateId'],
            'smsTemplateId' =>
                $options['smsTemplateId'],
        ]);

        $payload = $this->version->create('POST', $this->uri, [], $data);

        return new ConfigurationItemInstance(
            $this->version,
            $payload
        );
    }


    /**
     * Reads ConfigurationItemInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return ConfigurationItemInstance[] Array of results
     */
    public function read(int $limit = null, $pageSize = null): array
    {
        return \iterator_to_array($this->stream($limit, $pageSize), false);
    }

    /**
     * Streams ConfigurationItemInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return Stream stream of results
     */
    public function stream(int $limit = null, $pageSize = null): Stream
    {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Retrieve a single page of ConfigurationItemInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 20
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return ConfigurationItemPage Page of ConfigurationItemInstance
     */
    public function page(
        $pageSize = Values::NONE,
        $pageNumber = Values::NONE
    ): ConfigurationItemPage
    {

        $params = Values::of([
            'page' => $pageNumber,
            'pageSize' => $pageSize,
        ]);
        

        $baseUrl = $this->version->urlWithoutPaginationInfo($this->version->absoluteUrl($this->uri), $params);
        $response = $this->version->page('GET', $this->uri, $params);

        return new ConfigurationItemPage($baseUrl, $this->version, $response, $this->solution);
    }

    /**
     * Retrieve a specific page of ConfigurationItemInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return ConfigurationItemPage Page of ConfigurationItemInstance
     */
    public function getPage(string $targetUrl): ConfigurationItemPage
    {
        $baseUrl = $this->version->urlWithoutPaginationInfo($targetUrl);
        $response = $this->version->getDomain()->getClient()->request(
            'GET',
            $targetUrl
        );

        return new ConfigurationItemPage($baseUrl, $this->version, $response, $this->solution);
    }


    /**
     * Constructs a ConfigurationItemContext
     *
     * @param string $configurationId The identifier of the configuration to be deleted.
     */
    public function getContext(
        string $configurationId
        
    ): ConfigurationItemContext
    {
        return new ConfigurationItemContext(
            $this->version,
            $configurationId
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Reach.Api.Authentix.ConfigurationItemList]';
    }
}
