<?php
session_start();
include "include/koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id='$id'"));

if (isset($_POST['update'])) {
    
    $nama = $_POST['nama_mobil'];
    $brand = $_POST['brand'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE mobil SET
        nama_mobil= '$nama',
        brand='$brand',
        harga_per_hari='$harga',
        status='$status'
        WHERE id='$id'");

        header("Location: mobil.php");
}
?>

<?php include "include/header.php"; ?>
<?php include "include/sidebar.php"; ?>

<div class="container col-md-10 p-4">
    <h2>Edit Mobil</h2>
    <hr>

    <div class="row align-items-start">
        <div class="col">

        <form method="POST">
            <div class="mb-3">
                <label>Nama Mobil</label>
                <input type="text" name="nama_mobil" 
                    value="<?= $data['nama_mobil']; ?>" 
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Brand</label>
                <input type="text" name="brand" 
                    value="<?= $data['brand']; ?>" 
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Harga per Hari</label>
                <input type="number" name="harga" 
                    value="<?= $data['harga_per_hari']; ?>" 
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="tersedia" <?= $data['status']=="tersedia"?"selected":""; ?>>Tersedia</option>
                    <option value="disewa" <?= $data['status']=="disewa"?"selected":""; ?>>Disewa</option>
                </select>
            </div>

            <button type="submit" name="update" class="btn btn-success">Update</button>
            <a href="mobil.php" class="btn btn-secondary">Kembali</a>
        </form>


        </div>
        <div class="col">

            <div class="card text-center">
                <img src="<?= "assets/upload/".$data['foto'];?>" class="card-img-top" alt="...">
            </div>

        </div>
    </div>
</div>


<?php include "include/footer.php"; ?>