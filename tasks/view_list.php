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

$list_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Tambah Tugas Baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sql = "INSERT INTO tasks (list_id, title, description, status) VALUES (?, ?, ?, 'incompleted')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $list_id, $title, $description);
    $stmt->execute();
    header("Location: view_list.php?id=" . $list_id . "&filter=" . $filter);
    exit();
}

// Edit Tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task'])) {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sql = "UPDATE tasks SET title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $description, $task_id);
    $stmt->execute();
    header("Location: view_list.php?id=" . $list_id . "&filter=" . $filter);
    exit();
}

// Jika ada permintaan POST, berarti kita sedang mengedit tugas atau menandai selesai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_task_id'])) {
    $task_id = $_POST['toggle_task_id'];
    $status = $_POST['status'] === 'completed' ? 'incompleted' : 'completed';
    
    // Update status di database
    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $task_id);
    $stmt->execute();
    
    header("Location: view_list.php?id=" . $list_id . "&filter=" . $filter);
    exit();
}

// Ambil informasi to-do list
$sql = "SELECT * FROM todo_lists WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_assoc();

if (!$list) {
    header("Location: ../pages/halaman.php");
    exit();
}

// Ambil tugas-tugas dalam to-do list berdasarkan filter dan pencarian
$sql = "SELECT * FROM tasks WHERE list_id = ? AND title LIKE ?";
if ($filter === 'completed') {
    $sql .= " AND status = 'completed'";
} elseif ($filter === 'incompleted') {
    $sql .= " AND status = 'incompleted'";
}
$stmt = $conn->prepare($sql);
$search_param = "%" . $search_query . "%";
$stmt->bind_param("is", $list_id, $search_param);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($task = $result->fetch_assoc()) {
    $tasks[] = $task;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .list-group-item {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: transform 0.2s;
        }
        .list-group-item:hover {
            transform: scale(1.02);
        }
        .btn {
            margin: 0 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Daftar Tugas</h1>

        <!-- Pencarian dan Filter -->
        <div class="text-center mb-4">
            <div class="btn-group" role="group" aria-label="Filter">
                <a href="?id=<?php echo $list_id; ?>&filter=all" class="btn btn-outline-secondary">Semua</a>
                <a href="?id=<?php echo $list_id; ?>&filter=completed" class="btn btn-outline-success">Selesai</a>
                <a href="?id=<?php echo $list_id; ?>&filter=incompleted" class="btn btn-outline-warning">Belum Selesai</a>
            </div>
            <form id="searchForm" class="form-inline mt-3 justify-content-center" method="GET">
                <input type="hidden" name="id" value="<?php echo $list_id; ?>">
                <input type="text" name="search" class="form-control" placeholder="Cari Tugas" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-primary ml-2">Cari</button>
            </form>
        </div>

        <!-- Daftar Tugas -->
        <ul class="list-group">
            <?php foreach ($tasks as $task): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <div class="col-12 col-md-8">
                        <form method="POST" class="d-flex align-items-center">
                            <input type="hidden" name="toggle_task_id" value="<?php echo $task['id']; ?>">
                            <input type="hidden" name="status" value="<?php echo $task['status']; ?>">
                            <button type="submit" class="btn btn-link">
                                <input type="checkbox" <?php echo $task['status'] === 'completed' ? 'checked' : ''; ?>>
                            </button>
                            <strong class="ml-2"><?php echo htmlspecialchars($task['title']); ?></strong><br>
                            <small><?php echo nl2br(htmlspecialchars($task['description'])); ?></small>
                        </form>
                    </div>
                    <span class="col-12 col-md-4 text-right mt-3 mt-md-0">
                        <button class="btn btn-info btn-sm" onclick="viewTask('<?php echo htmlspecialchars($task['title']); ?>', '<?php echo htmlspecialchars($task['description']); ?>')"><i class="fas fa-eye"></i> Lihat</button>
                        <button class="btn btn-primary btn-sm" onclick="editTask(<?php echo $task['id']; ?>, '<?php echo htmlspecialchars($task['title']); ?>', '<?php echo htmlspecialchars($task['description']); ?>')"><i class="fas fa-pen"></i> Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteTask(<?php echo $task['id']; ?>)"><i class="fas fa-trash"></i> Hapus</button>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-3 text-center">
            <button class="btn btn-success" id="addTaskBtn">Tambah Tugas Baru</button>
            <a href="../pages/halaman.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- Modal Tambah Tugas Baru -->
    <div id="newTaskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Tambah Tugas Baru</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Judul Tugas</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" ></textarea>
                </div>
                <button type="submit" name="new_task" class="btn btn-success">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Modal Lihat Tugas -->
    <div id="viewTaskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="viewTaskTitle">Lihat Tugas</h2>
            <p id="viewTaskDescription"></p>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div id="editTaskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Tugas</h2>
            <form method="POST">
                <input type="hidden" id="editTaskId" name="task_id">
                <div class="form-group">
                    <label for="editTitle">Judul Tugas</label>
                    <input type="text" class="form-control" id="editTitle" name="title" required>
                </div>
                <div class="form-group">
                    <label for="editDescription">Deskripsi</label>
                    <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                </div>
                <button type="submit" name="edit_task" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function viewTask(title, description) {
        $('#viewTaskTitle').text(title);
        $('#viewTaskDescription').text(description);
        $('#viewTaskModal').show();
    }

    function editTask(id, title, description) {
        $('#editTaskId').val(id);
        $('#editTitle').val(title);
        $('#editDescription').val(description);
        $('#editTaskModal').show();
    }

    function deleteTask(id) {
        if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
            alert('Hapus tugas dengan ID: ' + id);
        }
    }

    // Modal script
    var modals = document.getElementsByClassName("modal");
    var btn = document.getElementById("addTaskBtn");
    var spans = document.getElementsByClassName("close");

    btn.onclick = function() {
        document.getElementById("newTaskModal").style.display = "block";
    }

    for (let i = 0; i < spans.length; i++) {
        spans[i].onclick = function() {
            modals[i].style.display = "none";
        }
    }

    window.onclick = function(event) {
        for (let i = 0; i < modals.length; i++) {
            if (event.target == modals[i]) {
                modals[i].style.display = "none";
            }
        }
    }
    </script>
</body>
</html>