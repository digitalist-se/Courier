<?php
/**
 * Courier, a plugin for Matomo.
 *
 * @link https://digitalist.se
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\Db;
use Exception;
use Piwik\Common;
use Piwik\Plugin;

class Courier extends Plugin
{


    public function install()
    {
          // Creates the table that holds the results of the feedback.
        try {
            $sql = "CREATE TABLE " . Common::prefixTable('courier_integration') . " (
                            id INT NOT NULL AUTO_INCREMENT ,
                            type VARCHAR( 256 ) NOT NULL ,
                            name VARCHAR( 256 ) NOT NULL ,
                            integration BLOB ,
                            date TIMESTAMP ,
                            PRIMARY KEY ( id )
                        )  DEFAULT CHARSET=utf8 ";
            Db::exec($sql);
        } catch (Exception $e) {
          // ignore error if table already exists (1050 code is for 'table already exists')
            if (!Db::get()->isErrNo($e, '1050')) {
                  throw $e;
            }
        }
    }

    public function uninstall()
    {
        Db::dropTables(Common::prefixTable('courier_integration'));
    }

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
