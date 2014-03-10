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
  "vendor/ufds/libutil/di/",
  "vendor/ufds/libmath/math/",
  "test/php/utils",
);

set_include_path(get_include_path().":".$cwd.implode(':'.$cwd, $paths));

spl_autoload_register(function($class) {
  require("$class.php");
});

DiContainer::instance()->sso = new TestSso();

?>