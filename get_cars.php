<?php
/**
 * get_cars.php — AJAX endpoint untuk filter armada
 * Dipanggil oleh filter di index.php via fetch()
 */
include "include/koneksi.php";

$brand_filter = "";
if (isset($_GET['brand']) && $_GET['brand'] !== '') {
    $brand_filter = mysqli_real_escape_string($conn, $_GET['brand']);
    $query = mysqli_query($conn, "SELECT * FROM mobil 
                                  WHERE brand='$brand_filter' 
                                  AND status='tersedia'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM mobil 
                                  WHERE status='tersedia'");
}

$rows = [];
while ($data = mysqli_fetch_assoc($query)) {
    $rows[] = $data;
}
$totalCars = count($rows);

// ── Kirim response: section-header + car grid + view-more ──────────────────

if ($totalCars === 0): ?>
<div class="col-12">
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-car-side"></i></div>
        <h3 class="empty-title">Tidak ada armada tersedia</h3>
        <p class="empty-sub">Coba pilih brand lain atau reset filter</p>
    </div>
</div>
<?php else:
    foreach ($rows as $i => $data):
        $isHidden = ($i >= 6) ? 'car-hidden' : '';
?>
<div class="col-md-4 col-sm-6 <?= $isHidden; ?>" data-index="<?= $i; ?>">
    <div class="car-card">
        <div class="car-img-wrap">
            <img src="assets/upload/<?= htmlspecialchars($data['foto']); ?>" alt="<?= htmlspecialchars($data['nama_mobil']); ?>">
            <div class="card-img-overlay-custom"></div>
            <span class="badge-status">
                <i class="fas fa-circle" style="font-size:0.5rem; color:#4ade80;"></i>&nbsp;Tersedia
            </span>
            <span class="badge-brand"><?= htmlspecialchars($data['brand']); ?></span>
        </div>
        <div class="card-body-custom">
            <h5 class="car-name"><?= htmlspecialchars($data['nama_mobil']); ?></h5>
            <div class="car-meta">
                <i class="fas fa-building"></i>
                <?= htmlspecialchars($data['brand']); ?>
                &nbsp;·&nbsp;
                <i class="fas fa-shield-alt"></i>
                Asuransi Termasuk
            </div>
            <div class="divider-custom"></div>
            <div class="price-row">
                <div>
                    <span class="price-label">Harga Sewa</span>
                    <div class="price-value">
                        Rp <?= number_format($data['harga_per_hari']); ?>
                        <span class="price-unit">/ hari</span>
                    </div>
                </div>
                <div class="price-discount">
                    <div class="discount-badge">🔥 Hemat 10%</div>
                </div>
            </div>
            <a href="form.php?id=<?= (int)$data['id']; ?>" class="btn-booking">
                <i class="fas fa-key"></i> Booking Sekarang
            </a>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>