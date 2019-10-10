<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

class Courier extends \Piwik\Plugin
{
    public function registerEvents()
    {
        return [
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
        ];
    }

    public function getStylesheetFiles(&$files)
    {
        $files[] = "plugins/Courier/assets/stylesheets/courier.css";
    }

}
