<?php
/**
 * MailChimpApi - ParserInterface
 *
 * @since       14.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Interfaces;

interface Parser
{
    public function getParsedData();
    public function checkReceivedData($data);
    public function parseData();
}
