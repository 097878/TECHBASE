<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
 
    <?php
        date_default_timezone_set("asia/tokyo");
        $name=filter_input(INPUT_POST,"name");
        $comment=filter_input(INPUT_POST,"comment");
        $pass=filter_input(INPUT_POST,"pass");
        $delnum=filter_input(INPUT_POST,"delnum");
        $editnum=filter_input(INPUT_POST,"editnum");
        $subeditnum=filter_input(INPUT_POST,"subeditnum");
          $date = date("Y/m/d H:i:s");
         $dsn='mysql:dbname=データベース名;host=localhost';
        $user= 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
            ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date DATETIME"
        .");";
            $stmt = $pdo->query($sql);
           //テーブル作成 
           $results =$pdo->query($sql); 
  
  
  //コメント
 if (isset($_POST['submit']) && !empty($name && $comment) && $pass==$password){
             $sql = $pdo -> prepare("INSERT INTO tbtest (name,comment,date) VALUES (:name,:comment,:date)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR); 
             $sql -> bindParam(':date', $date, PDO::PARAM_STR);
              //データ挿入
            
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $pass=$_POST["pass"];
             $sql->execute();
             
              $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results=$stmt->fetchAll(); 
 }
             
             
         
        //削除
         elseif(isset($_POST['delete']) && !empty($delnum && $pass)){
           $delnum=$_POST["delnum"];
            $pass=$_POST["pass"];
            $id=$delnum;//削除番号指定
            $sql = 'delete from tbtest where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results=$stmt->fetchAll();
           //データ削除
        }
         
         //編集
         elseif(isset($_POST['edit']) && !empty($editnum && $pass)){
            $editnum=$_POST["editnum"];
            $pass=$_POST["pass"];
            $id=$editnum;
            $subeditnum=$editnum;
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results=$stmt->fetchAll();
           
        }
            
        elseif(isset($_POST['edit']) && !empty($name && $comment && $subeditnum)){
            $subeditnum=$_POST["subeditnum"];
            $id=$subeditnum;
            $name=$_POST["name"];
            $date = date("Y/m/d H:i:s");
            $comment=$_POST["comment"];
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results=$stmt->fetchAll();
            
        }  ?>
       パスワードは必ず入れてください。
   <form action="" method="post">
        <input type="text" name="name"placeholder="名前" value=<?php if(isset($_POST['edit']) && !empty($editnum && $pass )){
            foreach($results as $row) //rowで並べる
            {
                if($row['id']==$editnum){
                    echo $row['name'];
                }
            }
        }?>>
        <input type="text" name="comment"placeholder="コメント" value=<?php if(isset($_POST['edit']) && !empty($editnum && $pass)){ 
        foreach($results as $row)
        {
                if($row['id']==$editnum){
                    echo $row['comment'];
                }
            }
        }?>>
        <input type="text" name="pass" placeholder="パスワード">
      <input type="hidden" name="subeditnum" value=<?php if(isset($_POST['edit']) && !empty($editnum && $pass )){ echo $editnum; }?>>
        <input type="submit" name="submit" ><br>
    
    
        <input type="number" name="delnum" placeholder="削除対象番号">
        <input type="submit" name="delete" value="削除"><br>
  
        <input type="number" name = "editnum" placeholder="編集番号指定">
        <input type="submit" name="edit" value = "編集">
    </form>  
       
        <?php 
        //書き込み
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results=$stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
            echo "<hr>";
            }
    ?>
</body>
</html>