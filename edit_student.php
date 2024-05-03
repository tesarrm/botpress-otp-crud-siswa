<?php
session_start();
include('config.php');

if(!isset($_SESSION['login_user'])){
    header("location: login.php");
    exit();
}

$id = $_GET['id'];

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    $sql = "UPDATE students SET name='$name', email='$email' WHERE id='$id'";
    $result = $conn->query($sql);
    header("location: dashboard.php");
}

$sql = "SELECT * FROM students WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <form action="" method="post">
        <label>Name :</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
        <label>Email :</label>
        <input type="text" name="email" value="<?php echo $row['email']; ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
