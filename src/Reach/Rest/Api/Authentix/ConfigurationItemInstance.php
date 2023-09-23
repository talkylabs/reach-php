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
use Reach\InstanceResource;
use Reach\Options;
use Reach\Values;
use Reach\Version;
use Reach\Deserialize;
use Reach\Rest\Api\Authentix\ConfigurationItem\AuthenticationControlItemList;
use Reach\Rest\Api\Authentix\ConfigurationItem\AuthenticationItemList;


/**
 * @property string $appletId
 * @property string $apiVersion
 * @property string $configurationId
 * @property string $serviceName
 * @property int $codeLength
 * @property bool $allowCustomCode
 * @property bool $usedForDigitalPayment
 * @property int $defaultExpiryTime
 * @property int $defaultMaxTrials
 * @property int $defaultMaxControls
 * @property string $smtpSettingId
 * @property string $emailTemplateId
 * @property string $smsTemplateId
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 */
class ConfigurationItemInstance extends InstanceResource
{
    protected $_authenticationControlItems;
    protected $_authenticationItems;

    /**
     * Initialize the ConfigurationItemInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $configurationId The identifier of the configuration to be deleted.
     */
    public function __construct(Version $version, array $payload, string $configurationId = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'appletId' => Values::array_get($payload, 'appletId'),
            'apiVersion' => Values::array_get($payload, 'apiVersion'),
            'configurationId' => Values::array_get($payload, 'configurationId'),
            'serviceName' => Values::array_get($payload, 'serviceName'),
            'codeLength' => Values::array_get($payload, 'codeLength'),
            'allowCustomCode' => Values::array_get($payload, 'allowCustomCode'),
            'usedForDigitalPayment' => Values::array_get($payload, 'usedForDigitalPayment'),
            'defaultExpiryTime' => Values::array_get($payload, 'defaultExpiryTime'),
            'defaultMaxTrials' => Values::array_get($payload, 'defaultMaxTrials'),
            'defaultMaxControls' => Values::array_get($payload, 'defaultMaxControls'),
            'smtpSettingId' => Values::array_get($payload, 'smtpSettingId'),
            'emailTemplateId' => Values::array_get($payload, 'emailTemplateId'),
            'smsTemplateId' => Values::array_get($payload, 'smsTemplateId'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'dateCreated')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'dateUpdated')),
        ];

        $this->solution = ['configurationId' => $configurationId ?: $this->properties['configurationId'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return ConfigurationItemContext Context for this ConfigurationItemInstance
     */
    protected function proxy(): ConfigurationItemContext
    {
        if (!$this->context) {
            $this->context = new ConfigurationItemContext(
                $this->version,
                $this->solution['configurationId']
            );
        }

        return $this->context;
    }

    /**
     * Delete the ConfigurationItemInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws ReachException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        return $this->proxy()->delete();
    }

    /**
     * Fetch the ConfigurationItemInstance
     *
     * @return ConfigurationItemInstance Fetched ConfigurationItemInstance
     * @throws ReachException When an HTTP error occurs.
     */
    public function fetch(): ConfigurationItemInstance
    {

        return $this->proxy()->fetch();
    }

    /**
     * Update the ConfigurationItemInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ConfigurationItemInstance Updated ConfigurationItemInstance
     * @throws ReachException When an HTTP error occurs.
     */
    public function update(array $options = []): ConfigurationItemInstance
    {

        return $this->proxy()->update($options);
    }

    /**
     * Access the authenticationControlItems
     */
    protected function getAuthenticationControlItems(): AuthenticationControlItemList
    {
        return $this->proxy()->authenticationControlItems;
    }

    /**
     * Access the authenticationItems
     */
    protected function getAuthenticationItems(): AuthenticationItemList
    {
        return $this->proxy()->authenticationItems;
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
        return '[Reach.Api.Authentix.ConfigurationItemInstance ' . \implode(' ', $context) . ']';
    }
}
