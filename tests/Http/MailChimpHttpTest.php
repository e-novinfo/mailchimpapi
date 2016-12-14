<?php
/**
 * MailChimpApi - MailChimpHttpTest.
 *
 * @since       12.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */
use \enovinfo\MailChimpApi\Http\MailChimpHttp as MailChimpHttp;

class MailChimpHttpTest extends \PHPUnit_Framework_TestCase
{
    /***************************/
    /********** SETUP **********/
    /***************************/

    public function setUp()
    {
        $envFilePath = __DIR__.'/../../';

        if (file_exists($envFilePath.'.env')) {
            $dotenv = new Dotenv\Dotenv($envFilePath);
            $dotenv->load();
        }
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /******************************************/
    /********** TEST INVALID API KEY **********/
    /******************************************/

    /**
     * @expectedException \Exception
     */
    public function testInvalidAPIKey()
    {
        $mailChimpHttp = new MailChimpHttp('foo');
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /********************************************/
    /********** TEST EMPTY DATA CENTER **********/
    /********************************************/

    /**
     * @expectedException \Exception
     */
    public function testEmptyDataCenter()
    {
        $mailChimpHttp = new MailChimpHttp('fooapikey-');
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST ENVIRONNEMENT **********/
    /****************************************/

    public function testTestEnvironment()
    {
        $MC_API_KEY = getenv('MC_API_KEY');
        $message = 'No environment variables in file .env. Create the file .env like .env.example.';
        $this->assertNotEmpty($MC_API_KEY, $message);
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testInstantiation()
    {
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }

        $mailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Http\MailChimpHttp', $mailChimpHttp);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /******************************************/
    /********** TEST REQUEST SUCCESS **********/
    /******************************************/
    
    public function testRequestSuccess()
    {
        
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }
        
        $mailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $mailChimpHttp->verifySSL = false;
        
        $mailChimpHttp->get('lists');
        
        $this->assertTrue($mailChimpHttp->getRequestSuccess());
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /***************************************/
    /********** TEST REQUEST FAIL **********/
    /***************************************/
    
    public function testRequestFails()
    {
        
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }
        
        $mailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $mailChimpHttp->verifySSL = false;
        
        $mailChimpHttp->get('foo');

        $this->assertFalse($mailChimpHttp->getRequestSuccess());
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /***************************************************************/
    /********** TEST REQUEST FAIL BECAUSE ACTION IS EMPTY **********/
    /***************************************************************/
    
    /**
     * @expectedException \Exception
     */
    public function testRequestFailsActionEmpty()
    {
        
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }
        
        $mailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $mailChimpHttp->verifySSL = false;
        
        $mailChimpHttp->get('');
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /****************************************************/
    /********** TEST REQUEST RESPONSE IS ARRAY **********/
    /****************************************************/
    
    public function testRequestResponseIsArray()
    {
        
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }
        
        $mailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $mailChimpHttp->verifySSL = false;
        
        $mailChimpHttp->get('lists');
        
        $this->assertTrue(is_array($mailChimpHttp->getResponse()));
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*********************************************/
    /********** TEST REQUEST STATUS 404 **********/
    /*********************************************/
    
    public function testRequestResponseIs404()
    {
        
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }
        
        $mailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $mailChimpHttp->verifySSL = false;
        
        $mailChimpHttp->get('foo');
        $response = $mailChimpHttp->getResponse();
        
        $this->assertEquals(404, $response['headers']['http_code']);
        
    }
    
}
