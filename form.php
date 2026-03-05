<?php
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

        echo "<script>alert('Booking berhasil! Silakan upload bukti transfer di admin.'); 
              window.location='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Form Booking</h2>
    <hr>

    <div class="card mb-4">
        <div class="card-body">
            <h5><?= $mobil['nama_mobil']; ?></h5>
            <p>Harga / Hari: Rp <?= number_format($mobil['harga_per_hari']); ?></p>
            <p class="text-success">Diskon 10% berlaku</p>
        </div>
    </div>

    <form method="POST">

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="hp" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Sewa</label>
            <input type="date" name="tgl_sewa" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Kembali</label>
            <input type="date" name="tgl_kembali" class="form-control" required>
        </div>

        <button type="submit" name="booking" class="btn btn-success">
            Booking Sekarang
        </button>

        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>