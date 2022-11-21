<?php
 
require_once('dbconect.php');
 
session_start();
//メールアドレスのバリデーション
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。<br>';
?>

  <a href ="top.php"><input type=button value=戻る></a>
<?php
  return false;
}

//DB内でPOSTされたメールアドレスを検索
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $stmt = $pdo->prepare('select * from newform where email = ?');
  $stmt->execute([$_POST['email']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}
//emailがDB内に存在しているか確認
if (!isset($row['email'])) {
  echo 'メールアドレス又はパスワードが間違っています。<br>';
?>
  <a href ="top.php"><input type=button value=戻る></a>
<?php  
  return false;
}
//パスワード確認後sessionにメールアドレスを渡す
if (password_verify($_POST['pass'], $row['password'])) {
  session_regenerate_id(true); //session_idを新しく生成し、置き換える
  $_SESSION['EMAIL'] = $row['email'];
  echo "ログイン完了しました。<br>";
  echo "※約3秒後にページを移動します。";

?>
    <meta http-equiv="refresh" content=" 3; url=./select.php">
<!--<script type="text/javascript">-->
<!--  history.go(-3);-->
<!--</script>-->
<?php
} else {
  echo 'メールアドレス又はパスワードが間違っています。<br>';
?>
<a href ="top.php"><input type=button value=戻る></a>
<?php
  return false;
}
?>