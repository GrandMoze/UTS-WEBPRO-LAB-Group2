<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ujian tengah semester";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$list_id = $_GET['list_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $sql = "INSERT INTO tasks (list_id, title, description, due_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $list_id, $title, $description, $due_date);

    if ($stmt->execute()) {
        header("Location: view_list.php?id=" . $list_id);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas Baru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Tambah Tugas Baru</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="title">Judul Tugas</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Tanggal Jatuh Tempo</label>
                <input type="date" class="form-control" id="due_date" name="due_date">
            </div>
            <button type="submit" class="btn btn-primary">Tambah Tugas</button>
            <a href="view_list.php?id=<?php echo $list_id; ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>