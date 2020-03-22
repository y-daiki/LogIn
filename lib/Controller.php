<?php

namespace MyApp;

class Controller {

  // エラー情報を格納するためのプライベードプロパティ
  private $_errors;
  // パスワードエラーが出た時にも、入力した email アドレスを残しておく
  private $_values;

  // 初期化するためのコンストラクタ
  public function __construct() {
    // もし $_SESSION['token'] がセットされていなかったらセットする。推測されにくい文字列にすれば良いのですが、最近は openssl_random_pseudo_bytes(16) という命令を使うのが一般的
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
    // stdClass() は、PHP デフォルトのクラスで宣言することなくいきなり new して使うことができる特殊なオブジェクト。オブジェクト型のデータをさっと作りたい時に便利
    $this->_errors = new \stdClass();
    $this->_values = new \stdClass();
  }

  // 継承させるので protected 
  protected function setValues($key, $value) {
    $this->_values->$key = $value;
  }

  // インスタンスから呼ぶので public
  public function getValues() {
    return $this->_values;
  }

  // 継承させるので protected 
  protected function setErrors($key, $error) {
    $this->_errors->$key = $error;
  }

  // インスタンスから呼ぶので public
  public function getErrors($key) {
    // もしセットされていたら $this->_errors->$key を返せば良いですし、そうでなかったら空文字を返す
    return isset($this->_errors->$key) ?  $this->_errors->$key : '';
  }

  // 継承させるので protected 
  protected function hasError() {
    // $this->_errors を調べてあげて、それが空でないか調べる
    // get_object_vars でプロパティを取得
    return !empty(get_object_vars($this->_errors));
  }

  protected function isLoggedIn() {
    // $_SESSION['me']：ログインした時にセッションにmeというキー情報を保持
    // もし $_SESSION['me'] がセットされていて、なおかつ空じゃなかったら
    return isset($_SESSION['me']) && !empty($_SESSION['me']);
  }

  public function me() {
    // もしログインしていたら $SESSION['me'] を返せば良いですし、そうでなければ null を返す
    return $this->isLoggedIn() ? $_SESSION['me'] : null;
  }
}