<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
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

$user_id = $_SESSION['user_id'];

// Hapus to-do list
if (isset($_GET['delete_list_id'])) {
    $list_id = $_GET['delete_list_id'];
    $sql = "DELETE FROM todo_lists WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $list_id);
    $stmt->execute();
}

// Ambil daftar to-do list pengguna
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM todo_lists WHERE user_id = ? AND title LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = '%' . $search . '%';
$stmt->bind_param("is", $user_id, $search_param);
$stmt->execute();
$result = $stmt->get_result();
$todo_lists = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 60%;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #343a40;
            text-align: center;
        }
        .list-group-item {
            border: none;
            border-radius: 5px;
            background: #e0f7fa;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            transition: border 0.3s;
        }
        .list-group-item:hover {
            border: 2px solid #007bff;
        }
        .btn {
            margin-left: 5px;
        }
        .search-bar {
            width: 100%;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <h2>To-Do Lists Anda</h2>
        <ul class="list-group">
            <?php foreach ($todo_lists as $list): ?>
                <li class="list-group-item">
                    <span><?php echo htmlspecialchars($list['title']); ?></span>
                    <div>
                        <a href="../tasks/view_list.php?id=<?php echo $list['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="?delete_list_id=<?php echo $list['id']; ?>" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Cari Tugas" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary mt-2">Cari</button>
        </form>
    </div>

    <script>
        // JavaScript untuk interaksi tambahan jika diperlukan
    </script>
</body>

</html>