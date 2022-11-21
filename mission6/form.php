<!DOCTYPE html>
<?php
    // データベースに接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // テーブルが無かったら作成
    $sql = "CREATE TABLE IF NOT EXISTS form_m6"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "url TEXT,"
    . "date DATETIME,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    // 送信時に編集番号が空の時
    if(empty($_POST["ed_num"]) && !empty($_POST["send"])){
        if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['pass'])) {
              $hash_pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            if(!empty($_POST["name"]) && !empty($_POST["form"]) && !empty($_POST["pass"])){
                
                
                $sql = $pdo -> prepare("INSERT INTO form_m6 (name, comment, url, date, pass) VALUES (:name, :comment, :url, :date, :pass)");
                $sql -> bindParam(':name', $s_name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $s_comment, PDO::PARAM_STR);
                $sql -> bindParam(':url', $s_url, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $s_pass, PDO::PARAM_STR);
                $s_name = $_POST["name"];
                $s_comment = $_POST["form"];
                $s_url = $_POST["url"];
                $date = date("Y/m/d H:i:s");
                $s_pass = $hash_pass;
                $sql -> execute();
            }
        }
        
        // 名前が空の時
        if(empty($_POST["name"])){
            $error_message[] = "名前が入力されていません";
        }
        // コメントが空の時
        if(empty($_POST["form"])){
            $error_message[] = "コメントが入力されていません";
        }
        // パスワードが空の時
        if(empty($_POST["pass"])){
            $error_message[] = "パスワードが入力されていません";
        }
        
    }else if(!empty($_POST["ed_num"]) && !empty($_POST["send"])){ //編集番号がある時
        if(!empty($_POST["name"]) && !empty($_POST["form"]) && !empty($_POST["pass"])){
            if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['pass'])) {
              $hash_pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }
            $ed_num = $_POST["ed_num"];
            $id = $ed_num; //変更する投稿番号
            $s_name = $_POST["name"];
            $s_comment = $_POST["form"]; //変更したい名前、変更したいコメントは自分で決めること
            $s_url = $_POST["url"];
            $s_pass = $hash_pass;
            $sql = 'UPDATE form_m6 SET name=:name,comment=:comment,url=:url,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $s_name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $s_comment, PDO::PARAM_STR);
            $stmt->bindParam(':url', $s_url, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $s_pass, PDO::PARAM_STR);
            $stmt->execute();
        }
        // 名前が空の時
        if(empty($_POST["name"])){
            $error_message[] = "名前が入力されていません";
        }
        // コメントが空の時
        if(empty($_POST["form"])){
            $error_message[] = "コメントが入力されていません";
        }
        // パスワードが空の時
        if(empty($_POST["pass"])){
            $error_message[] = "パスワードが入力されていません";
        }
    }
    
    // 削除ボタンを押したときの処理
    if(!empty($_POST["delete_sub"])){
        $stmt = $pdo->prepare('select * from form_m6 where id = ?');
        $stmt->execute([$_POST['delete']]);
        $row_url = $stmt->fetch(PDO::FETCH_ASSOC);
        $row_pass = $row_url["pass"];
        var_dump($row_pass);
        var_dump($_POST["del_pass"]);
        if (password_verify($_POST['del_pass'], $row_url['pass'])) {
            $id = $_POST["delete"];
            // $del_pass = $_POST["del_pass"];
            $sql = 'delete from form_m6 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            // $stmt->bindParam(':pass', $del_pass, PDO::PARAM_STR);
            $stmt->execute();
        }else{
            $error_message[] = "パスワードが間違っています";
        }
        // 削除番号が空の時
        if(empty($_POST["delete"])){
            $error_message[] = "削除番号が入力されていません";
        }
        // パスワードが空の時
        if(empty($_POST["del_pass"])){
            $error_message[] = "パスワードが入力されていません";
        }
    }
    
    // 編集ボタンを押したときの処理
    if(!empty($_POST["edit_sub"])){
        $stmt = $pdo->prepare('select * from form_m6 where id = ?');
        $stmt->execute([$_POST['edit']]);
        $row_url = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($_POST['ed_pass'], $row_url['pass'])) {
            $edit = $_POST["edit"];
            $id = $edit;
            $ed_pass = $_POST["ed_pass"];
            $sql = 'SELECT* FROM form_m6 WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            // $stmt->bindParam(':pass', $ed_pass, PDO::PARAM_STR);
            $res = $stmt->execute();
            if($res){
                $data = $stmt->fetch();
                $ed_name = $data["name"];
                $ed_comment = $data["comment"];
                $ed_url = $data["url"];
                
            }
        }else{
            $error_message[] = "パスワードが間違っています";
        }
        // 編集番号が空の時
        if(empty($_POST["edit"])){
            $error_message[] = "編集番号が入力されていません";
        }
        // パスワードが空の時
        if(empty($_POST["ed_pass"])){
            $error_message[] = "パスワードが入力されていません";
        }
    }
    
