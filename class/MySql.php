<?php

  class MySql {
    public static function connect() {
      $pdo = new PDO('mysql:host=localhost;dbname=cms_avancado', 'root', '');
      return $pdo;
    }
  }

?>