<?php
require_once('dbconect.php');
//データベースへ接続、テーブルがない場合は作成
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("create table if not exists newform(
      id int not null auto_increment primary key,
      email varchar(255),
      password varchar(255),
      created timestamp not null default current_timestamp
    )");
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

// エラーメッセージ
$err = [];
//メールアドレスのバリデーション
if (!$email = filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL)) {
  $err[] = '入力された値が不正です。';
}
//正規表現でパスワードをバリデーション
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['new_pass'])) {
  $password = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
} else {
  $err[] = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
}

if($_POST["new_pass"] !== $_POST["new_pass_conf"]){
    $err[] = "確認用パスワードと異なっています";
}

if(count($err) === 0){
//データベース内のメールアドレスを取得
$stmt = $pdo->prepare("select email from newform where email = ?");
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
}
else{
?>
<?php
    foreach($err as $e){
        echo $e."<br>";
    }
?>
    <br>
    <a href="top.php"><input type=button value=戻る><a>
<?php        
    return false;
}

//データベース内のメールアドレスと重複していない場合、登録する。
if (!isset($row['email'])) {
  $stmt = $pdo->prepare("insert into newform(email, password) value(?, ?)");
  $stmt->execute([$email, $password]);
?>

<body id="log_body">
  <main class="main_log">
    <p>登録完了</p>
    <br>
    <a href="top.php"><input type=button value=戻る><a>

</body>

<?php
}else {
?>

<body id="log_body">
  <main class="main_log">
  <p>既に登録されたメールアドレスです</p>
  <h1 style="text-align:center;margin-top: 0em;margin-bottom: 1em;" class="h1_log">初めての方はこちら</h1>
  <form action="register.php" method="post" class="form_log">
    <!--<label for="email" class="label">メールアドレス</label><br>-->
    <input type="email" name="email" class="textbox un" placeholder="メールアドレス"><br>
    <!--<label for="password" class="label">パスワード</label><br>-->
    <input type="password" name="password" class="textbox pass" placeholder="パスワード"><br>
    <button type="submit" class="log_button">新規登録する</button>
    <p style="text-align:center;margin-top: 1.5em;">※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
  </form>
</main>
</body>
<?php
return false;
}
?>