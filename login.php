<?php
session_start();
include "include/koneksi.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin 
                                  WHERE username='$username' 
                                  AND password='$password'");

    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $_SESSION['login'] = true;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Login gagal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-4 mx-auto">
        <div class="card shadow">
            <div class="card-header text-center">
                <h4>Login Admin</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>