<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_nim'])) {
    $nim = $_SESSION['user_nim'];
} else {
    echo "<p>User tidak terautentikasi. Silakan login.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $abstrak = $_POST['abstrak'];

    $stmt = $conn->prepare("INSERT INTO judul (judul, abstrak, nim) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul, $abstrak, $nim);

    if ($stmt->execute()) {
        header('Location: pengajuan_judul.php');
        exit();
    } else {
        echo "<p>error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Judul</title>
</head>
<body>
    <div class="container">
        <h2>Pengajuan Judul</h2>
        <form action="" method="post">
            <label for="judul">Judul</label>
            <input type="text" id="judul" name="judul" placeholder="Judul" required>
            <label for="abstrak">Abstrak</label>
            <textarea name="abstrak" id="abstrak" rows="4" maxlength="150" required></textarea>
            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($nim); ?>" required readonly>

            <button type="submit">Simpan</button>
            
        </form>
        <a href="pengajuan_judul.php"><button>Kembali</button></a>
    </div>
</body>
</html>
