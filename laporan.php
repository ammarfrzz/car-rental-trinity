<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "include/koneksi.php";
include "include/header.php";
include "include/sidebar.php";


// TOTAL PENDAPATAN
$total_pendapatan = mysqli_query($conn, "
    SELECT SUM(grand_total) as total 
    FROM booking 
    WHERE status_pembayaran='paid'
");
$data_total = mysqli_fetch_assoc($total_pendapatan);


// PENDAPATAN BULAN INI
$bulan_ini = date('m');
$tahun_ini = date('Y');

$pendapatan_bulan = mysqli_query($conn, "
    SELECT SUM(grand_total) as total 
    FROM booking 
    WHERE status_pembayaran='paid'
    AND MONTH(tanggal_sewa)='$bulan_ini'
    AND YEAR(tanggal_sewa)='$tahun_ini'
");
$data_bulan = mysqli_fetch_assoc($pendapatan_bulan);
file_put_contents("data.txt", json_encode($data_bulan));

// TOTAL BOOKING
$total_booking = mysqli_query($conn, "
    SELECT COUNT(id) as total FROM booking
");
$data_booking = mysqli_fetch_assoc($total_booking);


// MOBIL PALING SERING DISEWA
$mobil_populer = mysqli_query($conn, "
    SELECT mobil.nama_mobil, COUNT(booking.id) as total_sewa
    FROM booking
    JOIN mobil ON booking.mobil_id = mobil.id
    GROUP BY mobil_id
    ORDER BY total_sewa DESC
    LIMIT 1
");
$data_mobil = mysqli_fetch_assoc($mobil_populer);

$data_bulan = [];
$label_bulan = [];

for ($i = 1; $i <= 12; $i++) {
    $query = mysqli_query($conn, "
        SELECT SUM(grand_total) as total 
        FROM booking 
        WHERE status_pembayaran='paid'
        AND MONTH(tanggal_sewa)='$i'
    ");
    $row = mysqli_fetch_assoc($query);
    $data_bulan[] = $row['total'] ?? 0;
    $label_bulan[] = date("M", mktime(0,0,0,$i,1));
}
?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="col-md-10 p-4">
    <h4>Laporan</h4>
    <hr>

    <div class="row">

        <div class="col-md-3">
            <div class="card bg-primary text-black h-100">
                <div class="card-body">
                    <h6>Total Pendapatan</h6>
                    <h5>Rp <?= number_format($data_total['total'] ?? 0); ?></h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-black h-100">
                <div class="card-body">
                    <h6>Pendapatan Bulan Ini</h6>
                    <h5>Rp <?= number_format($data_bulan['total'] ?? 0); ?></h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-black h-100">
                <div class="card-body">
                    <h6>Total Booking</h6>
                    <h5><?= $data_booking['total']; ?></h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-black h-100">
                <div class="card-body">
                    <h6>Mobil Terfavorit</h6>
                    <h5>
                        <?= $data_mobil['nama_mobil'] ?? '-'; ?>
                    </h5>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card-body">
                 <h5>Pendapatan Bulanan</h5>
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const ctx = document.getElementById('chartPendapatan');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($label_bulan); ?>,
        datasets: [{
            label: 'Pendapatan Bulanan',
            data: <?= json_encode($data_bulan); ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.1)',
            fill: true,
            tension: 0.4
        }]
    }
});
</script>

<?php include "include/footer.php"; ?>