?>
<html lang="ja">
    <head>
        <title>プログラミングメモ</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="form_desing.css">
    </head>
    <body>
        <h2 style="text-align:center;margin-top: 0em;margin-bottom: 1em;">投稿フォーム</h2>
        <form action="" method="POST">
            <ul>
                <li class="name">
                    <label for="name">名前：</label>
                    <input type="text" name="name" placeholder="name" value="<?php if(isset($ed_name)){echo $ed_name;} ?>">
                </li>
                <li class="comment">
                    <label for="comment">タイトル：</label>
                    <input type="textarea" name="form" placeholder="comment" value="<?php if(isset($ed_comment)){echo $ed_comment;}?>">
                </li>
                <li class="url">
                    <label for="url">URL：</label>
                    <input type="text" name="url" placeholder="URL" value="<?php if(isset($ed_url)){echo $ed_url;}?>">
                </li>
                <li class="pass">
                    <label for="pass">パスワード：</label>
                    <input type="password" name="pass" placeholder="password" value="<?php if(isset($ed_pass)){echo $ed_pass;} ?>">
                </li>
                <p><input type="hidden" name = "ed_num" placeholder="編集対称番号" value="<?php if(isset($edit)){echo $edit;} ?>"></p>
                <li><input type="submit" name="send" value="送信する"></li>
            </ul>
        </form>
        <h2 style="text-align:center;margin-top: 0em;margin-bottom: 1em;">削除フォーム</h2>
        <form action="" method="POST">
            <ul>
                <li class="delete">
                    <label for="delete">削除番号：</label>
                    <input type="number" name="delete" placeholder="delete number">
                </li>
                <li class="del_pass">
                    <label for="del_pass">パスワード：</label>
                    <input type="password" name="del_pass" placeholder="password">
                </li>
                <li><input type="submit" name="delete_sub" value="削除"></li>
            </ul>
        </form>
        <h2 style="text-align:center;margin-top: 0em;margin-bottom: 1em;">編集フォーム</h2>
        <form action = "" method="POST">
            <ul>
                <li class="edit">
                    <label for="edit">編集番号：</label>
                    <input type= "number" name= "edit" placeholder = "edit number">
                </li>
                <li class="ed_pass">
                    <label for="ed_pass">パスワード：</label>
                    <input type="password" name="ed_pass" placeholder="password">
                </li>
                <li><input type = "submit" name = "edit_sub" value = "編集"></li>
            </ul>
        </form>
        <?php if( !empty($error_message) ): ?>
        	<ul class="error_message">
        		<?php foreach( $error_message as $value ): ?>
        			<li><?php echo $value; ?></li>
        		<?php endforeach; ?>
        	</ul>
        <?php endif; ?>
        
        <?php
            $sql = 'SELECT * FROM form_m6';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].'<br>';
                echo '名前    ：'.$row['name'].'<br>';
                echo 'タイトル：'.$row['comment'].'<br>';
                $url = $row['url'];
                $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/';
                $replace = '<a href="$1">$1</a>';
                $url    = preg_replace( $pattern, $replace, $url );
                echo 'URL   ：'.$url.'<br>';
                echo '日付  ：'.$row['date'].'<br>';
                // echo $row['pass'].'<br>'; //後で消す
            echo "<hr>";
            }
            
            //$sql = 'DROP TABLE tbtest';
            //$stmt = $pdo->query($sql);
        ?>
        
    </body>
</html>
