<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'prodi') {
    echo "<p>Akses ditolak. Hanya pengguna dengan role prodi yang dapat mengakses halaman ini.</p>";
    exit();
}

$sql = "SELECT j.*, u.name FROM judul j JOIN users u ON j.nim = u.nim";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $id = $_POST['id_judul']; 
    $status = $_POST['status'];
    $alasan = $_POST['alasan'] ?? ''; 

    if ($status === 'diterima') {
        $update_sql = "UPDATE judul SET status = 'diterima', alasan = NULL WHERE id_judul = '$id'";
    } else {
        $update_sql = "UPDATE judul SET status = 'ditolak', alasan = '$alasan' WHERE id_judul = '$id'";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "<p>Status berhasil diperbarui.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
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
                    <th>Upload Bukti Pembayaran</th>
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
                                <form method="post" action="">
                                    <input type="hidden" name="id_judul" value="<?php echo htmlspecialchars($row['id_judul']); ?>">
                                    <select name="status" id="status">
                                        <option value="diterima">Diterima</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                    <input type="text" name="alasan" placeholder="Alasan (jika tidak diterima)">
                                    <button type="submit" name="action" value="update">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="login.php"><button>Keluar</button></a>
    </div>
</body>
</html>
