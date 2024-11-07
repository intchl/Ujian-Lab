<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    echo "<p>Akses ditolak. Hanya staff prodi yang dapat mengakses halaman ini.</p>";
    exit();
}

$sql = "SELECT j.*, u.name FROM judul j JOIN users u ON j.nim = u.nim WHERE j.status = 'diterima' AND j.bukti_pembayaran IS NOT NULL";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['surat_pengantar'])) {
    $judul_id = $_POST['id_judul'];
    $target_dir = "surat_pengantar/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target_file = $target_dir . basename($_FILES["surat_pengantar"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf") {
        echo "<p>Sorry, only JPG, JPEG, PNG & PDF files are allowed.</p>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["surat_pengantar"]["tmp_name"], $target_file)) {
            $update_sql = "UPDATE judul SET surat_pengantar = '$target_file' WHERE id_judul = '$judul_id'";
            if ($conn->query($update_sql) === TRUE) {
                echo "<p>Surat pengantar berhasil diupload.</p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleksi Judul</title>
</head>
<body>
    <div class="container">
        <h2>Seleksi Judul</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Judul</th>
                    <th>Abstrak</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Upload Surat Pengantar</th>
                    <th>Lihat Surat Pengantar</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nim']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['judul']); ?></td>
                            <td><?php echo htmlspecialchars($row['abstrak']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <?php if (!empty($row['bukti_pembayaran'])): ?>
                                    <a href="<?php echo htmlspecialchars($row['bukti_pembayaran']); ?>" target="_blank">Lihat Bukti Pembayaran</a>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id_judul" value="<?php echo htmlspecialchars($row['id_judul']); ?>">
                                    <input type="file" name="surat_pengantar" required>
                                    <button type="submit">Upload</button>
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($row['surat_pengantar'])): ?>
                                    <a href="<?php echo htmlspecialchars($row['surat_pengantar']); ?>" target="_blank">Lihat Surat Pengantar</a>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="login.php"><button>Keluar</button></a>
        <a href="laporan.php"><button>Laporan</button></a>
    </div>
</body>
</html>
