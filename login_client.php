<?php
session_start();
include "include/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ========== LOGIN ==========
    if ($action === 'login') {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Email dan password wajib diisi.']);
            exit;
        }

        $email_esc = mysqli_real_escape_string($conn, $email);
        $result    = mysqli_query($conn, "SELECT * FROM client WHERE email = '$email_esc' LIMIT 1");

        if ($result && mysqli_num_rows($result) === 1) {
            $client = mysqli_fetch_assoc($result);
            if (password_verify($password, $client['password'])) {
                $_SESSION['client_id']    = $client['id'];
                $_SESSION['client_nama']  = $client['nama'];
                $_SESSION['client_email'] = $client['email'];
                echo json_encode(['status' => 'success', 'message' => 'Login berhasil! Selamat datang, ' . $client['nama']]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Password salah.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email tidak terdaftar.']);
        }
        exit;
    }

    // ========== REGISTER ==========
    if ($action === 'register') {
        $nama     = trim($_POST['nama'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $no_hp    = trim($_POST['no_hp'] ?? '');

        if (empty($nama) || empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama, email, dan password wajib diisi.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
            exit;
        }

        if (strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Password minimal 6 karakter.']);
            exit;
        }

        $email_esc = mysqli_real_escape_string($conn, $email);
        $cek       = mysqli_query($conn, "SELECT id FROM client WHERE email = '$email_esc' LIMIT 1");

        if (mysqli_num_rows($cek) > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar.']);
            exit;
        }

        $nama_esc  = mysqli_real_escape_string($conn, $nama);
        $hp_esc    = mysqli_real_escape_string($conn, $no_hp);
        $hash      = password_hash($password, PASSWORD_DEFAULT);

        $insert = mysqli_query($conn, "INSERT INTO client (nama, email, password, no_hp)
                                       VALUES ('$nama_esc', '$email_esc', '$hash', '$hp_esc')");

        if ($insert) {
            // Auto-login setelah register
            $new_id = mysqli_insert_id($conn);
            $_SESSION['client_id']    = $new_id;
            $_SESSION['client_nama']  = $nama;
            $_SESSION['client_email'] = $email;
            echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil! Selamat datang, ' . $nama]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mendaftar, coba lagi.']);
        }
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Request tidak valid.']);