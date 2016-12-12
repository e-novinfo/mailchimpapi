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
        $MailChimpHttp = new MailChimpHttp('foo');
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
        $MailChimpHttp = new MailChimpHttp('foo');
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

        $MailChimpHttp = new MailChimpHttp($MC_API_KEY);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Http\MailChimpHttp', $MailChimpHttp);
    }
}
