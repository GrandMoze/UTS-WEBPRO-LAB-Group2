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

if (isset($_GET['id'])) {
    $list_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Hapus tasks terkait dari database
    $sql = "DELETE FROM tasks WHERE list_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $list_id);
    $stmt->execute();

    // Hapus to-do list dari database
    $sql = "DELETE FROM todo_lists WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $list_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "To-do list dan tasks terkait berhasil dihapus.";
    } else {
        echo "Gagal menghapus to-do list.";
    }
}

header("Location: ../pages/halaman.php");
exit();
?>