<?php
session_start();
include('config.php');

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}

// Memeriksa apakah email pengguna ada di tabel users
$email = $_SESSION['login_user'];
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika email pengguna tidak ada di tabel users, arahkan kembali ke halaman login
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <!-- Tambahkan link CSS dan JS untuk Botpress UI -->
    <link rel="stylesheet" href="https://cdn.botpress.com/channel-web/inject/botpress-webchat.css" />
    <script src="https://cdn.botpress.com/channel-web/inject/botpress-webchat.js"></script>
    <script>
        // Inisialisasi Botpress Webchat
        window.addEventListener('load', function () {
            WebChat.default.init({ host: 'YOUR_BOTPRESS_SERVER_HOSTNAME' });
        });
    </script>
    <style>
        /* Tambahkan gaya kustom untuk tabel */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Welcome, <?php echo $_SESSION['login_user']; ?></h1>
    <a href="logout.php">Logout</a>
    <a href="add_student.php">Add Student</a>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td><td><a href='edit_student.php?id=" . $row["id"] . "'>Edit</a> | <a href='delete_student.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>0 results</td></tr>";
        }
        ?>
    </table>

    <!-- Tambahkan elemen untuk Botpress Webchat -->
    <div id="webchat" style="position: fixed; bottom: 20px; right: 20px;"></div>
</body>

</html>
