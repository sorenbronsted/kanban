<?php

require_once "application/Config.php";

date_default_timezone_set("Europe/Copenhagen");

//----------------------------
// DATABASE CONFIGURATION
//----------------------------
return array(
  'db' => array(
      'development' => array(
        'type'      => Config::dbDriver,
        'host'      => Config::dbHost,
        'port'      => 3306,
        'database'  => Config::dbName,
        'user'      => Config::dbUser,
        'password'  => Config::dbPassword,
        'charset'   => Config::dbCharset
      ),
    ),
  'ruckusing_base' => dirname(__FILE__) . '/vendor/ruckusing/ruckusing-migrations',
  'migrations_dir' => RUCKUSING_WORKING_BASE . '/database',
  'db_dir' => RUCKUSING_WORKING_BASE . '/db',
  'log_dir' => '/tmp/kanban/logs',
);

?>