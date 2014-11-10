<?php
require_once 'settings.php';

try {
  DiContainer::instance()->sso->challenge("kanban");
}
catch(AccessDeniedException $e) {
  header('HTTP/1.0 403 Forbidden');
  include('unauthorized.html');
  exit;
}
catch(NotAuthorizedException $e) {
  header('WWW-Authenticate: Basic realm="UFDS Login"');
  header('HTTP/1.0 401 Unauthorized');
  include('unauthorized.html');
  exit;
}
catch(Exception $e) {
  print("SSO: ".$e->getMessage());
  exit;
}
User::sync();
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>UFDS - Kanban</title>
    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/views.css" />
    <link rel="stylesheet" href="css/application.css" />
    <link rel="stylesheet" href="css/tabs.css" />
    <link rel="stylesheet" href="css/tableheaders.css" />
  </head>
  <body>
    <div id="header">
      <div id="header-title">UFDS - Kanban</div>
      <div id="loader"><img src="images/ajax-loader.gif" /></div>
    </div>

		<div id="views">
			<div id="links">
				<a href="#Task">Opgaver</a> | 
				<a href="#Project">Projekt</a> | 
				<a href="#Status">Status</a>
			</div>
		</div>
    <div id="application">
      <div id="tabs">
      </div>
      <div id="content">
      </div>
    </div>

    <script type="application/dart" src="client/main.dart"></script>
    <script src="client/dart.js"></script>
  </body>
</html>
