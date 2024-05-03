<?php
session_start();
include('config.php');

if(!isset($_SESSION['login_user'])){
    header("location: login.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM students WHERE id='$id'";
$result = $conn->query($sql);
header("location: dashboard.php");
?>
