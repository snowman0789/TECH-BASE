<?php
require_once('dbconect.php');
//データベースへ接続、テーブルがない場合は作成
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("create table if not exists form
        (id INT AUTO_INCREMENT PRIMARY KEY,
        name char(32),
        comment TEXT,
        date DATETIME,
        pass TEXT
        )");
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

    
    // テーブルが無かったら作成
    $sql = "CREATE TABLE IF NOT EXISTS newform"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "email varchar(191),"
    . "pass varchar(191),"
    . "created timestamp not null default current_timestamp"
    .");";
    // $stmt = $pdo->query($sql);


?>
<!DOCTYPE HTML>
    <html lang="ja">
        <head>
            <title>ログイン画面</title>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="top_design.css">
        </head>
        <body>
            <h1>ログイン画面</h1>
            
            <div style="display:inline-flex">
            <form action="login.php" method="POST">
                <h2 class="login">【ログイン】</h2>
                <p><input type="email" name="email" placeholder="メールアドレス"></p>
                <p><input type="password" name="pass" placeholder="パスワード"></p>
                <p><input type="submit" value="ログインする"></p>
            </form>
            <form action="register.php" method="POST">
                <h2 class="new">【新規登録】</h2>
                <p><input type="email" name="new_email" placeholder="メールアドレス"></p>
                <p><input type="password" name="new_pass" placeholder="パスワード"></p>
                <p><input type="password" name="new_pass_conf" placeholder="確認用パスワード"></p>
                <p><input type="submit" value="新規登録する"></p>
                <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
            </form>
            </div>
        </body>
    </html>
<?php
    /*
        $sql = 'SELECT * FROM newform';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].'<br>';
                echo '名前：'.$row['email'].'<br>';
                echo 'コメント：'.$row['password'].'<br>';
                echo '日付：'.$row['created'].'<br>';
                // echo $row['pass'].'<br>'; //後で消す
            echo "<hr>";
            }
            */
?>