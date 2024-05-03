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

$email = $_SESSION['login_user'];

// Pastikan metode HTTP yang digunakan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan data yang diperlukan ada dalam request
    if (isset($_POST['name'])) {
        $name = $_POST['name'];

        // Panggil fungsi untuk mengubah profil pengguna
        if (updateUserProfile($email, $name)) {
            echo json_encode(array("message" => "Profile updated successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update profile. Please try again."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing required data"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
?>
