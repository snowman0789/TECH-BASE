<?php

function connect()
{
    try{
        // データベースに接続
        $dsn = 'mysql:dbname=tb240012db;host=localhost';
        $user = 'tb-240012';
        $password = 'PmyrVfXX3R';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    }catch(PDOExeption $e){
        echo "接続失敗です".$e->getMessage();
        exit();
    }

}

define('DSN', 'mysql:dbname=tb240012db;host=localhost');
define('DB_USER', 'tb-240012');
define('DB_PASS', 'PmyrVfXX3R');

?>