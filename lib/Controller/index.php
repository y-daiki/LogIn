<?php

namespace MyApp\Controller;

class Index extends \MyApp\Controller {

  public function run() {
    if (!$this->isLoggedIn()) {
      // login
      header('Location: ' . SITE_URL . '/login.php');
      exit;
    }

    // 登録しているユーザーの一覧を取得
    // ユーザーモデルのインスタンス化
    $userModel = new \MyApp\Model\User();
    // findAll()というメソッドをUserモデルで作り、登録しているユーザーの情報を引っ張ってくる
    $this->setValues('users', $userModel->findAll());
  }

}