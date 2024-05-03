<?php
session_start();
include('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Sesuaikan dengan lokasi file autoload PHPMailer

// Fungsi untuk menghasilkan OTP
function generateOTP()
{
    $otp = rand(100000, 999999);
    return $otp;
}

// Fungsi untuk mengirimkan OTP ke email
function sendOTP($email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Host SMTP Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'tesarrm58@gmail.com'; // Alamat email Anda
        $mail->Password = 'cslulirpurnvnnpw'; // Kata sandi email Anda
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; // Port SMTP untuk TLS

        // Set pengirim dan penerima email
        $mail->setFrom('tesarrm58@gmail.com', 'Support'); // Alamat email pengirim
        $mail->addAddress($email); // Alamat email penerima

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP';
        $mail->Body = 'Kode OTP Anda: ' . $otp;

        // Kirim email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Fungsi untuk mengautentikasi pengguna berdasarkan OTP
function authenticateUser($email, $otp)
{
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
        // OTP valid, set session login_user
        $_SESSION['login_user'] = $email;
        return true;
    } else {
        return false;
    }
}
// Endpoint untuk verifikasi OTP dan autentikasi pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['otp'])) {
    $email = $_POST['email'];
    $otp_entered = $_POST['otp'];
    if (authenticateUser($email, $otp_entered)) {
        // Autentikasi berhasil, kirim respons JSON
        echo json_encode(array("message" => "Autentikasi berhasil"));
    } else {
        // Autentikasi gagal, kirim respons JSON dengan status 401
        http_response_code(401);
        echo json_encode(array("message" => "Autentikasi gagal. OTP tidak valid"));
    }
}
// Endpoint untuk mengirimkan OTP
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $otp = generateOTP();
    $_SESSION['otp'] = $otp; // Simpan OTP di session
    if (sendOTP($email, $otp)) {
        // OTP berhasil dikirim, kirim respons JSON
        echo json_encode(array("message" => "OTP telah dikirim ke email Anda"));
    } else {
        // Gagal mengirim OTP, kirim respons JSON dengan status 500
        http_response_code(500);
        echo json_encode(array("message" => "Gagal mengirim OTP. Silakan coba lagi."));
    }
}



// Jika metode HTTP tidak didukung, kirim respons JSON dengan status 405
else {
    http_response_code(405);
    echo json_encode(array("message" => "Metode HTTP tidak didukung"));
}
