<?php
session_start();
include('config.php');

// Fungsi untuk mengubah profil pengguna
function updateUserProfile($email, $name)
{
    global $conn;

    // Update data pengguna dalam database
    $sql = "UPDATE students SET name='$name' WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

// Pastikan pengguna sudah login sebelum mengakses API ini
if (!isset($_SESSION['login_user'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized"));
    exit();
}


// Pastikan metode HTTP yang digunakan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    // Pastikan data yang diperlukan ada dalam request

    $sql = "SELECT * FROM students WHERE email='$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    // Panggil fungsi untuk mengubah profil pengguna
    echo json_encode(array("data" => $row['name']));
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
