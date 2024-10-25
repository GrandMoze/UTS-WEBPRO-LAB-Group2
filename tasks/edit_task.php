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

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_title = $_POST['title'];

    $sql = "UPDATE tasks SET title = ? WHERE id = ? AND list_id IN (SELECT id FROM todo_lists WHERE user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_title, $task_id, $user_id);
    $stmt->execute();

    header("Location: view_list.php?id=" . $_GET['list_id']);
    exit();
}

$sql = "SELECT * FROM tasks WHERE id = ? AND list_id IN (SELECT id FROM todo_lists WHERE user_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    header("Location: ../pages/halaman.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Tugas</h1>
        <form method="post">
            <div class="form-group">
                <label for="title">Judul Tugas</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="view_list.php?id=<?php echo $_GET['list_id']; ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>