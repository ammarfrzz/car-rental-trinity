<?php
session_start();
include "include/koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama  = $_POST['nama_mobil'];
    $brand = $_POST['brand'];
    $harga = $_POST['harga'];
    $status = "tersedia";

    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];

    move_uploaded_file($tmp, "assets/upload/" . $foto);

    mysqli_query($conn, "INSERT INTO mobil 
        (nama_mobil, brand, harga_per_hari, status, foto)
        VALUES ('$nama','$brand','$harga','$status','$foto')");

    header("Location: mobil.php");
}
?>

<?php include "include/header.php"; ?>
<?php include "include/sidebar.php"; ?>

<div class="col-md-10 p-4">
    <h2>Tambah Mobil</h2>
    <hr>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Mobil</label>
            <input type="text" name="nama_mobil" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Harga per Hari</label>
            <input type="number" min="0" name="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Foto Mobil</label>
            <input type="file" name="foto" class="form-control" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
        <a href="mobil.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include "include/footer.php"; ?>