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


namespace Reach\Rest\Api\Authentix\ConfigurationItem;

use Reach\Exceptions\ReachException;
use Reach\InstanceResource;
use Reach\Values;
use Reach\Version;
use Reach\Deserialize;


/**
 * @property string $appletId
 * @property string $apiVersion
 * @property string $configurationId
 * @property string $authenticationId
 * @property string $status
 * @property string $dest
 * @property string $channel
 * @property int $expiryTime
 * @property int $maxTrials
 * @property int $maxControls
 * @property string $paymentInfo
 * @property string[] $trials
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 */
class AuthenticationItemInstance extends InstanceResource
{
    /**
     * Initialize the AuthenticationItemInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $configurationId The identifier of the configuration being used.
     * @param string $authenticationId The identifier of the authentication to be fetched.
     */
    public function __construct(Version $version, array $payload, string $configurationId, string $authenticationId = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'appletId' => Values::array_get($payload, 'appletId'),
            'apiVersion' => Values::array_get($payload, 'apiVersion'),
            'configurationId' => Values::array_get($payload, 'configurationId'),
            'authenticationId' => Values::array_get($payload, 'authenticationId'),
            'status' => Values::array_get($payload, 'status'),
            'dest' => Values::array_get($payload, 'dest'),
            'channel' => Values::array_get($payload, 'channel'),
            'expiryTime' => Values::array_get($payload, 'expiryTime'),
            'maxTrials' => Values::array_get($payload, 'maxTrials'),
            'maxControls' => Values::array_get($payload, 'maxControls'),
            'paymentInfo' => Values::array_get($payload, 'paymentInfo'),
            'trials' => Values::array_get($payload, 'trials'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'dateCreated')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'dateUpdated')),
        ];

        $this->solution = ['configurationId' => $configurationId, 'authenticationId' => $authenticationId ?: $this->properties['authenticationId'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return AuthenticationItemContext Context for this AuthenticationItemInstance
     */
    protected function proxy(): AuthenticationItemContext
    {
        if (!$this->context) {
            $this->context = new AuthenticationItemContext(
                $this->version,
                $this->solution['configurationId'],
                $this->solution['authenticationId']
            );
        }

        return $this->context;
    }

    /**
     * Fetch the AuthenticationItemInstance
     *
     * @return AuthenticationItemInstance Fetched AuthenticationItemInstance
     * @throws ReachException When an HTTP error occurs.
     */
    public function fetch(): AuthenticationItemInstance
    {

        return $this->proxy()->fetch();
    }

    /**
     * Update the AuthenticationItemInstance
     *
     * @param string $status The new status of the authentication.
     * @return AuthenticationItemInstance Updated AuthenticationItemInstance
     * @throws ReachException When an HTTP error occurs.
     */
    public function update(string $status): AuthenticationItemInstance
    {

        return $this->proxy()->update($status);
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws ReachException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new ReachException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Reach.Api.Authentix.AuthenticationItemInstance ' . \implode(' ', $context) . ']';
    }
}

