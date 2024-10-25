<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ujian tengah semester";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['id'];
    $user_password = $_POST['password'];

    $sql = "SELECT * FROM ujianlab WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ss", $user_id, $user_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: pages/halaman.php");
        exit();
    } else {
        $error_message = "Username atau password salah!";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="gambar container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-4" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                <div class="login-box">
                    <h2>LOGIN</h2>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <form action="" method="post">
                    <img src="./gambar/Universitas-Multimedia-Nusantara.png" alt=""
                    style="position: fixed; top: 20px; left: 20px; z-index: 1000; width: 80px; height: 150px;">
                        <div class="form-group">
                            <label for="id">USERNAME</label>
                            <input type="text" id="id" name="id" class="form-control" placeholder="USERNAME" required>
                        </div>
                        <div class="form-group">
                            <label for="password">PASSWORD</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="PASSWORD" required>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block">LOGIN</button>
                        <div class="text-center mt-2">
                            <a href="auth/lupapassword.php">LUPA PASSWORD?</a>
                        </div>
                        <div class="text-center mt-2">
                            <a href="auth/register.php" class="btn btn-link">REGISTER</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a href="https://wa.me/6285893930323" target="_blank"
            style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp"
                style="width: 60px; height: 60px; border-radius: 50%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
        </a>
    </div>
</body>

</html>