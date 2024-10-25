<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ujian tengah semester";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['id'];
    $userPassword = $_POST['password'];
    $userEmail = $_POST['email'];

    $sql = "INSERT INTO ujianlab (username, password, email) VALUES ('$userId', '$userPassword', '$userEmail')";

    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="../login.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="gambar container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-4" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                <div class="login-box">
                    <h2>LOGIN</h2>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="id">USERNAME</label>
                            <input type="text" id="id" name="id" class="form-control" placeholder="USERNAME" required>
                        </div>
                        <div class="form-group">
                            <label for="password">PASSWORD</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="PASSWORD" required>
                        </div>
                        <div class="form-group">
                            <label for="email">EMAIL</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="EMAIL"
                                required>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block">REGISTER</button>
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