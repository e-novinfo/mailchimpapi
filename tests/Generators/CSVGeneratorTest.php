<?php
/**
 * MailChimpApi - CSVGeneratorTest
 *
 * @since       16.12.2016
 *
 * @version     1.0.0.0
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

use \enovinfo\MailChimpApi\Generators\CSVGenerator as CSVGenerator;

class CSVGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /***************************/
    /********** SETUP **********/
    /***************************/
    
    protected $data;

    public function setUp()
    {
        $this->data = array( array( 'id' => 'foo', 'email_adress' => 'user@domaine.com', 'status' => 'subscribed', 'country_code' => 'CH', 'LNAME' => 'John', 'FNAME' => 'Do', 'MERGEDFIELD' => 'foo' ) );
        $this->mergeFields = array( array( 'merge_id' => 2, 'tag' => 'LNAME', 'name' => 'foo' ) );
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testGeneratorInstantiation()
    {
        $csvGenerator = new CSVGenerator($this->data, null);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Generators\CSVGenerator', $csvGenerator);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /***************************************/
    /********** TEST INVALID DATA **********/
    /***************************************/

    /**
     * @expectedException \Exception
     */
    
    public function testGeneratorInvalidData()
    {
        $csvGenerator = new CSVGenerator('foo', null);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*************************************/
    /********** TEST EMPTY DATA **********/
    /*************************************/

    /**
     * @expectedException \Exception
     */
    
    public function testGeneratorEmptyData()
    {
        $csvGenerator = new CSVGenerator('', null);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /***************************************************/
    /********** TEST DATA TO PARSE ASSIGNMENT **********/
    /***************************************************/
    
    public function testGeneratorDataToParseAssignement()
    {
        $csvGenerator = new CSVGenerator($this->data, null);
        $this->assertTrue(is_array($csvGenerator->getDataToParse()));
    }

    /*********************************************************************************/
    /*********************************************************************************/
    
    /**************************************************/
    /********** TEST MERGE FIELDS ASSIGNMENT **********/
    /**************************************************/
    
    public function testGeneratorMergeFieldsAssignement()
    {
        $csvGenerator = new CSVGenerator($this->data, $this->mergeFields);
        $this->assertTrue(is_array($csvGenerator->getDataToParse()));
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /********************************************************/
    /********** TEST MERGE FIELDS ASSIGNMENT FALIS **********/
    /********************************************************/
    
    public function testGeneratorMergeFieldsAssignementFails()
    {
        $csvGenerator = new CSVGenerator($this->data, null);
        $this->assertFalse(is_array($csvGenerator->getAdditionalFields()));
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /******************************************************************/
    /********** TEST MERGE FIELDS ASSIGNMENT FALIS NOT ARRAY **********/
    /******************************************************************/
    
    public function testGeneratorMergeFieldsAssignementFailsNotArray()
    {
        $csvGenerator = new CSVGenerator($this->data, 'foo');
        $this->assertFalse(is_array($csvGenerator->getAdditionalFields()));
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /******************************************/
    /********** TEST PROCESS SUCCEED **********/
    /******************************************/
    
    public function testGeneratorProcessSucceed()
    {
        $csvGenerator = new CSVGenerator($this->data, $this->mergeFields);
        $this->assertTrue($csvGenerator->process(false));
    }
}
