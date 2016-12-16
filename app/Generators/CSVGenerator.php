<?php
/**
 * MailChimpApi - CSVGenerator
 *
 * @since       16.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Generators;

class CSVGenerator
{
    
    /********************************/
    /********** PROPERTIES **********/
    /********************************/
    
    public $data;
    public $mergeFields;
    
    private $delimter = ';';
    private $textSeparator = '"';
    private $replaceTextSeparator = "'";
    private $lineDelimiter = "\n";
    private $dataToParse;
    private $headersName;
    private $additionalFields;
    private $destinationFolder = "exports/";
    private $fileName = "export.csv";
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*******************************/
    /********** CONSTRUCT **********/
    /*******************************/
    
    /*
     * @param Array $data Data to parse
     */

    public function __construct($data, $mergeFields = null)
    {
    
        if ($this->checkData($data)) {
            $this->setDataToParse($data);
        }
        
        if ($this->checkMergeFields($mergeFields)) {
            $this->setAdditionalFields($mergeFields);
        }
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /***************************************/
    /********** GET DATA TO PARSE **********/
    /***************************************/
    
    /*
     * @return Array
     */
    
    public function getDataToParse()
    {
        return $this->dataToParse;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*******************************************/
    /********** GET ADDITIONAL FIELDS **********/
    /*******************************************/
    
    /*
     * @return Array
     */
    
    public function getAdditionalFields()
    {
        return $this->additionalFields;
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
        
    /*******************************************/
    /********** SET ADDITIONAL FIELDS **********/
    /*******************************************/
    
    /*
     * @param Array $additionalFields Additional fields
     * @return Void
     */
    
    private function setAdditionalFields($additionalFields)
    {
        $this->additionalFields = $additionalFields;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*********************************/
    /********** SET HEADERS **********/
    /*********************************/
    
    /*
     * @param Array $headers Headers
     * @return Void
     */
    
    private function setHeadersName($headers)
    {
        $this->headersName = $headers;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*********************************/
    /********** SET HEADERS **********/
    /*********************************/
    
    /*
     * @return Void
     */
    
    private function setFileName()
    {
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        
        $this->fileName = 'export_' . $day . $month . $year . '.csv';
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /********************************/
    /********** CHECK DATA **********/
    /********************************/
    
    /*
     * @param Array $data Data to parse
     * @return Bool
     */
    
    private function checkData($data)
    {
        if (!empty($data) && is_array($data)) {
            return true;
        } else {
            throw new \Exception('Invalid data.');
        }
        
        return false;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** CHECK MERGE FIELDS **********/
    /****************************************/
    
    /*
     * @param Array $mergeFields Additional fields
     * @return Bool
     */
    
    private function checkMergeFields($mergeFields)
    {
        if (!empty($mergeFields) && is_array($mergeFields)) {
            return true;
        }
        
        return false;
    }

    /*********************************************************************************/
    /*********************************************************************************/
        
    /*****************************/
    /********** PROCESS **********/
    /*****************************/
    
    /*
     * @param Bool $clean Clean the directory
     * @return Bool
     */
    
    public function process($clean = true)
    {
        
        if (($clean && $this->cleanDirectory()) || !$clean) {
        
            $this->setFileName();

            $headers = $this->prepareHeaders();
            $this->setHeadersName($headers);

            return $this->write();
            
        }
        
        return false;

    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*************************************/
    /********** PREPARE HEADERS **********/
    /*************************************/
    
    /*
     * @return Array
     */
    
    private function prepareHeaders()
    {
        
        $headers = array();
        
        foreach($this->dataToParse as $key => $value) {
            
            if (!in_array($key, $headers)) {
                array_push($headers, $key);
            }

        }
        
        if ($this->additionalFields) {
            
            foreach($this->additionalFields as $field) {
            
                if (!in_array($field['tag'], $headers)) {
                    array_push($headers, $field['tag']);
                }

            }
            
        }
        
        return $headers;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /***************************/
    /********** WRITE **********/
    /***************************/
    
    /*
     * @return Bool
     */
    
    private function write()
    {
        
        ob_start();
        
        $file = fopen($this->destinationFolder . $this->fileName, 'w+');
        
        $str = $this->convertData();
        
        $write = fwrite($file, $str);
        
        fclose($file);
        
        ob_get_clean();
        flush();
        
        if ($write) {
            return true;
        }
        
        return false;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**********************************/
    /********** CONVERT DATA **********/
    /**********************************/
    
    /*
     * @return String
     */
    
    private function convertData() 
    {
        
        $lines = array();
        
        $lines[0] = $this->convertLine($this->headersName);
        
        $i = 1;
        
        foreach ($this->dataToParse as $line) {
            $lines[$i] = $this->convertLine($line);
            $i++;
        }
        
        $imploded = implode($this->lineDelimiter, $lines);
        
        return $imploded;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**********************************/
    /********** CONVERT LINE **********/
    /**********************************/
    
    /*
     * @param Array $line Line to convert
     * @return String
     */
    
    private function convertLine($line) 
    {
        
        $csvLine = array();
        
        foreach ($line as $item) {
            $csvLine[] = $this->textSeparator . str_replace($this->textSeparator, $this->replaceTextSeparator, $item) . $this->textSeparator;
        }
        
        $imploded = implode($this->delimter, $csvLine);
        
        return $imploded;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*************************************/
    /********** CLEAN DIRECTORY **********/
    /*************************************/
    
    /*
     * @return Bool
     */
    
    private function cleanDirectory() {
        
        $dir = $this->destinationFolder;
        $dirHandle = opendir($dir);
        
        while ($file = readdir($dirHandle)) {
            if (!is_dir($file) && $file !== '.gitkeep') {
                $unlink = unlink($dir . $file);
                if (!$unlink) {
                    return false;
                }
            }
        }
        
        closedir($dirHandle);
        
        return $dirHandle;
        
    }
      
}
