<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\DataTable;
use Piwik\DataTable\Row;

/**
 * API for plugin Courier
 *
 * @method static \Piwik\Plugins\Courier\API getInstance()
 */
class API extends \Piwik\Plugin\API
{

    public function getEndpoints()
    {
        $endpoints = [
            'webhook' => [
                'name' => 'Webhook',
                'description' => 'A custom webhook URL. Deliver will be in json.',
            ],
            'slack' => [
                'name' => 'Slack',
                'description' => 'Post to Slack.',
            ],
            'zapier' => [
                'name' => 'Zapier',
                'description' => 'Integrate with Zapier.',
            ],
            'email' => [
                'name' => 'Email',
                'description' => 'Use email.',
            ],
        ];
        return $endpoints;
    }

}
