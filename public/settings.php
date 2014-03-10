<?php
setlocale(LC_ALL, 'da_DK.utf8');

date_default_timezone_set("Europe/Copenhagen");
openlog("ufds-kanban", LOG_PID | LOG_CONS, LOG_LOCAL0);


$paths = array(
  "application",
  "application/model",
  "application/control",
  "vendor/ufds/libdatabase/dbobject",
  "vendor/ufds/libdatabase/types",
  "vendor/ufds/libssoclient/client",
  "vendor/ufds/libutil/di/",
  "vendor/ufds/libmath/math/",
);

$root = dirname(__DIR__).DIRECTORY_SEPARATOR;

set_include_path(get_include_path().":".$root.implode(':'.$root, $paths));

spl_autoload_register(function($class) {
  require("$class.php");
});

DiContainer::instance()->request = new Request();
DiContainer::instance()->sso = new SingleSignOnClient('ufds-kanban');
