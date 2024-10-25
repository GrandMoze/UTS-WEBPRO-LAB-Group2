<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['id'];
    $confirmPassword = $_POST['password'];
    $username = $_POST['username'];

    if ($newPassword === $confirmPassword) {
        $conn = new mysqli("localhost", "root", "", "ujian tengah semester"); // Ganti dengan kredensial yang benar

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $sql = "UPDATE ujianlab SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newPassword, $username);
        if ($stmt->execute()) {
            echo "Password berhasil diubah.";
            header("Location: ../pages/index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "Password baru dan konfirmasi password tidak sama.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <link rel="stylesheet" href="../login.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="gambar container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-4" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                <div class="login-box">
                    <h2>UBAH PASSWORD</h2>
                    <form action="" method="post">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($_GET['username']); ?>">
                        <div class="form-group">
                            <label for="id">NEW PASSWORD</label>
                            <input type="text" id="id" name="id" class="form-control" placeholder="NEW PASSWORD"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="password">CONFIRM PASSWORD</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="PASSWORD" required>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block">UBAH PASSWORD</button>
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