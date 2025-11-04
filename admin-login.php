<?php
session_start();
error_reporting(0);
include('includes/config.php');

if($_SESSION['alogin']!=''){
$_SESSION['alogin']='';
}
if(isset($_POST['login'])){
    $uname = $_POST['username'];
    $password = md5($_POST['password']);
    
    $sql ="SELECT UserName,Password FROM admin WHERE UserName=:uname AND Password=:password";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':uname', $uname, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    
    if($query->rowCount() > 0){
        $_SESSION['alogin']=$_POST['username'];
        header("location: dashboard.php");
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<link rel="stylesheet" href="asset/css/style.css">



</head>

<body>

<h1 class="title">Student Result Management System</h1>

<div class="login-box">
    <h2>Admin Login</h2>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="UserName" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Sign In</button>
    </form>

    
</div>

</body>
</html>
