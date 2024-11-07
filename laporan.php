<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    echo "<p>Akses ditolak. Hanya staff prodi yang dapat mengakses halaman ini.</p>";
    exit();
}

$sql = "SELECT COUNT(*) as jumlah FROM judul WHERE status = 'diterima' AND bukti_pembayaran IS NOT NULL";
$result = $conn->query($sql);
$count = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Judul</title>
</head>
<body>
    <div class="container">
        <h2>Laporan Judul Diterima dan Sudah Melakukan Pembayaran</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Diterima dan Pembayaran Ada</td>
                    <td><?php echo htmlspecialchars($count['jumlah']); ?></td>
                </tr>
            </tbody>
        </table>
        <a href="surat_pengantar.php"><button>kembali</button></a>
    </div>
</body>
</html>
