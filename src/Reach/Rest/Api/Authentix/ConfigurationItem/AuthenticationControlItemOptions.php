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

use Reach\Options;
use Reach\Values;

abstract class AuthenticationControlItemOptions
{
    /**
     * @param string $dest The phone number or email being authenticated. Phone numbers must be in E.164 format. Either this parameter or the `authenticationId` must be specified.
     * @param string $code The 4-10 character string being verified. This is required for `sms` and `email` channels.
     * @param string $authenticationId The ID of the authentication being checked. Either this parameter or the to `dest` must be specified.
     * @param string $paymentInfo Information related to the digital payment to authenticate. It is required when `usedForDigitalPayment` is true. It is ignored otherwise. It is a stringfied JSON map where keys are `payee`, `amount`, and `currency` and the associated values are respectively the payee, the amount, and the currency of a financial transaction.
     * @return CheckAuthenticationControlItemOptions Options builder
     */
    public static function check(
        
        string $dest = Values::NONE,
        string $code = Values::NONE,
        string $authenticationId = Values::NONE,
        string $paymentInfo = Values::NONE

    ): CheckAuthenticationControlItemOptions
    {
        return new CheckAuthenticationControlItemOptions(
            $dest,
            $code,
            $authenticationId,
            $paymentInfo
        );
    }

}

class CheckAuthenticationControlItemOptions extends Options
    {
    /**
     * @param string $dest The phone number or email being authenticated. Phone numbers must be in E.164 format. Either this parameter or the `authenticationId` must be specified.
     * @param string $code The 4-10 character string being verified. This is required for `sms` and `email` channels.
     * @param string $authenticationId The ID of the authentication being checked. Either this parameter or the to `dest` must be specified.
     * @param string $paymentInfo Information related to the digital payment to authenticate. It is required when `usedForDigitalPayment` is true. It is ignored otherwise. It is a stringfied JSON map where keys are `payee`, `amount`, and `currency` and the associated values are respectively the payee, the amount, and the currency of a financial transaction.
     */
    public function __construct(
        
        string $dest = Values::NONE,
        string $code = Values::NONE,
        string $authenticationId = Values::NONE,
        string $paymentInfo = Values::NONE

    ) {
        $this->options['dest'] = $dest;
        $this->options['code'] = $code;
        $this->options['authenticationId'] = $authenticationId;
        $this->options['paymentInfo'] = $paymentInfo;
    }

    /**
     * The phone number or email being authenticated. Phone numbers must be in E.164 format. Either this parameter or the `authenticationId` must be specified.
     *
     * @param string $dest The phone number or email being authenticated. Phone numbers must be in E.164 format. Either this parameter or the `authenticationId` must be specified.
     * @return $this Fluent Builder
     */
    public function setDest(string $dest): self
    {
        $this->options['dest'] = $dest;
        return $this;
    }

    /**
     * The 4-10 character string being verified. This is required for `sms` and `email` channels.
     *
     * @param string $code The 4-10 character string being verified. This is required for `sms` and `email` channels.
     * @return $this Fluent Builder
     */
    public function setCode(string $code): self
    {
        $this->options['code'] = $code;
        return $this;
    }

    /**
     * The ID of the authentication being checked. Either this parameter or the to `dest` must be specified.
     *
     * @param string $authenticationId The ID of the authentication being checked. Either this parameter or the to `dest` must be specified.
     * @return $this Fluent Builder
     */
    public function setAuthenticationId(string $authenticationId): self
    {
        $this->options['authenticationId'] = $authenticationId;
        return $this;
    }

    /**
     * Information related to the digital payment to authenticate. It is required when `usedForDigitalPayment` is true. It is ignored otherwise. It is a stringfied JSON map where keys are `payee`, `amount`, and `currency` and the associated values are respectively the payee, the amount, and the currency of a financial transaction.
     *
     * @param string $paymentInfo Information related to the digital payment to authenticate. It is required when `usedForDigitalPayment` is true. It is ignored otherwise. It is a stringfied JSON map where keys are `payee`, `amount`, and `currency` and the associated values are respectively the payee, the amount, and the currency of a financial transaction.
     * @return $this Fluent Builder
     */
    public function setPaymentInfo(string $paymentInfo): self
    {
        $this->options['paymentInfo'] = $paymentInfo;
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
        return '[Reach.Api.Authentix.CheckAuthenticationControlItemOptions ' . $options . ']';
    }
}

