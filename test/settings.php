<?php
date_default_timezone_set("Europe/Copenhagen");
openlog("ufds", LOG_PID | LOG_CONS, LOG_LOCAL0);

$cwd = dirname(__DIR__).'/';

$paths = array(
  "application",
  "application/model",
  "application/control",
  "vendor/ufds/libdatabase/dbobject",
  "vendor/ufds/libdatabase/types",
  "vendor/ufds/libssoclient/client",
  "vendor/ufds/libutil/di",
  "vendor/ufds/libutil/config",
  "vendor/ufds/libutil/log",
  "vendor/ufds/libmath/math",
  "test/php/utils",
);

set_include_path(get_include_path().":".$cwd.implode(':'.$cwd, $paths));

spl_autoload_register(function($class) {
  require("$class.php");
});

$dic = DiContainer::instance();
$dic->config = new Config2('test/php/utils/kanban.ini');
$dic->log = Log::createFromConfig();
$dic->request = new Request();
$dic->sso = new TestSso;

?>