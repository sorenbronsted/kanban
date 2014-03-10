<?php
require_once 'settings.php';

class Rest {
  
  public function run() {
    try {
      header('Content-type: application/json');
      
      $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
      $allowedMethods = array("get", "delete", "post");
      
      if (!in_array($requestMethod, $allowedMethods)) {
        throw ErrorException("Unsupported request method $requestMethod");
      }
      if (array_key_exists('_', $_REQUEST)) {
        unset($_REQUEST['_']);
      }
      $this->authorize();
      $restCtrl = new RestCtrl($_SERVER['REQUEST_URI'], $_REQUEST);
      $result = $restCtrl->$requestMethod();
      echo ($this->jsonEncode($result));
    }
    catch (ValidationException $e) {
      return json_encode(array("error" => $e->errors()));
    }
    catch (ApplicationException $e) {
      return json_encode(array("error" => $e->getMessage()));
    }
    catch(ErrorException $e) {
      syslog(LOG_ERR, $e->getMessage());
      $lines = $e->getTrace();
      foreach ($lines as $key => $line) {
        $class = "file";
        if (array_key_exists("class", $line)) {
          $class = $line['class'].$line['type'].$line['function'];
        }
        syslog(LOG_ERR, "$key: $class (".(isset($line['file']) ? $line['file'] : '').",".(isset($line['line']) ? $line['line'] : '').")");
      }
      header($_SERVER['SERVER_PROTOCOL']. " 500 ".$e->getMessage());
    }
    catch(RuntimeException $e) {
      syslog(LOG_ERR, $e->getMessage());
      header($_SERVER['SERVER_PROTOCOL']. " 500 ".$e->getMessage());
    }
  }
  
  private function authorize() {
    try {
      DiContainer::instance()->sso->challengeCookie("ufds-kanban");
    }
    catch (NotAuthorizedException $e) {
      throw new AccessDeniedException();
    }
  }
  
  private function jsonEncode($item) {
    $result = "";
    if (is_array($item)) {
      $result .= "[";
      foreach($item as $tmp) {
        if (strlen($result) > 1) {
          $result .= ",";
        }
        $result .= $this->jsonEncode($tmp);
      }
      $result .= "]";
    }
    else {
      if ($item instanceof ModelObject || $item instanceof DataWrapper) {
        return $item->jsonEncode();
      }
    }
    return $result;
  }
}

$rest = new Rest();
echo $rest->run();
