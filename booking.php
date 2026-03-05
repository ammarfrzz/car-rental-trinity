<?php
session_start();
include "include/koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['konfirmasi'])) {
    $id = $_GET['konfirmasi'];
    mysqli_query($conn, "UPDATE booking SET status_booking='confirmed' WHERE id='$id'");
    header("Location: booking.php");
}

if (isset($_GET['bayar'])) {
    $id = $_GET['bayar'];
    mysqli_query($conn, "UPDATE booking SET status_pembayaran='paid' WHERE id='$id'");
    header("Location: booking.php");
}

if (isset($_GET['selesai'])) {
    $id = $_GET['selesai'];
    mysqli_query($conn, "UPDATE booking SET status_booking='completed' WHERE id='$id'");
    header("Location: booking.php");
}
?>

<?php include "include/header.php"; ?>
<?php include "include/sidebar.php"; ?>

<div class="col-md-10 p-4">
    <h2>Data Booking</h2>
    <hr>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Client</th>
                        <th>Mobil</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status Booking</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "
                    SELECT booking.*, mobil.nama_mobil 
                    FROM booking
                    JOIN mobil ON booking.mobil_id = mobil.id
                    ORDER BY booking.id DESC
                ");
                
                while ($row = mysqli_fetch_assoc($query)) {
                ?>

<tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_client']; ?></td>
                        <td><?= $row['nama_mobil']; ?></td>
                        <td>
                            <?= $row['tanggal_sewa']; ?> <br>
                            s/d <?= $row['tanggal_kembali']; ?>
                        </td>
                        <td>Rp <?= number_format($row['grand_total']); ?></td>
                        <td><?= $row['status_booking']; ?></td>
                        <td><?= $row['status_pembayaran']; ?></td>
                        <td>

                        <?php if ($row['status_booking'] == 'pending') { ?>
                            <a href="?konfirmasi=<?= $row['id']; ?>" 
                               class="btn btn-warning btn-sm">
                               Konfirmasi
                            </a>
                        <?php } ?>

                        <?php if ($row['status_pembayaran'] == 'belum_bayar') { ?>
                            <a href="?bayar=<?= $row['id']; ?>" 
                               class="btn btn-success btn-sm">
                               Verifikasi
                            </a>
                        <?php } ?>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>