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
    /********************************/
    /********** PROPERTIES **********/
    /********************************/
    
    public $verifySSL = true;

    private $apiKey;
    private $apiEndPoint;
    private $requestSuccess = false;
    private $response = array();
    private $formattedResponse;
        
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*******************************/
    /********** CONSTRUCT **********/
    /*******************************/

    public function __construct($apiKey)
    {
        $this->setApiKey($apiKey);
        $this->setEndPoint();
    }

    /*********************************************************************************/
    /*********************************************************************************/
    
    /****************************************/
    /********** GET REQUEST STATUS **********/
    /****************************************/
    
    public function getRequestSuccess() {
        return $this->requestSuccess;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**********************************/
    /********** GET RESPONSE **********/
    /**********************************/
    
    public function getResponse() {
        return $this->response;
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
        }
        
        $this->apiKey = $apiKey;
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
        }
        
        $this->apiEndPoint = 'https://'.$dc.'.api.mailchimp.com/3.0';
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**********************************/
    /********** SET RESPONSE **********/
    /**********************************/
    
    private function setResponse($response) 
    {
        $this->response = $response;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /********************************************/
    /********** SET FORMATTED RESPONSE **********/
    /********************************************/
    
    private function setFormattedResponse($response) 
    {
        $this->formattedResponse = $response;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*****************************************/
    /********** SET REQUEST SUCCESS **********/
    /*****************************************/
    
    private function setRequestSuccess($status = false) 
    {
        $this->requestSuccess = $status;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*************************/
    /********** GET **********/
    /*************************/
    
    public function get($action) 
    {
        $this->request('get', $action, $param = array(), $timeout = 10);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*****************************/
    /********** REQUEST **********/
    /*****************************/
    
    private function request($method, $action, $param = array(), $timeout = 10) 
    {
        
        if (!function_exists('curl_init') || !function_exists('curl_setopt')) {
            throw new \Exception("cURL support is required.");
        }
        
        if (empty($action)) {
            throw new \Exception('No action.');
        }
        
        $response = array();
        $url = $this->apiEndPoint . '/' . $action;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.api+json',
            'Content-Type: application/vnd.api+json',
            'Authorization: Basic ' . base64_encode( 'user:'. $this->apiKey )
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'e-novinfo/MailChimp-API/3.0 (github.com/e-novinfo/mailchimpapi)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        
        switch ($method) {
            case 'get':
                $query = http_build_query($param, '', '&');
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
                break;
            default:
                return false;
        }
        
        $response['body'] = curl_exec($ch);
        $response['headers'] = curl_getinfo($ch);
        
        curl_close($ch);
        
        $handledResponse = $this->handleResponse($response);
        
        $this->checkSuccess();
        
        return $handledResponse;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*************************************/
    /********** HANDLE RESPONSE **********/
    /*************************************/
    
    private function handleResponse($response) {
        
        if (!empty($response['body'])) {
            
            $formattedResponse = $this->formatResponse($response['body']);
            
            $this->setResponse($response);
            $this->setFormattedResponse($formattedResponse);
            
            return $formattedResponse;
            
        }
        
        return false;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*************************************/
    /********** FORMAT RESPONSE **********/
    /*************************************/
    
    private function formatResponse($response) 
    {
        
        $formattedResponse = json_decode($response, true);
        return $formattedResponse;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**********************************************/
    /********** CHECK IF REQUEST SUCCEED **********/
    /**********************************************/

    private function checkSuccess() 
    {
        
        $status = $this->findHTTPStatus();
            
        if ($status >= 200 && $status <= 299 && !empty($this->response['body'])) {
            $this->setRequestSuccess(true);
            return true;
        }
        
        if (empty($this->response['body'])) {
            $this->setRequestSuccess(false);
            return false;
        }
   
        $this->setRequestSuccess(false);
        return false;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**************************************/
    /********** FIND HTTP STATUS **********/
    /**************************************/
    
    private function findHTTPStatus() 
    {
        
        if (!empty($this->response['headers']['http_code'])) {
            return (int)$this->response['headers']['http_code'];
        }
        
        return 400;
    }

}
