<?php 
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Validation for NIM and Password
    if (!is_numeric($nim) || strlen($nim) != 10) {
        $error_message = "ID HARUS BERUPA ANGKA DENGAN PANJANG 10 DIGIT";
    } elseif (strlen($password) < 5 || strlen($password) > 10) {
        $error_message = "Password harus memiliki 5-10 karakter";
    } else {
        $password = md5($password); // Password hashing

        $sql = "SELECT * FROM users WHERE nim = '$nim' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_nim'] = $row['nim']; // Set NIM in session
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['name'];

            // Redirect based on role
            if ($row['role'] == 'mahasiswa') {
                header('Location: pengajuan_judul.php');
            } elseif ($row['role'] == 'prodi') {
                header('Location: seleksi_judul.php');
            } elseif ($row['role'] == 'staff') {
                header('Location: surat_pengantar.php');
            }
            exit();
        } else {
            $error_message = "ID atau Password Salah!";
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
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            var nim = document.getElementById("nim").value;
            var password = document.getElementById("password").value;

            if (isNaN(nim) || nim.length != 10) {
                alert("ID harus berupa angka dengan panjang 10 digit");
                return false;
            }
            if (password.length < 5 || password.length > 10) {
                alert("Password harus 5-10 karakter");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container-login">
        <h2>Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST" onsubmit="return validateForm()">
            <input type="text" id="nim" name="nim" maxlength="10" required placeholder="NIM">
            <input type="password" id="password" name="password" minlength="5" maxlength="10" required placeholder="Password">
            <button type="submit" class="btn-cari">Login</button>
        </form>
    </div>
</body>
</html>
