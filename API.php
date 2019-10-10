<?php
/**
 * Courier, a plugin for Matomo.
 *
 * @link https://digitalist.se
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\Common;
use Piwik\Db;
use Piwik\Piwik;
use Piwik\Plugins\CustomOptOut\SystemSettings;

/**
 * API for plugin Courier
 *
 * @method static API getInstance()
 */
class API extends \Piwik\Plugin\API
{

    public function getEndpoints()
    {

        Piwik::checkUserIsNotAnonymous();
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


    public function saveIntegration($type, $name, $integration)
    {
        Piwik::checkUserIsNotAnonymous();
        $created_date = date("Y-m-d H:i:s");
        $insert = "INSERT INTO " . Common::prefixTable('courier_integration') . " (type, name, integration, date) VALUES (?,?,?,?)";
        $values = [$type, $name, $integration, $created_date];
        Db::query($insert, $values);
    }
}
