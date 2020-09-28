<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

    <?php

    //接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql='DROP TABLE mission5';

    //テーブル作り
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "comment TEXT,"
    . "date DATETIME"
	.");";
	$stmt = $pdo->query($sql);


    //投稿
    if(!empty($_POST["name"]) && !empty($_POST["str"]) && empty($_POST["num"]) && !empty($_POST["pass"])){


        //変数代入
	    $name = $_POST["name"];
        $comment = $_POST["str"];
        $date =  new DATETIME();
        $date = $date -> format('Y-m-d H:i:s');

        //INSERT
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date) VALUES (:name, :comment,:date)");

        //ここのbindParamは最初に指定したパラメータをどの変数に入れるか指定する
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);


        /*
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment) VALUES (:name, :comment)");

        //ここのbindParamは最初に指定したパラメータをどの変数に入れるか指定する
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        //$sql -> bindParam(':date', $date, PDO::PARAM_STR);
        */

	    $sql -> execute();
    }

    //削除の場合
    else if(!empty($_POST["del_num"]) && !empty($_POST["del_pass"])){

        //削除する番号の指定
        $id = $_POST["del_num"];

        //削除
	    $sql = 'delete from mission5 where id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    //編集の場合
    //編集番号の設定
    else if(!empty($_POST["edit_num"]) && !empty($_POST["edit_pass"])){

        //変数代入
        $id = $_POST["edit_num"];

        //データレコードの抽出
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();


        foreach ($results as $row){

            //$rowの中にはテーブルのカラム名が入る
            if($row['id']==$id){

                $data_num = $row['id'];
                $data_name = $row['name'];
                $data_str = $row['comment'];

            }

        }


    }


    //編集されたものの書き換え
    else if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["num"]) && !empty($_POST["pass"])){
        $id = $_POST["num"]; //変更する投稿番号
	    $name = $_POST["name"];
	    $comment = $_POST["str"]; //変更したい名前、変更したいコメントは自分で決めること
	    $sql = 'UPDATE mission5 SET name=:name,comment=:comment WHERE id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();
    }



    ?>

    <form action="mission5-1.php" method="post">
        【投稿フォーム】
        <br>
        名前：<input type="text" name="name"  placeholder = "名前" value="<?php if(!empty($data_name)) {echo $data_name;} ?>">
        <br>
        コメント：<input type="text" name="str" placeholder = "コメント" value="<?php if(!empty($data_str)) {echo $data_str;} ?>">
        <br>
        <input type="hidden" name="num" value="<?php if(!empty($data_num)!=NULL){echo $data_num;} ?>">
        パスワード：<input type="password" name="pass" placeholder="パスワード">
        <br>
        <input type="submit" value="送信">
        <br>
        <br>
        【削除フォーム】
        <br>
        削除番号：<input type="number" name="del_num" placeholder = "削除対象番号">
        <br>
        パスワード：<input type="password" name="del_pass" placeholder="パスワード">
        <br>
        <input type="submit" value="削除">
        <br>
        <br>
        【編集フォーム】
        <br>
        編集番号：<input type="number" name="edit_num" placeholder="編集対象番号">
        <br>
        パスワード：<input type="password" name="edit_pass" placeholder="パスワード">
        <br>
        <input type="submit" value="編集">
    </form>


    <?php
       $sql = 'SELECT * FROM mission5';
       $stmt = $pdo->query($sql);
       $results = $stmt->fetchAll();
       foreach ($results as $row){
           //$rowの中にはテーブルのカラム名が入る
           echo $row['id'].',';
           echo $row['name'].',';
           echo $row['comment'].',';
           echo $row['date'].'<br>';
       echo "<hr>";
       }

    ?>



</body>
</html>