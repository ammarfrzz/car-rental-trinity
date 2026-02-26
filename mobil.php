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
    <h2>Data Mobil</h2>
    <hr>

    <a href="tambah_mobil.php" class="btn btn-primary mb-3">+ Tambah Mobil</a>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Mobil</th>
            <th>Brand</th>
            <th>Harga / Hari</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $query = mysqli_query($conn, "SELECT * FROM mobil");

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td>
                <img src="assets/upload/<?= $data['foto']; ?>" width="100">
            </td>
            <td><?= $data['nama_mobil']; ?></td>
            <td><?= $data['brand']; ?></td>
            <td>Rp <?= number_format($data['harga_per_hari']); ?></td>
            <td><?= $data['status']; ?></td>
            <td>
                <a href="edit_mobil.php?id=<?= $data['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="hapus_mobil.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>

        <?php } ?>
    </table>
</div>

<?php include "include/footer.php"; ?>