<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>好きな歴史上の人物</h1>
    <?php
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $table_name = "favorite_history_on_character";
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = "CREATE TABLE IF NOT EXISTS $table_name "
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date DATETIME,"
        . "password char(8)"
        .");";
        $stmt = $pdo->query($sql);
    ?>
    <?php
        $edit_name = "";
        $edit_comment = "";
        $edit_number = "";
        $edit_password = "";
        if(!empty($_POST["edit"])){
            $updatenumber = $_POST["edit"];
            $sql = "SELECT * FROM $table_name where id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id',$updatenumber, PDO::PARAM_INT);
            $stmt->execute(); 
            $data = $stmt->fetch();
            $edit_number = $data['id'];
            $edit_name = $data['name'];
            $edit_comment = $data['comment'];
            $edit_password = $data['password'];
        }
    ?>
<form action = "" method = "post">
    <label for = "name">【投稿フォーム】</label><br>
    <label>お名前：　　<input type = "text" name = "name" value = "<?php echo $edit_name; ?>"></label><br>
    <label>コメント：　<input type = "text" name ="comment" value = "<?php echo $edit_comment; ?>"></label><br>
    <input type = "hidden" name = "edit_statement" value = "<?php echo $edit_number; ?>"><br>
    <label>パスワード：<input type = "password" name = "password" value = "<?php echo $edit_password; ?>" minlength = "4" maxlength = "8"></label><br>
    <input type = "submit" name = "submit"><br>
    <label for = "delete">【削除フォーム】</label><br>
    <label>投稿番号：　<input type = "number" name = "delete"></label><br>
    <label>パスワード：<input type = "password" name = "del_pass" placeholder = "半角英数字4~8文字" minlength = "4" maxlength = "8"></label><br>
    <input type = "submit" name = "submit" value = "削除"><br>
    <label for = "edit">【編集フォーム】</label><br>
    <label>投稿番号：　<input type = "number" name = "edit"></label><br>
    <label>パスワード：<input type = "password" name = "edi_pass" placeholder = "半角英数字4~8文字" minlength = "4" maxlength = "8"></label><br>
    <input type = "submit" name = "submit" value ="編集">
</form>
<?php
     if(!empty($_POST["comment"])&& !empty($_POST["name"])&& !empty($_POST["password"])){
    //空だったら新規、空じゃなかったら編集　選別するためのコード
        if(!empty($_POST["edit_statement"])){
            $edit = $_POST["edit_statement"];
            $edi_pass = $_POST["edi_pass"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $password = $_POST["password"];
            $sql = "SELECT * FROM $table_name where id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id',$edit, PDO::PARAM_INT);
            $stmt->execute(); 
            $data = $stmt->fetch();
            $edit_password = $data['password'];
            if($edi_pass == $edit_password){
                $sql = "UPDATE $table_name SET name=:name,comment=:comment,date=NOW(),password=:password WHERE id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt-> bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
                $stmt->execute();
            }
        } else{
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $password = $_POST["password"];
            $sql = $pdo -> prepare("INSERT INTO $table_name (name, comment,date,password) VALUES (:name, :comment,NOW(),:password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $sql -> execute();
        }
    }

    if(!empty($_POST["delete"])&& !empty($_POST["del_pass"])){
        $delete = $_POST["delete"];
        $del_pass = $_POST["del_pass"];
        $sql = "SELECT * FROM $table_name where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id',$delete, PDO::PARAM_INT);
        $stmt->execute(); 
        $data = $stmt->fetch();
        $del_password = $data['password'];
        if($del_pass == $del_password){
            $sql = "delete from $table_name where id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
     $sql = "SELECT * FROM $table_name";
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
     foreach ($results as $row){
         echo $row['id'].',';
         echo $row['name'].',';
         echo $row['comment'].','; 
         echo $row['date'].'<br>';
           
     }
?>
</body>
</html>