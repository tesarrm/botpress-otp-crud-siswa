<?php
session_start();
include('config.php');

if(!isset($_SESSION['login_user'])){
    header("location: login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    $sql = "INSERT INTO students (name, email) VALUES ('$name', '$email')";
    $result = $conn->query($sql);
    header("location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
</head>
<body>
    <h1>Add Student</h1>
    <form action="" method="post">
        <label>Name :</label>
        <input type="text" name="name"><br>
        <label>Email :</label>
        <input type="text" name="email"><br>
        <input type="submit" value="Add">
    </form>
</body>
</html>
