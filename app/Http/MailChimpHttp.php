<?php
/**
 * MailChimpApi - MailChimpHttp.
 *
 * @since       12.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Http;

class MailChimpHttp
{
    /*******************************/
    /********** CONSTRUCT **********/
    /*******************************/

    private $apiKey;
    private $apiEndPoint;

    public function __construct($apiKey)
    {
        $this->setApiKey($apiKey);
        $this->setEndPoint();
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /*********************************/
    /********** SET API KEY **********/
    /*********************************/

    private function setApiKey($apiKey)
    {
        if (strpos($apiKey, '-') === false) {
            throw new \Exception('Invalid MailChimp API key.');
        } else {
            $this->apiKey = $apiKey;
        }
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /***********************************/
    /********** SET END POINT **********/
    /***********************************/

    private function setEndPoint()
    {
        list($key, $dc) = explode('-', $this->apiKey);

        if (empty($dc)) {
            throw new \Exception('No data center.');
        } else {
            $this->apiEndPoint = 'https://'.$dc.'.api.mailchimp.com/3.0';
        }
    }
}
