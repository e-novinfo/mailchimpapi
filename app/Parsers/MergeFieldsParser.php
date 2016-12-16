<?php
/**
 * MailChimpApi - MergeFieldsParser
 *
 * @since       16.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Parsers;

use enovinfo\MailChimpApi\Interfaces\Parser as Parser;

class MergeFieldsParser implements Parser
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
            $this->setDataToParse($data['merge_fields']);
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
        $this->dataToParse = $dataToParse;
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
        $this->parsedData = $parsedData;
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
        $this->readyToParse = $state;
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
        if (!empty($data) && is_array($data) && !empty($data['merge_fields'])) {
            return true;
        } else {
            throw new \Exception('Invalid data.');
        }
        
        return false;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /***********************************/
    /********** PARSE PROCESS **********/
    /***********************************/
    
    /*
     * @return Array
     */
    
    private function parseProcess() 
    {
        
        $parsedData = array();
            
        $i = 0;

        foreach ($this->dataToParse as $field) {

            $parsedData[$i] = array();

            if (!empty($field['merge_id'])) {
                $parsedData[$i]['merge_id'] = (int) $field['merge_id'];
            }
            
            if (!empty($field['tag'])) {
                $parsedData[$i]['tag'] = (string) $field['tag'];
            }

            if (!empty($field['name'])) {
                $parsedData[$i]['name'] = (string) $field['name'];
            }

            $i++;

        }
        
        return $parsedData;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** RETURN PARSED DATA **********/
    /****************************************/
    
    /*
     * @return Bool
     */
    
    public function parseData()
    {
        if ($this->readyToParse) {
            
            $parsedData = $this->parseProcess();
            $this->setParsedData($parsedData);
            return true;
            
        } else {
            throw new \Exception('Data can\'t be parsed.');
        }
        
        return false;
    }
}
