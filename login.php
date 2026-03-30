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
        echo "<script>showError();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0; padding: 0;
            box-sizing: border-box;
        }

        :root {
            --blue-deep:   #0f2d6e;
            --blue-mid:    #1a4db8;
            --blue-light:  #2f6bdb;
            --blue-pale:   #dce9ff;
            --blue-ghost:  #f0f5ff;
            --white:       #ffffff;
            --text-dark:   #0d1b3e;
            --text-mid:    #4a5f8a;
            --text-muted:  #8fa3c8;
            --border:      #d0ddf5;
            --shadow-blue: rgba(26, 77, 184, 0.18);
        }

        body {
            min-height: 100vh;
            background: var(--blue-ghost);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
            overflow: hidden;
        }

        /* ── Background Left Panel ───────────────── */
        .bg-left {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 46%;
            background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #2351c4 100%);
            z-index: 0;
        }

        .bg-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 20%, rgba(255,255,255,0.08) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(15,45,110,0.6) 0%, transparent 60%);
        }

        .deco-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .deco-circle-1 { width:340px; height:340px; top:-80px; left:-80px; }
        .deco-circle-2 { width:220px; height:220px; top:60px; left:60px; border-color:rgba(255,255,255,0.07); }
        .deco-circle-3 { width:280px; height:280px; bottom:-60px; right:-40px; }
        .deco-circle-4 { width:160px; height:160px; bottom:40px; right:40px; border-color:rgba(255,255,255,0.07); }

        .dot {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            animation: floatDot 6s ease-in-out infinite alternate;
        }
        .dot-1 { width:8px; height:8px; top:22%; left:18%; animation-delay:0s; }
        .dot-2 { width:5px; height:5px; top:55%; left:72%; animation-delay:1.5s; }
        .dot-3 { width:10px; height:10px; top:75%; left:25%; animation-delay:3s; }
        .dot-4 { width:6px; height:6px; top:38%; left:60%; animation-delay:0.8s; }

        @keyframes floatDot {
            from { transform: translateY(0); opacity: 0.6; }
            to   { transform: translateY(-14px); opacity: 1; }
        }

        /* ── Brand Panel ─────────────────────────── */
        .brand-panel {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 46%;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 10%;
        }

        .brand-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 32px;
        }

        .brand-panel h2 {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .brand-panel p {
            font-size: 14.5px;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
            max-width: 280px;
            font-weight: 300;
        }

        .brand-divider {
            width: 40px; height: 2px;
            background: rgba(255,255,255,0.3);
            margin: 24px 0;
            border-radius: 2px;
        }

        .brand-stats {
            display: flex;
            gap: 32px;
            margin-top: 40px;
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #fff;
            font-weight: 600;
            display: block;
        }

        .stat-label {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* ── Form Panel ──────────────────────────── */
        .form-panel {
            position: relative;
            z-index: 2;
            margin-left: 46%;
            width: 54%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(28px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .card-eyebrow {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--blue-mid);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .card-eyebrow::before {
            content: '';
            width: 20px; height: 2px;
            background: var(--blue-mid);
            border-radius: 2px;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .card-sub {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 40px;
            font-weight: 300;
        }

        .form-group { margin-bottom: 22px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-mid);
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-wrap .ico {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.2s;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            color: var(--text-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            padding: 13px 15px 13px 44px;
            outline: none;
            transition: border-color 0.22s, box-shadow 0.22s;
            box-shadow: 0 1px 3px rgba(15,45,110,0.05);
        }

        input::placeholder { color: var(--text-muted); }

        input:focus {
            border-color: var(--blue-light);
            box-shadow: 0 0 0 4px rgba(47,107,219,0.1);
        }

        .toggle-pw {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 0; line-height: 1;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--blue-mid); }

        /* Error box */
        .error-box {
            display: none;
            align-items: center;
            gap: 10px;
            background: #fff4f4;
            border: 1px solid #ffc5c5;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            font-size: 13px;
            color: #c0392b;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%,60%  { transform: translateX(-6px); }
            40%,80%  { transform: translateX(6px); }
        }

        /* Button */
        .btn-login {
            width: 100%;
            margin-top: 8px;
            background: linear-gradient(135deg, var(--blue-mid) 0%, var(--blue-light) 100%);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.04em;
            border: none;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.25s;
            box-shadow: 0 4px 16px var(--shadow-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.12) 0%, transparent 100%);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(26,77,184,0.35);
        }

        .btn-login:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .bg-left, .brand-panel { display: none; }
            .form-panel { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>

<div class="bg-left">
    <div class="deco-circle deco-circle-1"></div>
    <div class="deco-circle deco-circle-2"></div>
    <div class="deco-circle deco-circle-3"></div>
    <div class="deco-circle deco-circle-4"></div>
    <div class="dot dot-1"></div>
    <div class="dot dot-2"></div>
    <div class="dot dot-3"></div>
    <div class="dot dot-4"></div>
</div>

<div class="brand-panel">
    <div class="brand-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
            <path d="M2 17l10 5 10-5"/>
            <path d="M2 12l10 5 10-5"/>
        </svg>
    </div>
    <h2>Sistem<br>Manajemen<br>Admin</h2>
    <div class="brand-divider"></div>
    <p>Platform khusus admin untuk mengelola seluruh operasional sistem dengan aman dan efisien.</p>
    <div class="brand-stats">
        <div class="stat-item">
            <span class="stat-num">99.9%</span>
            <span class="stat-label">Uptime</span>
        </div>
        <div class="stat-item">
            <span class="stat-num">256-bit</span>
            <span class="stat-label">Enkripsi</span>
        </div>
        <div class="stat-item">
            <span class="stat-num">24/7</span>
            <span class="stat-label">Akses</span>
        </div>
    </div>
</div>

<div class="form-panel">
    <div class="login-card">

        <div class="card-eyebrow">Admin Panel</div>
        <h1 class="card-title">Masuk ke<br>Akun Anda</h1>
        <p class="card-sub">Silakan isi username dan password untuk melanjutkan</p>

        <div class="error-box" id="errorBox">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Username atau password salah. Silakan coba lagi.
        </div>

        <form method="POST" autocomplete="off">

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <input type="text" id="username" name="username"
                        placeholder="Masukkan username Anda" required autocomplete="username">
                    <svg class="ico" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password"
                        placeholder="Masukkan password Anda" required autocomplete="current-password">
                    <svg class="ico" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <button type="button" class="toggle-pw" onclick="togglePw()">
                        <svg id="eye-ico" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn-login">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk Sekarang
            </button>

        </form>

        <div class="form-footer">
            &copy; <?= date('Y') ?> Admin Panel &nbsp;&middot;&nbsp; Akses Terbatas
        </div>

    </div>
</div>

<script>
    function togglePw() {
        const pw  = document.getElementById('password');
        const ico = document.getElementById('eye-ico');
        const show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        ico.innerHTML = show
            ? `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`
            : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    }

    function showError() {
        document.getElementById('errorBox').style.display = 'flex';
    }
</script>

</body>
</html>