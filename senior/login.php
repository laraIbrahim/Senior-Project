<?php

require_once "connect.php";
$query="SELECT * FROM nurses;";

if (isset($_POST['username']) && isset($_POST['password'])){
    $pass=$_POST['password'];
    $username=$_POST['username'];

    $queryPass="SELECT password FROM nurses WHERE username=\"$username\";";
    

    $addQuery = mysqli_query($con, $queryPass);
    $fetched_row = mysqli_fetch_assoc($addQuery);
    if($fetched_row!=null){
      $password = $fetched_row['password'];
      if ($pass == $password && $username == $username){
        header("location: index.php");
      }else{
        echo '<script>alert("Wrong Password!");</script>';
      }
    }else{
      echo '<script>alert("Wrong Username & Password!");</script>';
    }   
}

?>



<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title> Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">


    

    <!-- Bootstrap core CSS -->
<link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Favicons -->
<link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
<meta name="theme-color" content="#7952b3">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  </head>
 
    
  <body>
    <div class="wrapper">
        <form method="post">
            <h1>Please Sign in</h1>
            <div class="input-box">
                <input type="text" placeholder="username"  name="username" class="form-control" id="username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="password" name="password" class="form-control" id="password" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>


</html>