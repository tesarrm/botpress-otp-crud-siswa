<?php
session_start();
include('config.php');

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}

$email = $_SESSION['login_user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    // tambahkan validasi atau pengolahan data lainnya sesuai kebutuhan Anda

    $sql = "UPDATE students SET name='$name' WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result) {
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}

$sql = "SELECT * FROM students WHERE email='$email'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
</head>

<body>
    <h2>Edit Profile</h2>
    <?php if (isset($success_message)) { ?>
        <div><?php echo $success_message; ?></div>
    <?php } ?>
    <?php if (isset($error_message)) { ?>
        <div><?php echo $error_message; ?></div>
    <?php } ?>
    <form action="" method="post">
        <label>Name :</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
        <!-- tambahkan input lainnya sesuai kebutuhan Anda -->
        <input type="submit" value="Update">
    </form>
    <a href="logout.php">Logout</a>

</body>

</html>