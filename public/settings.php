<?php

spl_autoload_register(function($class) {
	$paths = array(
		"../application/model",
		"../application/control",
		"../vendor/ufds/libdatabase/dbobject",
		"../vendor/ufds/libtypes/types",
		"../vendor/ufds/libssoclient/client",
		"../vendor/ufds/libutil/config",
		"../vendor/ufds/libutil/di",
		"../vendor/ufds/libutil/log",
		"../vendor/ufds/libmath/math",
	);

	foreach($paths as $path) {
		$fullname = __DIR__.'/'.$path.'/'.$class.'.php';
		if (is_file($fullname)) {
			include($fullname);
			return true;
		}
	}
	return false;
});

date_default_timezone_set("Europe/Copenhagen");
openlog("ufds-kanban", LOG_PID | LOG_CONS, LOG_LOCAL0);

$dic = DiContainer::instance();
$dic->config = new Config2('/etc/ufds/kanban.ini');
$dic->log = Log::createFromConfig();
$dic->request = new Request();
$dic->sso = new SingleSignOnClient('ufds-kanban');
