<?php

$loader = require dirname(__DIR__).'/vendor/autoload.php'; // Use composer autoloading

date_default_timezone_set("Europe/Copenhagen");
openlog("ufds-kanban", LOG_PID | LOG_CONS, LOG_LOCAL0);

$dic = DiContainer::instance();
$dic->config = new Config2('/etc/ufds/kanban.ini');
$dic->log = Log::createFromConfig();
$dic->request = new Request();
$dic->sso = new SingleSignOnClient('ufds-kanban');
$dic->header = new Header();
