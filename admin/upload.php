<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Mengupload file
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File yang diunggah bukan gambar.";
        exit();
    }

    // Memindahkan file ke direktori yang ditentukan
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Menyimpan data proyek ke database
        $sql = "INSERT INTO projects (title, description, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $description, $target_file);

        if ($stmt->execute()) {
            echo "Proyek berhasil diunggah.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error mengupload file.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Project</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <h2>Upload Project</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="title">Judul:</label>
        <input type="text" name="title" required>

        <label for="description">Deskripsi:</label>
        <textarea name="description" required></textarea>

        <label for="image">Pilih Gambar:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Unggah</button>
    </form>
</body>

</html>