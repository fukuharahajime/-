<?php

session_start();

try {
          // PDOインスタンスを生成
//        $ini = parse_ini_file('./db.ini',FALSE);
//          $pdo = new PDO('mysql:host='.$ini['host'].';dbname='.$ini['dbname'].';charset=utf8', $ini['dbuser'], $ini['dbpass']);
    
        $pdo = new PDO('mysql:host=localhost;dbname=tsubuyaki;charset=utf8','root','root');

        // エラー（例外）が発生した時の処理を記述
        } catch (PDOException $e) {

          // エラーメッセージを表示させる
          echo 'データベースにアクセスできません！' . $e->getMessage();
          // 強制終了
          exit;
        }


//        SignUpボタンを押下したときの処理
        if(isset($_POST['SignUpButton'])){
            
        
    //        入力チェックを以下に記載
            if(array_key_exists('email',$_POST) or array_key_exists('password',$_POST) or array_key_exists('name',$_POST)){
            if($_POST['email']==''){
                echo "emailを入力してください";
            }else if($_POST['password']==''){
                echo "passwordを入力してください";
            }else if($_POST['name']==''){
                echo "User Nameを入力してください";
            }else{
                echo $_POST['email'];
                $sql = "SELECT id FROM users WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':email',$_POST['email']);
                $stmt->execute();
//                テーブルのレコード数を取得する
                $row_cnt = $stmt->rowCount();
                if($row_cnt>0){
                    echo "重複してます";
                    echo $row_cnt;
                }
                else{
                    $sql = "INSERT INTO users (email,password,name) VALUES  (:email,:password,:name)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':email',$_POST['email']);
                    $stmt->bindValue(':password',$_POST['password']);
                    $stmt->bindValue(':name',$_POST['name']);
                    
                    
                    if($stmt->execute()){
                        echo "成功";
                    }else{
                        echo "失敗";
                    }
                }
            }
        }
    }
//    SignInボタンを押下したときの処理
   if(isset($_POST['SignInButton'])){
           //        入力チェックを以下に記載
        if(array_key_exists('email',$_POST) or array_key_exists('password',$_POST)){
            if($_POST['email']==''){
                echo "emailを入力してください";
            }else if($_POST['password']==''){
                echo "passwordを入力してください";
            }else{
                $sql = "SELECT * FROM users WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':email',$_POST['email']);
                $stmt->execute();
                print_r($sql);
                $user = $stmt->fetch();
                print_r($user);
                
                if($_POST['password'] == $user['password']){
                    echo "ログイン成功";
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['id'] = $user['id'];
                    echo "session:";
                    echo $_SESSION['id'];
                    header("Location: home.php"); 
                    exit();
                }
                else{
                    echo "ログイン失敗"; 
                    echo $_POST['password'];
                    echo $user['password'];
                }
            }
        }
    }
   
?>


<form method="post"  id="sign-up-form">
  <h1>TSUBUYAKI</h1>
  <div class="form-group">
    <label for="exampleInputUserName">User Name</label>
    <input name="name" class="form-control" id="exampleInputUserName"  placeholder="Enter User Name">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary" name="SignUpButton">SignUp</button> 
    <a class="toggleSwitch">SignIn</a>
</form>

<form method="post" id="sign-in-form">
  <h1>TSUBUYAKI</h1>

  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary" name="SignInButton">SignIn</button> 
    <a class="toggleSwitch">SignUp</a>
</form>

