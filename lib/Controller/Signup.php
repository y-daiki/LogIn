<?php

namespace MyApp\Controller;

class Signup extends \MyApp\Controller {

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
    // validate （データの検証）
    try {
      $this->_validate();

      // InvalidEmail：例外クラス
      // Controller にエラーオブジェクトを持たせて、そこに値をセットしたり、そこから値を取得したりしてエラーメッセージを View に反映する
    } catch (\MyApp\Exception\InvalidEmail $e) {
      // email というキーにエラーメッセージを渡してあげる
      $this->setErrors('email', $e->getMessage());

      // InvalidPassword：例外クラス
    } catch (\MyApp\Exception\InvalidPassword $e) {
      // password というキーにエラーメッセージを渡してあげる
      $this->setErrors('password', $e->getMessage());
    }

    // $_values オブジェクトに email の値を入れる
    $this->setValues('email', $_POST['email']);

    // hasError() というメソッドを作ってあげて、そちらが true だった場合は処理を止めたいので return
    if ($this->hasError()) {
      return;
    } else {
      // create user
      try {
        // $userModel のインスタンスを作る
        $userModel = new \MyApp\Model\User();
        // $userModel->create() を呼んであげて、emailとpasswordを渡し、ユーザーを作る
        $userModel->create([
          'email' => $_POST['email'],
          'password' => $_POST['password']
        ]);
        // email が既に存在する場合
      } catch (\MyApp\Exception\DuplicateEmail $e) {
        $this->setErrors('email', $e->getMessage());
        // 処理をとめる
        return;
      }

      // ユーザーを作ったらログイン画面にリダイレクト
      header('Location: ' . SITE_URL . '/login.php');
      exit;
    }
  }

  // データを検証する関数
  private function _validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo "Invalid Token!";
      exit;
    }

    // 上手くいかない場合に例外クラスを返す
    // FILTER_VALIDATE_EMAIL（オプション）でemailを検証
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      throw new \MyApp\Exception\InvalidEmail();
    }

    // 上手くいかない場合に例外クラスを返す
    // PWが英数字だけかを正規表現で検証
    if (!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['password'])) {
      throw new \MyApp\Exception\InvalidPassword();
    }
  }

}