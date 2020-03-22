<?php

namespace MyApp;

class Model {
  protected $db;

  // データベースへの接続
  public function __construct() {
    try {
      $this->db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      // 強制終了
      exit;
    }
  }
}