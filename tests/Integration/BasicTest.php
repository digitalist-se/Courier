<?php

namespace Piwik\Plugins\Courier\tests\Integration;

use Piwik\Plugins\Courier\API;


/**
 * @group Courier
 * @group Plugins
 */
class BasicTest extends \PHPUnit_Framework_TestCase
{

    public function testAPI()
    {
       $api = new API();
       $integrations = $api->getIntegrations();
       $this->assertArrayHasKey('webhook',$integrations);
       $this->assertArrayHasKey('slack',$integrations);
        $this->assertArrayNotHasKey('foo',$integrations);
    }
}
