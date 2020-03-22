<?php

namespace MyApp\Model;

class User extends \MyApp\Model {

  public function create($values) {
    $stmt = $this->db->prepare("insert into users (email, password, created, modified) values (:email, :password, now(), now())");
    $res = $stmt->execute([
      ':email' => $values['email'],
      // PHP の password_hash() という命令でハッシュ化。PASSWORD_DEFAULT としてあげると、デフォルトのアルゴリズムでハッシュ化
      ':password' => password_hash($values['password'], PASSWORD_DEFAULT)
    ]);
    // email が重複していたら、email にはユニークキーが付いているので、$res に false が返ってくる
    if ($res === false) {
      throw new \MyApp\Exception\DuplicateEmail();
    }
  }

  public function login($values) {
    // ユーザーを取得
    $stmt = $this->db->prepare("select * from users where email = :email");
    // execute() する時に渡すデータは email だけ
    $stmt->execute([
      ':email' => $values['email']
    ]);
    // データはオブジェクト形式で取得したいので、FetchMode() を設定
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    $user = $stmt->fetch();

    // $user が存在しない場合
    if (empty($user)) {
      throw new \MyApp\Exception\UnmatchEmailOrPassword();
    }
    // password_hash() で作ったパスワードは password_verify() で検証できる。渡ってきたデータと、$user のパスワードを調べる。
    if (!password_verify($values['password'], $user->password)) {
      throw new \MyApp\Exception\UnmatchEmailOrPassword();
    }

    return $user;
  }

  public function findAll() {
    // prepare でなくて query で OK
    $stmt = $this->db->query("select * from users order by id");
    // fetchMode() をセットしつつ、fetchAll() したものをそのまま返せば OK
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    return $stmt->fetchAll();
  }
}