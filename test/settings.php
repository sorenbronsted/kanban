<?php


$loader = require 'vendor/autoload.php'; // Use composer autoloading
$loader->addClassMap(array(
		'BaseCrud' => 'test/php/utils/BaseCrud.php',
		'Fixtures' => 'test/php/utils/Fixtures.php',
		'TestSso' => 'test/php/utils/TestSso.php',
));

date_default_timezone_set("Europe/Copenhagen");
openlog("ufds", LOG_PID | LOG_CONS, LOG_LOCAL0);

$dic = DiContainer::instance();
$dic->config = new Config2(__DIR__.'/kanban.ini');
$dic->log = Log::createFromConfig();
$dic->request = new Request();
$dic->sso = new TestSso();

?>