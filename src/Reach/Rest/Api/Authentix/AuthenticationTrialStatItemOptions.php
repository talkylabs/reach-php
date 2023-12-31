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

use Reach\Options;
use Reach\Values;

abstract class AuthenticationTrialStatItemOptions
{
    /**
     * @param string $dest Filter authentication trials sent only to this phone number or email. The phone number must be in the E.164 format.
     * @param string $trialStatus Filter authentication trials with the specified status.
     * @param string $channel Filter authentication trials sent via the specified channel.
     * @param string $configurationId Filter authentication trials from the configuration whose ID matches the specified one.
     * @param string $authenticationId Filter authentication trials from the authentication whose ID matches the specified one.
     * @param string $country Filter authentication trials sent to the specified destination country (in ISO 3166-1 alpha-2). Only possible when `dest` is a phone number.
     * @param \DateTime $sentAt Filter authentication trials created at the specified date. Must be in ISO 8601 format.
     * @param \DateTime $sentAfter Filter authentication trials created after the specified datetime. Must be in ISO 8601 format.
     * @param \DateTime $sentBefore Filter authentication trials created before the specified datetime. Must be in ISO 8601 format.
     * @return FetchAuthenticationTrialStatItemOptions Options builder
     */
    public static function fetch(
        
        string $dest = Values::NONE,
        string $trialStatus = Values::NONE,
        string $channel = Values::NONE,
        string $configurationId = Values::NONE,
        string $authenticationId = Values::NONE,
        string $country = Values::NONE,
        \DateTime $sentAt = null,
        \DateTime $sentAfter = null,
        \DateTime $sentBefore = null

    ): FetchAuthenticationTrialStatItemOptions
    {
        return new FetchAuthenticationTrialStatItemOptions(
            $dest,
            $trialStatus,
            $channel,
            $configurationId,
            $authenticationId,
            $country,
            $sentAt,
            $sentAfter,
            $sentBefore
        );
    }

}

class FetchAuthenticationTrialStatItemOptions extends Options
    {
    /**
     * @param string $dest Filter authentication trials sent only to this phone number or email. The phone number must be in the E.164 format.
     * @param string $trialStatus Filter authentication trials with the specified status.
     * @param string $channel Filter authentication trials sent via the specified channel.
     * @param string $configurationId Filter authentication trials from the configuration whose ID matches the specified one.
     * @param string $authenticationId Filter authentication trials from the authentication whose ID matches the specified one.
     * @param string $country Filter authentication trials sent to the specified destination country (in ISO 3166-1 alpha-2). Only possible when `dest` is a phone number.
     * @param \DateTime $sentAt Filter authentication trials created at the specified date. Must be in ISO 8601 format.
     * @param \DateTime $sentAfter Filter authentication trials created after the specified datetime. Must be in ISO 8601 format.
     * @param \DateTime $sentBefore Filter authentication trials created before the specified datetime. Must be in ISO 8601 format.
     */
    public function __construct(
        
        string $dest = Values::NONE,
        string $trialStatus = Values::NONE,
        string $channel = Values::NONE,
        string $configurationId = Values::NONE,
        string $authenticationId = Values::NONE,
        string $country = Values::NONE,
        \DateTime $sentAt = null,
        \DateTime $sentAfter = null,
        \DateTime $sentBefore = null

    ) {
        $this->options['dest'] = $dest;
        $this->options['trialStatus'] = $trialStatus;
        $this->options['channel'] = $channel;
        $this->options['configurationId'] = $configurationId;
        $this->options['authenticationId'] = $authenticationId;
        $this->options['country'] = $country;
        $this->options['sentAt'] = $sentAt;
        $this->options['sentAfter'] = $sentAfter;
        $this->options['sentBefore'] = $sentBefore;
    }

    /**
     * Filter authentication trials sent only to this phone number or email. The phone number must be in the E.164 format.
     *
     * @param string $dest Filter authentication trials sent only to this phone number or email. The phone number must be in the E.164 format.
     * @return $this Fluent Builder
     */
    public function setDest(string $dest): self
    {
        $this->options['dest'] = $dest;
        return $this;
    }

    /**
     * Filter authentication trials with the specified status.
     *
     * @param string $trialStatus Filter authentication trials with the specified status.
     * @return $this Fluent Builder
     */
    public function setTrialStatus(string $trialStatus): self
    {
        $this->options['trialStatus'] = $trialStatus;
        return $this;
    }

    /**
     * Filter authentication trials sent via the specified channel.
     *
     * @param string $channel Filter authentication trials sent via the specified channel.
     * @return $this Fluent Builder
     */
    public function setChannel(string $channel): self
    {
        $this->options['channel'] = $channel;
        return $this;
    }

    /**
     * Filter authentication trials from the configuration whose ID matches the specified one.
     *
     * @param string $configurationId Filter authentication trials from the configuration whose ID matches the specified one.
     * @return $this Fluent Builder
     */
    public function setConfigurationId(string $configurationId): self
    {
        $this->options['configurationId'] = $configurationId;
        return $this;
    }

    /**
     * Filter authentication trials from the authentication whose ID matches the specified one.
     *
     * @param string $authenticationId Filter authentication trials from the authentication whose ID matches the specified one.
     * @return $this Fluent Builder
     */
    public function setAuthenticationId(string $authenticationId): self
    {
        $this->options['authenticationId'] = $authenticationId;
        return $this;
    }

    /**
     * Filter authentication trials sent to the specified destination country (in ISO 3166-1 alpha-2). Only possible when `dest` is a phone number.
     *
     * @param string $country Filter authentication trials sent to the specified destination country (in ISO 3166-1 alpha-2). Only possible when `dest` is a phone number.
     * @return $this Fluent Builder
     */
    public function setCountry(string $country): self
    {
        $this->options['country'] = $country;
        return $this;
    }

    /**
     * Filter authentication trials created at the specified date. Must be in ISO 8601 format.
     *
     * @param \DateTime $sentAt Filter authentication trials created at the specified date. Must be in ISO 8601 format.
     * @return $this Fluent Builder
     */
    public function setSentAt(\DateTime $sentAt): self
    {
        $this->options['sentAt'] = $sentAt;
        return $this;
    }

    /**
     * Filter authentication trials created after the specified datetime. Must be in ISO 8601 format.
     *
     * @param \DateTime $sentAfter Filter authentication trials created after the specified datetime. Must be in ISO 8601 format.
     * @return $this Fluent Builder
     */
    public function setSentAfter(\DateTime $sentAfter): self
    {
        $this->options['sentAfter'] = $sentAfter;
        return $this;
    }

    /**
     * Filter authentication trials created before the specified datetime. Must be in ISO 8601 format.
     *
     * @param \DateTime $sentBefore Filter authentication trials created before the specified datetime. Must be in ISO 8601 format.
     * @return $this Fluent Builder
     */
    public function setSentBefore(\DateTime $sentBefore): self
    {
        $this->options['sentBefore'] = $sentBefore;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Reach.Api.Authentix.FetchAuthenticationTrialStatItemOptions ' . $options . ']';
    }
}

