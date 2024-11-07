<?php
session_start();
include 'db.php';

if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}

$user_role = $_SESSION['role'];
$sql = "SELECT * FROM judul";

$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['bukti_pembayaran'])) {
    $judul_id = $_POST['judul_id'];
    $target_dir = "bukti=bayar/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target_file = $target_dir . basename($_FILES["bukti_pembayaran"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $checkStatus = "SELECT status FROM judul WHERE id_judul = '$judul_id'";
    $statusResult = $conn->query($checkStatus);
    $statusRow = $statusResult->fetch_assoc();

    if ($statusRow['status'] !== 'diterima') {
        echo "<p>Judul belum diterima. Tidak dapat mengupload bukti pembayaran.</p>";
        $uploadOk = 0;
    }

    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf") {
        echo "<p>File harus JPG, JPEG, PNG & PDF</p>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
            $update_sql = "UPDATE judul SET bukti_pembayaran = '$target_file' WHERE id_judul = '$judul_id'";
            if ($conn->query($update_sql) === TRUE) {
                echo "<p>Bukti pembayaran berhasil diupload.</p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>Gagal upload.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h2>Daftar Judul</h2>
        <p>Halo, <?php echo ($_SESSION['name']); ?></p>
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Abstrak</th>
                    <th>Status</th>
                    <th>Alasan</th>
                    <th>Upload Bukti Pembayaran</th>
                    <th>File yang Diupload</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo ($row['judul']); ?></td>
                            <td><?php echo ($row['abstrak']); ?></td>
                            <td><?php echo ($row['status']); ?></td>
                            <td><?php echo ($row['alasan']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'diterima'): ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="judul_id" value="<?php echo ($row['id_judul']); ?>">
                                        <input type="file" name="bukti_pembayaran" required>
                                        <button type="submit">Upload</button>
                                    </form>
                                <?php else: ?>
                                    <p>Judulmu ditolak</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['bukti_pembayaran'])): ?>
                                    <a href="<?php echo ($row['bukti_pembayaran']); ?>" target="_blank">Lihat Bukti Pembayaran</a>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="login.php"><button>Keluar</button></a>
        <a href="tambah_judul.php"><button>Tambah Judul</button></a>
    </div>
</body>
</html>
