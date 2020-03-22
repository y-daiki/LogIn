<?php

// ユーザーの一覧

require_once(__DIR__ . '/../config/config.php');

// var_dump($_SESSION['me']);

// コントローラーのインスタンス化
$app = new MyApp\Controller\Index();

$app->run();

// ログインしているユーザーの情報はよく使うと想定されるので、コントローラーに me() というメソッドを作ってあげて、$app->me() で取れるようにしている

// それから登録されているユーザーの一覧は $app->getValues()->users で取れるようにしている

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Home</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div id="container">
  <!-- 飛び先はlogout.php -->
    <form action="logout.php" method="post" id="logout">
    <!-- 今ログインしている人のメールアドレスを表示しておいて、ログアウトのためのボタンを配置 -->
      <?= h($app->me()->email); ?> <input type="submit" value="Log Out">
      <!-- token を仕込む -->
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    </form>

    <h1>Users <span class="fs12">(<?= count($app->getValues()->users); ?>)</span></h1>
    <ul>
    <!-- ユーザーの一覧を表示 -->
      <?php foreach ($app->getValues()->users as $user) : ?>
        <li><?= h($user->email); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>