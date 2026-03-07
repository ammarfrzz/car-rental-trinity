<?php
session_start();
include "include/koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include "include/header.php"; ?>
<?php include "include/sidebar.php"; ?>

<?php
// Total Client
$q_client = mysqli_query($conn, "
    SELECT COUNT(DISTINCT CONCAT(nama_client,email,no_hp)) as total 
    FROM booking
");
$total_client = mysqli_fetch_assoc($q_client)['total'] ?? 0;

// Total Booking
$q_booking = mysqli_query($conn, "SELECT COUNT(id) as total FROM booking");
$total_booking = mysqli_fetch_assoc($q_booking)['total'] ?? 0;

// Total Pendapatan
$q_pendapatan = mysqli_query($conn, "
    SELECT SUM(grand_total) as total 
    FROM booking 
    WHERE status_pembayaran='paid'
");
$total_pendapatan = mysqli_fetch_assoc($q_pendapatan)['total'] ?? 0;

// Booking Pending
$q_pending = mysqli_query($conn, "
    SELECT COUNT(id) as total 
    FROM booking 
    WHERE status_booking='pending'
");
$total_pending = mysqli_fetch_assoc($q_pending)['total'] ?? 0;

// Mobil Aktif
$q_mobil = mysqli_query($conn, "
    SELECT COUNT(id) as total 
    FROM mobil
");
$total_mobil = mysqli_fetch_assoc($q_mobil)['total'] ?? 0;
?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="col-md-10 p-4">
    <h2 style="color: blue;">Dashboard</h2>
    <br>

    <?php
    $total_booking = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM booking"));
    $booking_pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM booking WHERE status_booking='pending'"));
    $mobil_tersedia = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM mobil WHERE status='tersedia'"));
    ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-black p-3">
                <h5>Total Booking</h5>
                <h3><?= $total_booking; ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary text-black p-3">
                <h5>Booking Pending</h5>
                <h3><?= $booking_pending; ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary text-black p-3">
                <h5>Mobil Tersedia</h5>
                <h3><?= $mobil_tersedia; ?></h3>
            </div>
        </div>
    </div>

    <div class="row mt-4">

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Pendapatan 12 Bulan</h6>
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Status Booking</h6>
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
// DATA GRAFIK BULANAN
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
file_put_contents("data2.txt", $data_bulan);
// STATUS BOOKING
$q_status = mysqli_query($conn,"
    SELECT 
    SUM(status_booking='pending') as pending,
    SUM(status_booking='confirmed') as confirmed,
    SUM(status_booking='completed') as completed
    FROM booking
");
$status = mysqli_fetch_assoc($q_status);
?>

<script>
const ctx = document.getElementById('chartPendapatan');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($label_bulan); ?>,
        datasets: [{
            label: 'Pendapatan',
            data: <?= json_encode($data_bulan); ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.1)',
            fill: true,
            tension: 0.4
        }]
    }
});

const ctx2 = document.getElementById('chartStatus');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['Pending','Confirmed','Completed'],
        datasets: [{
            data: [
                <?= $status['pending'] ?? 0; ?>,
                <?= $status['confirmed'] ?? 0; ?>,
                <?= $status['completed'] ?? 0; ?>
            ],
            backgroundColor: ['#ffc107','#0d6efd','#198754']
        }]
    }
});
</script>

<?php include "include/footer.php"; ?>