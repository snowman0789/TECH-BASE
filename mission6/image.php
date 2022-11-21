<?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $id = rand(1, 5);
    try {
        $dbh = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
        $sql = "SELECT * FROM images WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $image = $stmt->fetch();
?>

<h1>画像表示</h1>
<img src="images/<?php echo $image['name']; ?>" width="300" height="300">
<a href="upload.php">画像アップロード</a>
