<!DOCTYPE html>
<?php
    // データベースに接続
    $dsn = 'mysql:dbname=tb240012db;host=localhost';
    $user = 'tb-240012';
    $password = 'PmyrVfXX3R';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // テーブルが無かったら作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    // 送信時に編集番号が空の時
    if(empty($_POST["ed_num"]) && !empty($_POST["send"])){
        if(!empty($_POST["name"]) && !empty($_POST["form"]) && !empty($_POST["pass"])){
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $s_name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $s_comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $s_pass, PDO::PARAM_STR);
            $s_name = $_POST["name"];
            $s_comment = $_POST["form"];
            $date = date("Y/m/d H:i:s");
            $s_pass = $_POST["pass"];
            $sql -> execute();
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
            $ed_num = $_POST["ed_num"];
            $id = $ed_num; //変更する投稿番号
            $s_name = $_POST["name"];
            $s_comment = $_POST["form"]; //変更したい名前、変更したいコメントは自分で決めること
            $s_pass = $_POST["pass"];
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $s_name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $s_comment, PDO::PARAM_STR);
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
        $id = $_POST["delete"];
        $del_pass = $_POST["del_pass"];
        $sql = 'delete from tbtest where id=:id AND pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $del_pass, PDO::PARAM_STR);
        $stmt->execute();
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
        $edit = $_POST["edit"];
        $id = $edit;
        $ed_pass = $_POST["ed_pass"];
        $sql = 'SELECT* FROM tbtest WHERE id=:id AND pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $ed_pass, PDO::PARAM_STR);
        $res = $stmt->execute();
            if($res){
                $data = $stmt->fetch();
                $ed_name = $data["name"];
                $ed_comment = $data["comment"];
                $ed_pass = $data["pass"];
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
        <title>mission5-1</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h2>投稿フォーム</h2>
        <form action="" method="POST">
            <p>名前：<input type="text" name="name" placeholder="name" value="<?php if(isset($ed_name)){echo $ed_name;} ?>"></p>
            <p>コメント：<input type="text" name="form" placeholder="comment" value="<?php if(isset($ed_comment)){echo $ed_comment;}?>"></p>
            <p>パスワード：<input type="password" name="pass" placeholder="password" value="<?php if(isset($ed_pass)){echo $ed_pass;} ?>"></p>
            <p><input type="hidden" name = "ed_num" placeholder="編集対称番号" value="<?php if(isset($edit)){echo $edit;} ?>"></p>
            <p><input type="submit" name="send" value="送信する"></p>
        </form>
        <h2>削除フォーム</h2>
        <form action="" method="POST">
            <p>削除番号：<input type="number" name="delete" placeholder="delete number"></p>
            <p>パスワード：<input type="password" name="del_pass" placeholder="password"></p>
            <p><input type="submit" name="delete_sub" value="削除"></p>
        </form>
        <h2>編集フォーム</h2>
        <form action = "" method="POST">
            <p>編集番号：<input type= "number" name= "edit" placeholder = "edit number"> </p>
            <p>パスワード：<input type="password" name="ed_pass" placeholder="password"></p>
            <p><input type = "submit" name = "edit_sub" value = "編集"></p>
        </form>
        <?php if( !empty($error_message) ): ?>
        	<ul class="error_message">
        		<?php foreach( $error_message as $value ): ?>
        			<li><?php echo $value; ?></li>
        		<?php endforeach; ?>
        	</ul>
        <?php endif; ?>
        
        <?php
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].'<br>';
                echo '名前：'.$row['name'].'<br>';
                echo 'コメント：'.$row['comment'].'<br>';
                echo '日付：'.$row['date'].'<br>';
                // echo $row['pass'].'<br>'; //後で消す
            echo "<hr>";
            }
            
            //$sql = 'DROP TABLE tbtest';
            //$stmt = $pdo->query($sql);
        ?>
        
    </body>
</html>