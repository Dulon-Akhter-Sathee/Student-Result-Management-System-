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

<style>
.title {
    text-align: center;
    margin-top: 40px;
    font-size: 28px;
    font-weight: bold;
}

.login-box {
    width: 350px;
    background: #ffffff;
    padding: 25px;
    margin: 60px auto;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
    border-radius: 8px;
    text-align: center;
}

.login-box h2 {
    margin-bottom: 20px;
    font-size: 22px;
}

label {
    float: left;
    font-size: 14px;
    margin: 8px 0 3px 3px;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 10px;
    background: #28a745;
    border: none;
    color: #fff;
    font-size: 17px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #218838;
}
</style>

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
