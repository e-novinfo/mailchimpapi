<?php
/**
 * MailChimpApi - MembersParser
 *
 * @since       14.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Parsers;

use enovinfo\MailChimpApi\Interfaces\Parser as Parser;

class MembersParser implements Parser
{
    
    /********************************/
    /********** PROPERTIES **********/
    /********************************/
    
    public $data;
    
    private $dataToParse;
    private $parsedData;
    private $readyToParse = false;
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*******************************/
    /********** CONSTRUCT **********/
    /*******************************/
    
    /*
     * @param Array $data Data to parse
     */

    public function __construct($data)
    {
        if ($this->checkReceivedData($data)) {
            $this->setDataToParse($data);
            $this->setReadyToParse(true);
        }
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*************************************/
    /********** GET PARSED DATA **********/
    /*************************************/
    
    /*
     * @return Array
     */
    
    public function getParsedData()
    {
        return $this->parsedData;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /***************************************/
    /********** SET DATA TO PARSE **********/
    /***************************************/
    
    /*
     * @param Array $dataToParse Data to parse
     * @return Void
     */
    
    private function setDataToParse($dataToParse)
    {
        return $this->dataToParse = $dataToParse;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*************************************/
    /********** SET PARSED DATA **********/
    /*************************************/
    
    /*
     * @param Array $parsedData Data parsed
     * @return Void
     */
    
    private function setParsedData($parsedData)
    {
        return $this->parsedData = $parsedData;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** SET READY TO PARSE **********/
    /****************************************/
    
    /*
     * @param Bool $state State
     * @return Void
     */
    
    private function setReadyToParse($state = false)
    {
        return $this->readyToParse = $state;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*****************************************/
    /********** CHECK RECIEVED DATA **********/
    /*****************************************/
    
    /*
     * @param Array $data Data to parse
     * @return Bool
     */
    
    public function checkReceivedData($data)
    {
        if (!empty($data) && is_array($data) && !empty($data['members'])) {
            return true;
        } else {
            throw new \Exception('Invalid data.');
        }
        
        return false;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** RETURN PARSED DATA **********/
    /***************************************/
    
    /*
     * @return Bool
     */
    
    public function parseData()
    {
        if ($this->readyToParse) {
            $this->setParsedData(array());
            return true;
        } else {
            throw new \Exception('Data can\'t be parsed.');
        }
        
        return false;
    }
}
