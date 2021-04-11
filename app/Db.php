<?php

class Db {
  public $pdo;
  private static $instance=null;

  private function __construct($configArray) {
    $dbHost = $configArray['host'];
    $dbName = $configArray['db_name'];
    $dbUser = $configArray['username'];
    $dbPass = $configArray['password'];
    
    $this->pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPass);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    self::$instance = $this;
  }

  public static function getDb($configArray=[]) {
    if(empty(self::$instance)) {
      $newInst = new self($configArray);
      return $newInst;
    } else {
        return self::$instance;
      }
  }


}
