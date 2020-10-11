<!DOCKTYPE html>
<html lang='ja'>
<head>
    <meta charset = 'utf-8'>
    <title>Mission_3-5</title>
</head>
    
<body>
    <h1>好きなアーティストについて</h1>
    <form action= '' method= 'post'>
        <input type = 'text' name = 'name' value= "名前">                                                
        <input type = 'text' name= 'text' value=  "コメント"> 
        <input type = 'password' name= 'password1' placeholder="パスワードを入力"> 
        
        <input type = 'submit' name = 'submit'>
        <br>
        <input type = 'number' name = 'number1' placeholder="削除したい投稿番号" min='1'>
        <input type = 'password' name= 'password2' placeholder="パスワードを入力"> 
        <button name="delete">削除</button>
        <br>
        <input type = 'number' name = 'number2' placeholder="編集したい投稿番号" min='1'>
        <button name="edit">編集開始</button>
        <br>
    </form>
    
    <?php
    date_default_timezone_set('Asia/Tokyo');
    $name = filter_input(INPUT_POST, 'name');
    $text = filter_input(INPUT_POST, 'text');
    $password1 = filter_input(INPUT_POST, 'password1');
    
    $delete_num = filter_input(INPUT_POST, 'number1');
    $password2 = filter_input(INPUT_POST, 'password2');
    
    $edit_num = filter_input(INPUT_POST, 'number2');

    
    $dsn ='データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE 
    => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS combinedtest"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."password TEXT,"
    ."name char(32),"
    ."time TEXT,"
    ."comment TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    
       
//    送信ボタンが押されたとき  
    if(isset($_POST['submit'])){
        if ($name==''){
            echo '名前が入力されていません<br><br>';
        }elseif($text==''){
            echo 'コメントが入力されていません<br><br>';
        }elseif($password1==''){
            echo 'パスワードが入力されていません<br><br>';
        }else{
            $time = date("Y-m-d H:i:s");
            $combine = $name. '<>'. $text. '<>'. date("Y-m-d H:i:s").'<>'.$password1.'<>';
            
            $sql = $pdo -> prepare("INSERT INTO combinedtest (password,name,comment,time) VALUES (:password, :name, :comment, :time)");
            $sql -> bindParam(':password', $password1, PDO::PARAM_STR);
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $text, PDO::PARAM_STR);
            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
            

            
            
            $sql -> execute();
        }
        
//    削除ボタンが押されたとき  
    }elseif(isset($_POST['delete'])){
//        削除したい番号を整数型として取得
        $id1 = (int)$delete_num;
        
        $sql = 'SELECT * FROM combinedtest WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt -> execute(array(
        ':id' => $id1
        ));
        $result =  $stmt -> fetchAll();
    
        foreach($result as $row){
            $password = $row['password'];
        }
        $name = '';
        $text = '投稿は削除されました';

        
        
        if($password2 == $password){
            $time = date("Y-m-d H:i:s");
            $sql = 'UPDATE combinedtest SET name=:name,comment=:comment, time=:time WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $text, PDO::PARAM_STR);
            $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id1, PDO::PARAM_STR);
            
            $stmt -> execute();

        }else{
            echo 'パスワードが違います<br><br>';
        }

        
//        編集開始ボタンを押したとき
    }elseif(isset($_POST['edit'])){
        
        $id2 = (int)$edit_num;
        
        $sql = 'SELECT * FROM combinedtest WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt -> execute(array(
        ':id' => $id2
        ));
        $result =  $stmt -> fetchAll();
    
        foreach($result as $row){
 
            $id = $row['id'];
            $name =  $row['name'];
            $comment = $row['comment'];

        
 
//                新しい入力フォームを作成
//                投稿番号は書き換えできない
            echo "↓<form action='' method='post'>
                <input type = 'number' name = 'id2' value= $id readonly>　
                <input type = 'text' name = 'name2' value= $name>
                <input type = 'text' name= 'text2' value= $comment>
                 <input type = 'password' name= 'password3' placeholder='パスワードを入力'> 
                 <input type = 'submit' name='edit_run' value='編集'> <br>
                    </form>";
        }


//        
//        新たに作成した編集ボタンを押したとき
        
    }else if (isset($_POST['edit_run'])){
        $name = filter_input(INPUT_POST, 'name2');
        $text = filter_input(INPUT_POST, 'text2');
        $id2 = filter_input(INPUT_POST, 'id2');    
        $password3 = filter_input(INPUT_POST, 'password3');
        $sql = 'SELECT * FROM combinedtest WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt -> execute(array(
        ':id' => $id2
        ));
        $result =  $stmt -> fetchAll();
        foreach($result as $row){
            $id = $row['id'];
            $password = $row['password'];
        }
        if($password3 == $password){
            $time = date("Y-m-d H:i:s");
            $sql = 'UPDATE combinedtest SET name=:name,comment=:comment, time=:time WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $text, PDO::PARAM_STR);
            $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_STR);
            
            $stmt -> execute();
                
        
        }else{
             echo "パスワードが一違います<br>";       
            }
        }
    
    
    
    
////    画面の表示

    $sql = 'SELECT * FROM combinedtest';
    $stmt = $pdo ->query($sql);
    $results = $stmt->fetchAll();

    foreach($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['time'].'<br>';
        echo $row['comment'].'<br>';
        echo '<hr>';
    }
    

    
    ?>
    
</body>




</html>




