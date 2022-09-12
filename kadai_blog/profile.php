<!--
ログインしたユーザーのツイートのみ表示
そのほかははhome.phpとほぼ同じ。本来は同箇所はまとめるべき。
-->


<?php

session_start();

try {
    // PDOインスタンスを生成  
//    $ini = parse_ini_file('./db.ini',FALSE);
//    $pdo = new PDO('mysql:host='.$ini['host'].';dbname='.$ini['dbname'].';charset=utf8', $ini['dbuser'], $ini['dbpass']);
    $pdo = new PDO('mysql:host=localhost;dbname=tsubuyaki;charset=utf8','root','root');

        // エラー（例外）が発生した時の処理を記述
        } catch (PDOException $e) {

          // エラーメッセージを表示させる
          echo 'データベースにアクセスできません！' . $e->getMessage();

          // 強制終了
          exit;
        }
    
    function showName($id){
        global $pdo;
        $sql_name = "SELECT name FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql_name);
        $stmt->bindvalue(':id',$id);
        if($stmt->execute()){
            foreach( $stmt as $value ) {
                echo "name: ";
                echo "$value[name]<br>";
	       }
        }else{
            echo "失敗";
        }
    }




    if(isset($_POST['tsubuyaki_button'])){
        global $pdo;
        if(!$_POST['textarea']==''){    
            $sql = "INSERT INTO tweet (text,created_at,id) VALUES (:text,:created_at,:id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':text',$_POST['textarea']);
            date_default_timezone_set('Asia/Tokyo');
            $stmt->bindValue(':created_at',date("Y/m/d H:i:s"));
            $stmt->bindValue(':id',$_SESSION['id']);
            if($stmt->execute()){
                echo "つぶやきました";
                header("Location: home.php");
            }else{
                echo "つぶやき投稿エラー";
            }
        }
    }
?>


<!doctype html>

<head>
    
<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      
<!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>  
-->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
    <style type="text/css">

        body{
            background-color:azure;
        }
        #nav-column{
            font-size: 20px;
        }
        
        .col{
            border-right: 1px solid;
            border-color: darkgrey;
        }
        .col-6{
            border-right: 1px solid;
            border-color: darkgrey;
        }
        .tweet{
            border: 1px solid grey;
            border-radius: 5px;
            padding: 5px;
            margin: 5px;
        }
/*
        #tsubuyaki_box{
            height: 200px;
            width: 220px;
        }
*/
      
    </style>
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col">
        <div id="nav-column">
            <nav class="nav flex-column">
              <a class="nav-link active" href="home.php">ホーム</a>
              <a class="nav-link" href="profile.php">マイページ</a>
            </nav>
        </div>
    </div>
    <div class="col-6">
        <h2>マイページ</h2>
        <?php displayTweets(); ?>
        <?php    function displayTweets(){
            global $pdo;  
// 
//        $sql = "SELECT * FROM tweet WHERE id = $_SESSION[id] ORDER BY tweet_id DESC LIMIT 30";
//    getでif
        if(empty($_GET['id'])){
            $sql = "SELECT * FROM tweet WHERE id = $_SESSION[id] ORDER BY tweet_id DESC LIMIT 30";
        }else{
            $sql = "SELECT * FROM tweet WHERE id = $_GET[id] ORDER BY tweet_id DESC LIMIT 30";
        }
        // SQLステートメントを実行し、結果を変数に格納
        $stmt = $pdo->query($sql);

        // foreach文で配列の中身を一行ずつ出力
        foreach ($stmt as $row) {
        ?>            
        <div class="card">
            <div class="card-header">
                <?php showName($row['id']); ?> 
            </div>
            <div class="card-body">
                <h5 class="card-title">
            <?php            
                // データベースのフィールド名で出力
              echo $row['text'];
            ?>
                </h5>
                <p class="card-text"></p>
            </div>
        </div>
        <?php
                }        
            }
        ?>
    </div>

<!--      3カラム目だけ回り込ませたい-->
    <div class="col-sm">
        投稿内容を入力してください。
        <form method="post" id="tsubuyaki_form">
            <div id="tsubuyaki_form">
                <textarea type="text" name="textarea" cols="20" rows="5"></textarea>
                <input type="submit" class="btn btn-primary" name="tsubuyaki_button" value="投稿する">
            </div>
        </form>
            
    </div>
  </div>
</div>

</body>

</html>