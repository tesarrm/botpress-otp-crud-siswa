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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['otp'])) {
        $email = $_POST['email'];
        $otp_entered = $_POST['otp'];

        // Memeriksa apakah OTP yang dimasukkan oleh pengguna sesuai dengan yang diharapkan
        if ($_SESSION['otp'] == $otp_entered) {
            // Jika OTP valid, arahkan ke halaman edit profil
            $_SESSION['login_user'] = $email;
            header("location: edit_profile.php");
            exit(); // tambahkan ini untuk menghentikan eksekusi setelah mengarahkan
        } else {
            $error = "Invalid OTP";
        }
    } elseif (isset($_POST['email'])) {
        // Jika email dikirim dari form login
        $email = $_POST['email'];

        // Generate dan kirim OTP ke email
        $otp = generateOTP();
        $_SESSION['otp'] = $otp; // Simpan OTP di session
        if (sendOTP($email, $otp)) {
            echo "OTP telah dikirim ke email Anda"; // Tambahkan pesan sukses
        } else {
            $error = "Failed to send OTP. Please try again."; // Tambahkan pesan error jika gagal mengirimkan OTP
        }
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
        <h2>Login with OTP</h2>
        <form action="" method="post">
            <label>Email :</label>
            <input type="text" name="email"><br>
            <input type="submit" value="Send OTP">
        </form>
        <div>
            <?php if (isset($error)) {
                echo $error;
            } ?>
        </div>
    </div>
    <div>
        <h2>Verify OTP</h2>
        <form action="" method="post">
            <label>Enter OTP :</label>
            <input type="text" name="otp"><br>
            <input type="hidden" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
            <input type="submit" value="Verify OTP">
        </form>
    </div>
</body>

</html>
