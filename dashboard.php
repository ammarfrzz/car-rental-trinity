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

<div class="col-md-10 p-4">
    <h2>Dashboard</h2>
    <hr>

    <?php
    $total_booking = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM booking"));
    $booking_pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM booking WHERE status_booking='pending'"));
    $mobil_tersedia = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM mobil WHERE status='tersedia'"));
    ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                <h5>Total Booking</h5>
                <h3><?= $total_booking; ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white p-3">
                <h5>Booking Pending</h5>
                <h3><?= $booking_pending; ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white p-3">
                <h5>Mobil Tersedia</h5>
                <h3><?= $mobil_tersedia; ?></h3>
            </div>
        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>