<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require '../config.php';

// Proses upload file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Mengupload file
    $target_dir = "../images/";
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . '.' . $imageFileType; // Menggunakan uniqid untuk nama file

    // Validasi format gambar
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.');</script>";
        exit();
    }

    // Cek apakah file adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File yang diunggah bukan gambar.');</script>";
        exit();
    }

    // Memindahkan file ke direktori yang ditentukan
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Menyimpan data proyek ke database
        // Menggunakan jalur relatif dari folder proyek
        $relative_file_path = 'images/' . basename($target_file);
        $sql = "INSERT INTO projects (title, description, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $description, $relative_file_path);

        if ($stmt->execute()) {
            echo "<script>alert('Proyek berhasil diunggah.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error mengupload file.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;

            text-decoration: none;

            border: none;

            border-radius: 5px;

        }

        .btn-danger {
            background-color: #dc3545;
            color: white;

        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .alert {
            display: none;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>

</head>

<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <h2>Upload Project</h2>
        <form action="index.php" method="post" enctype="multipart/form-data" id="uploadForm">
            <label for="title">Judul:</label>
            <input type="text" name="title" required>

            <label for="description">Deskripsi:</label>
            <textarea name="description" required></textarea>

            <label for="image">Pilih Gambar:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit">Unggah</button>
        </form>
        <div id="message" class="alert"></div>
    </div>

    <script>
        const form = document.getElementById('uploadForm');
        const messageBox = document.getElementById('message');

        form.addEventListener('submit', function() {
            messageBox.style.display = 'block';
            messageBox.innerText = 'Mengupload...';
            messageBox.classList.add('success');
        });
    </script>
</body>

</html>