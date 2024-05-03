<?php
session_start();
include('config.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['login_user'] = $email;
        header("location: dashboard.php");
    } else {
        $error = "Your Login Name or Password is invalid";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <div>
        <form action="" method="post">
            <label>Email :</label>
            <input type="text" name="email"><br>
            <label>Password :</label>
            <input type="password" name="password"><br>
            <input type="submit" value="Login">
        </form>
        <div><?php if(isset($error)) { echo $error; } ?></div>
    </div>
</body>
</html>
