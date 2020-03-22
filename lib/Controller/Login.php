<?php

namespace MyApp\Controller;

class Login extends \MyApp\Controller {

  public function run() {
    if ($this->isLoggedIn()) {
      header('Location: ' . SITE_URL);
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->postProcess();
    }
  }

  protected function postProcess() {
    try {
      $this->_validate();
      // 何も入力されなかった場合に、EmptyPost という例外を返す。その場合は login というキーでセット
    } catch (\MyApp\Exception\EmptyPost $e) {
      $this->setErrors('login', $e->getMessage());
    }

    $this->setValues('email', $_POST['email']);

    if ($this->hasError()) {
      return;
    } else {
      try {
        $userModel = new \MyApp\Model\User();
        $user = $userModel->login([
          'email' => $_POST['email'],
          'password' => $_POST['password']
        ]);
        // password と email がマッチしない場合
      } catch (\MyApp\Exception\UnmatchEmailOrPassword $e) {
        $this->setErrors('login', $e->getMessage());
        return;
      }
      // login処理
      // セッションを管理する際にクッキーでセッション ID を保存していくのですが、それが特定されると困る。だから、session_regenerate_idを使い、現在のセッションIDを新しく生成したものと置き換える
      session_regenerate_id(true);
      // login 処理はユーザー情報が $_SESSION['me'] に入ったかどうかで判定
      $_SESSION['me'] = $user;

      // redirect to home
      header('Location: ' . SITE_URL);
      exit;
    }
  }

  private function _validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo "Invalid Token!";
      exit;
    }

    // $_POST['email'] と $_POST['password'] のキーが無かった場合
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
      echo "Invalid Form!";
      exit;
    }

    // $_POST['email'] が空文字、もしくは $_POST['password'] が空文字だった場合
    if ($_POST['email'] === '' || $_POST['password'] === '') {
      throw new \MyApp\Exception\EmptyPost();
    }
  }

}