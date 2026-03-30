<?php
session_start();
include "include/koneksi.php";

$id = $_GET['id'];
$mobil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id='$id'"));

if (isset($_POST['booking'])) {

    $nama  = $_POST['nama'];
    $hp    = $_POST['hp'];
    $email = $_POST['email'];
    $tgl_sewa = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];

    $harga = $mobil['harga_per_hari'];

    // HITUNG JUMLAH HARI
    $start = strtotime($tgl_sewa);
    $end   = strtotime($tgl_kembali);
    $jumlah_hari = ($end - $start) / (60*60*24);

    if ($jumlah_hari <= 0) {
        echo "<script>alert('Tanggal kembali harus setelah tanggal sewa!');</script>";
    } else {

        $total = $harga * $jumlah_hari;
        $diskon = $total * 0.10;
        $grand_total = $total - $diskon;

        mysqli_query($conn, "INSERT INTO booking 
            (nama_client, no_hp, email, mobil_id,
             tanggal_sewa, tanggal_kembali, jumlah_hari,
             total_harga, diskon, grand_total,
             status_booking, status_pembayaran)
             
             VALUES
            ('$nama','$hp','$email','$id',
             '$tgl_sewa','$tgl_kembali','$jumlah_hari',
             '$total','$diskon','$grand_total',
             'pending','belum_bayar')");

        // ── WhatsApp Redirect setelah booking berhasil ──
        $nomorAdmin = "6281235958322";
        $pesan  = "Halo Admin, saya ingin konfirmasi booking mobil:\n\n";
        $pesan .= " *Detail Booking*\n";
        $pesan .= "Nama      : {$nama}\n";
        $pesan .= "No. HP    : {$hp}\n";
        $pesan .= "Email     : {$email}\n\n";
        $pesan .= " *Kendaraan*\n";
        $pesan .= "Mobil     : {$mobil['nama_mobil']}\n\n";
        $pesan .= " *Periode Sewa*\n";
        $pesan .= "Tgl Sewa  : " . date('d/m/Y', strtotime($tgl_sewa)) . "\n";
        $pesan .= "Tgl Kembali: " . date('d/m/Y', strtotime($tgl_kembali)) . "\n";
        $pesan .= "Durasi    : {$jumlah_hari} hari\n\n";
        $pesan .= " *Rincian Biaya*\n";
        $pesan .= "Harga Normal : Rp " . number_format($total, 0, ',', '.') . "\n";
        $pesan .= "Diskon 10%   : - Rp " . number_format($diskon, 0, ',', '.') . "\n";
        $pesan .= "Total Bayar  : Rp " . number_format($grand_total, 0, ',', '.') . "\n\n";
        $pesan .= "Mohon informasikan rekening untuk transfer. Terima kasih! ";

        $waUrl = "https://wa.me/{$nomorAdmin}?text=" . urlencode($pesan);

        // Simpan wa_url ke session, redirect ke halaman yang sama
        $_SESSION['wa_url'] = $waUrl;
        $_SESSION['booking_sukses'] = true;

        header("Location: form.php?id=$id&sukses=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --cream: #F5F0E8;
            --dark: #1A1A2E;
            --gold: #C9A84C;
            --gold-light: #E8D5A3;
            --muted: #6B6B7B;
            --card-bg: #FFFFFF;
            --border: #E2DDD4;
            --success-green: #2D6A4F;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            color: var(--dark);
            min-height: 100vh;
        }

        /* ── Animated background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 40% at 80% 10%, rgba(201,168,76,.12) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 10% 90%, rgba(26,26,46,.06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Layout wrapper ── */
        .page-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 16px 80px;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 28px;
            width: 100%;
            max-width: 900px;
            align-items: start;
        }

        /* ── Left panel – car info ── */
        .car-panel {
            background: var(--dark);
            border-radius: 20px;
            padding: 36px 28px;
            position: sticky;
            top: 48px;
            color: #fff;
            overflow: hidden;
        }

        .car-panel::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(201,168,76,.15);
        }

        .car-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -30px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(201,168,76,.08);
        }

        .panel-label {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 500;
            margin-bottom: 14px;
        }

        .car-name {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        /* ── Car image ── */
        .car-img-wrap {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 28px;
            z-index: 1;
            aspect-ratio: 16/9;
            background: rgba(201,168,76,.08);
            border: 1px solid rgba(201,168,76,.25);
        }

        .car-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .55s cubic-bezier(.25,.46,.45,.94);
        }

        .car-img-wrap:hover img {
            transform: scale(1.06);
        }

        /* gradient at bottom so price text stays legible */
        .car-img-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                transparent 45%,
                rgba(13,13,30,.75) 100%
            );
            pointer-events: none;
        }

        .car-img-brand {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #1A1A2E;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            z-index: 2;
        }

        .car-img-status {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(201,168,76,.35);
            color: var(--gold-light);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: #6FCF97;
            border-radius: 50%;
            animation: blink 1.6s ease-in-out infinite;
        }

        @keyframes blink {
            0%,100% { opacity: 1; }
            50%      { opacity: .3; }
        }

        /* fallback when no image */
        .car-img-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--gold);
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: .6;
        }

        .car-img-fallback i { font-size: 48px; }

        .price-block {
            position: relative;
            z-index: 1;
        }

        .price-label {
            font-size: 12px;
            letter-spacing: 1px;
            color: rgba(255,255,255,.5);
            margin-bottom: 4px;
        }

        .price-value {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--gold);
        }

        .price-per {
            font-size: 13px;
            color: rgba(255,255,255,.5);
            margin-left: 4px;
        }

        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(45,106,79,.35);
            border: 1px solid rgba(45,106,79,.6);
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 500;
            color: #6FCF97;
            margin-top: 16px;
        }

        .divider-line {
            height: 1px;
            background: rgba(255,255,255,.1);
            margin: 24px 0;
            position: relative;
            z-index: 1;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255,255,255,.6);
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .info-row i { color: var(--gold); font-size: 15px; }

        /* ── Right panel – form ── */
        .form-panel {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 4px 40px rgba(26,26,46,.07);
            border: 1px solid var(--border);
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
        }

        .section-heading {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 500;
            margin-bottom: 18px;
            margin-top: 30px;
        }

        .section-heading:first-of-type { margin-top: 0; }

        /* ── Custom inputs ── */
        .field-wrap {
            position: relative;
            margin-bottom: 20px;
        }

        .field-wrap label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .5px;
            color: var(--muted);
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold);
            font-size: 16px;
            pointer-events: none;
        }

        .input-icon-wrap input {
            padding-left: 44px !important;
        }

        .form-control-custom {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--dark);
            background: #FAFAF8;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
            -webkit-appearance: none;
        }

        .form-control-custom:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.15);
            background: #fff;
        }

        .form-control-custom::placeholder { color: #BBBBBB; }

        /* ── Date row ── */
        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── Summary box ── */
        .summary-box {
            background: #F8F6F0;
            border: 1.5px dashed var(--gold-light);
            border-radius: 14px;
            padding: 20px;
            margin-top: 30px;
            margin-bottom: 28px;
        }

        .summary-box .summary-title {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 14px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--muted);
            padding: 5px 0;
        }

        .summary-row.total {
            border-top: 1px solid var(--border);
            margin-top: 8px;
            padding-top: 12px;
            font-size: 15px;
            font-weight: 500;
            color: var(--dark);
        }

        .summary-row.total span:last-child {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--gold);
        }

        /* ── Buttons ── */
        .btn-book {
            width: 100%;
            padding: 16px;
            background: var(--dark);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: .5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background .2s, transform .15s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .btn-book::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201,168,76,.25), transparent);
            opacity: 0;
            transition: opacity .25s;
        }

        .btn-book:hover { background: #12122a; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,26,46,.2); }
        .btn-book:hover::before { opacity: 1; }
        .btn-book:active { transform: translateY(0); }

        .btn-back {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: var(--muted);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 400;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-top: 12px;
            transition: border-color .2s, color .2s;
        }

        .btn-back:hover { border-color: var(--dark); color: var(--dark); }

        /* ── Responsive ── */
        @media (max-width: 760px) {
            .booking-grid { grid-template-columns: 1fr; }
            .car-panel { position: static; }
            .date-row { grid-template-columns: 1fr; }
            .form-panel { padding: 28px 20px; }
            .page-wrapper { padding: 24px 12px 60px; }
        }

        /* ── Entrance animation ── */
        .car-panel, .form-panel {
            animation: fadeUp .5s ease both;
        }
        .form-panel { animation-delay: .1s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
<div class="booking-grid">

    <!-- ── LEFT: Car Info Panel ── -->
    <div class="car-panel">
        <p class="panel-label">Kendaraan Pilihan</p>
        <h2 class="car-name"><?= htmlspecialchars($mobil['nama_mobil']); ?></h2>

        <div class="car-img-wrap">
            <?php if (!empty($mobil['foto'])): ?>
                <img
                    src="assets/upload/<?= htmlspecialchars($mobil['foto']); ?>"
                    alt="<?= htmlspecialchars($mobil['nama_mobil']); ?>"
                    onerror="this.closest('.car-img-wrap').innerHTML='<div class=\'car-img-fallback\'><i class=\'bi bi-car-front-fill\'></i><span>Foto tidak tersedia</span></div>'"
                >
                <span class="car-img-brand"><?= htmlspecialchars($mobil['brand']); ?></span>
                <span class="car-img-status">
                    <span class="status-dot"></span> Tersedia
                </span>
            <?php else: ?>
                <div class="car-img-fallback">
                    <i class="bi bi-car-front-fill"></i>
                    <span>Foto tidak tersedia</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="price-block">
            <p class="price-label">Harga Sewa</p>
            <div>
                <span class="price-value">Rp <?= number_format($mobil['harga_per_hari']); ?></span>
                <span class="price-per">/ hari</span>
            </div>
            <div class="discount-badge">
                <i class="bi bi-tag-fill"></i>
                Diskon 10% sudah termasuk
            </div>
        </div>

        <div class="divider-line"></div>

        <div class="info-row"><i class="bi bi-shield-check"></i> Asuransi perjalanan inklusif</div>
        <div class="info-row"><i class="bi bi-clock"></i> Konfirmasi dalam 1×24 jam</div>
        <div class="info-row"><i class="bi bi-credit-card"></i> Bayar via transfer bank</div>
        <div class="info-row"><i class="bi bi-headset"></i> Dukungan 24/7</div>
    </div>

    <!-- ── RIGHT: Booking Form ── -->
    <div class="form-panel">
        <h1 class="form-title">Form Booking</h1>
        <p class="form-subtitle">Isi data diri Anda untuk melanjutkan pemesanan.</p>

        <form method="POST" id="bookingForm">

            <p class="section-heading">Informasi Penyewa</p>

            <div class="field-wrap">
                <label>Nama Lengkap</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-person"></i>
                    <input type="text" name="nama" class="form-control-custom" placeholder="Masukkan nama lengkap" required>
                </div>
            </div>

            <div class="field-wrap">
                <label>Nomor HP / WhatsApp</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-telephone"></i>
                    <input type="text" name="hp" class="form-control-custom" placeholder="Contoh: 08123456789" required>
                </div>
            </div>

            <div class="field-wrap">
                <label>Alamat Email</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" class="form-control-custom" placeholder="nama@email.com" required>
                </div>
            </div>

            <p class="section-heading">Periode Sewa</p>

            <div class="date-row">
                <div class="field-wrap">
                    <label>Tanggal Sewa</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-calendar-event"></i>
                        <input type="date" name="tgl_sewa" id="tgl_sewa" class="form-control-custom" required>
                    </div>
                </div>

                <div class="field-wrap">
                    <label>Tanggal Kembali</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control-custom" required>
                    </div>
                </div>
            </div>

            <!-- Live summary -->
            <div class="summary-box" id="summaryBox" style="display:none;">
                <p class="summary-title">Estimasi Biaya</p>
                <div class="summary-row">
                    <span>Durasi sewa</span>
                    <span id="sumDays">— hari</span>
                </div>
                <div class="summary-row">
                    <span>Harga normal</span>
                    <span id="sumNormal">Rp —</span>
                </div>
                <div class="summary-row">
                    <span>Diskon 10%</span>
                    <span id="sumDiskon" style="color:#2D6A4F;">− Rp —</span>
                </div>
                <div class="summary-row total">
                    <span>Total Pembayaran</span>
                    <span id="sumTotal">Rp —</span>
                </div>
            </div>

            <button type="submit" name="booking" class="btn-book">
                <i class="bi bi-check-circle"></i>
                Booking Sekarang
            </button>

            <a href="index.php" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Daftar Mobil
            </a>

        </form>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const hargaPerHari = <?= $mobil['harga_per_hari']; ?>;
    const tglSewa    = document.getElementById('tgl_sewa');
    const tglKembali = document.getElementById('tgl_kembali');
    const summaryBox = document.getElementById('summaryBox');

    function fmt(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }

    function updateSummary() {
        if (!tglSewa.value || !tglKembali.value) { summaryBox.style.display = 'none'; return; }
        const diff = (new Date(tglKembali.value) - new Date(tglSewa.value)) / 86400000;
        if (diff <= 0) { summaryBox.style.display = 'none'; return; }

        const normal    = hargaPerHari * diff;
        const diskon    = normal * 0.10;
        const grandTotal = normal - diskon;

        document.getElementById('sumDays').textContent   = diff + ' hari';
        document.getElementById('sumNormal').textContent = fmt(normal);
        document.getElementById('sumDiskon').textContent = '− ' + fmt(diskon);
        document.getElementById('sumTotal').textContent  = fmt(grandTotal);

        summaryBox.style.display = 'block';
    }

    tglSewa.addEventListener('change', function() {
        tglKembali.min = this.value;
        updateSummary();
    });
    tglKembali.addEventListener('change', updateSummary);

    // Set today as min for start date
    const today = new Date().toISOString().split('T')[0];
    tglSewa.min = today;

    <?php if (!empty($_SESSION['booking_sukses'])): ?>
        <?php
            $waLink = $_SESSION['wa_url'];
            unset($_SESSION['wa_url']);
            unset($_SESSION['booking_sukses']);
        ?>
        // Buka WA saat halaman load (setelah redirect) — tidak diblokir browser
        window.location.href = "<?= addslashes($waLink) ?>";
    <?php endif; ?>
</script>
</body>
</html>