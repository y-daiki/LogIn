<?php

// 新規登録

require_once(__DIR__ . '/../config/config.php');

$app = new MyApp\Controller\Signup();

$app->run();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div id="container">
  <!-- フォームにsubmitのidのsignupをつける -->
    <form action="" method="post" id="signup">
      <p>
      <!-- getValues() で返ってくる stdClass() は存在しないプロパティにアクセスすると Notice が出てしまうので、isset() で調べてあげる。これがもしセットされていたらエスケープしつつ表示をしてあげて、そうでなかったら空文字 -->
        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">
      </p>

      <!-- email に関してのエラーはこちらで出力。Controller のエラーオブジェクトに入っているので、$app に入っている -->
      <p class="err"><?= h($app->getErrors('email')); ?></p>

      <p>
        <input type="password" name="password" placeholder="password">
      </p>

      <!-- password に関してのエラーはこちらで出力。Controller のエラーオブジェクトに入っているので、$app に入っている -->
      <p class="err"><?= h($app->getErrors('password')); ?></p>


      <!-- onclick：JavaScriptのイベントハンドラー -->
      
      <!-- Document の getElementById() メソッドは、 id プロパティが指定された文字列に一致する要素を表す Element オブジェクトを返す。ID を持たない要素にアクセスする必要がある場合は、 querySelector() で何らかのセレクターを使用して要素を検索することができる。 -->

      <!-- signupというid で、submitする -->
      <div class="btn" onclick="document.getElementById('signup').submit();">Sign Up</div>
      <p class="fs12"><a href="/login.php">Log In</a></p>

      <!-- 変なフォームから投稿されていないかチェックしたいので、Controller.phpで、セッションに token を仕込みつつ、Signup.phpで、フォームから渡された token と一致するか見てあげる。 -->
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    </form>
  </div>
</body>
</html>