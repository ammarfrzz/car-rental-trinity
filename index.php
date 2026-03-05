<?php
include "include/koneksi.php";

$brand_filter = "";
if (isset($_GET['brand'])) {
    $brand_filter = $_GET['brand'];
    $query = mysqli_query($conn, "SELECT * FROM mobil 
                                  WHERE brand='$brand_filter' 
                                  AND status='tersedia'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM mobil 
                                  WHERE status='tersedia'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Rental DPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2 class="text-center mb-3">🚗 Car Rental DPK</h2>

    <div class="alert alert-success text-center">
        🎉 Promo Diskon 10% untuk semua mobil!
    </div>

    <!-- FILTER BRAND -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="brand" class="form-control">
                    <option value="">Semua Brand</option>

                    <?php
                    $brand_query = mysqli_query($conn, "SELECT DISTINCT brand FROM mobil");
                    while ($b = mysqli_fetch_assoc($brand_query)) {
                        echo "<option value='{$b['brand']}'>{$b['brand']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="index.php" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- LIST MOBIL -->
    <div class="row">
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <img src="assets/upload/<?= $data['foto']; ?>" 
                     class="card-img-top" height="200" style="object-fit:cover;">
                <div class="card-body">
                    <h5><?= $data['nama_mobil']; ?></h5>
                    <p>Brand: <?= $data['brand']; ?></p>
                    <p>Harga / Hari: 
                        <strong>Rp <?= number_format($data['harga_per_hari']); ?></strong>
                    </p>
                    <a href="form.php?id=<?= $data['id']; ?>" 
                       class="btn btn-success w-100">
                        Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

</div>

</body>
</html>