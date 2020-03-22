<?php
// ログアウト処理も Controller を作っても良いのですが、それほど複雑な処理でもないので、logout.php に直接書いている

require_once(__DIR__ . '/../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // tokenの検証
  // Controller がインスタンス化されないと $_SESSION['token'] がセットされないので、index.php の方でこちらを有効化する
  if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    echo "Invalid Token!";
    exit;
  }

  // セッションを空にする
  $_SESSION = [];

  // PHP ではセッションの管理にクッキーを使うので、そちらのクッキーも削除。クッキーの名前は session_name() で取れるので、このクッキーがもしセットされていたら削除する。名前が session_name() で、内容は空にしてあげて、そして有効期限を過去日付にしてあげれば削除ができる。
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 86400, '/');
  }

  session_destroy();

}

// index.php に飛ばす
header('Location: ' . SITE_URL);