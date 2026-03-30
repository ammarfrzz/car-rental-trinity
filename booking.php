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

<style>
  .booking-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 36px 36px 48px;
    min-height: 100vh;
    background: #f0f5ff;
    flex: 1;
  }

  .page-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 28px;
  }

  .page-header-icon {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.30);
    flex-shrink: 0;
  }

  .page-header-icon i {
    color: #fff;
    font-size: 20px;
  }

  .page-header-text h1 {
    font-size: 22px;
    font-weight: 700;
    color: #1a3a6b;
    margin: 0 0 3px;
    letter-spacing: -0.01em;
  }

  .page-header-text p {
    font-size: 13px;
    color: #94a3b8;
    margin: 0;
    font-weight: 500;
  }

  .booking-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(26, 58, 107, 0.09);
    border: 1px solid #e2eaf7;
    overflow: hidden;
  }

  .booking-card-header {
    padding: 20px 28px;
    background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .booking-card-header h5 {
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    margin: 0;
    letter-spacing: 0.01em;
  }

  .booking-card-header span {
    font-size: 12px;
    color: rgba(255,255,255,0.65);
    font-weight: 500;
  }

  .booking-card-body {
    padding: 0;
    overflow-x: auto;
  }

  .booking-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
  }

  .booking-table thead tr {
    background: #f0f5ff;
    border-bottom: 2px solid #dbeafe;
  }

  .booking-table thead th {
    padding: 14px 18px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #1a3a6b;
    white-space: nowrap;
  }

  .booking-table tbody tr {
    border-bottom: 1px solid #f0f5ff;
    transition: background 0.16s;
  }

  .booking-table tbody tr:last-child {
    border-bottom: none;
  }

  .booking-table tbody tr:hover {
    background: #f8faff;
  }

  .booking-table tbody td {
    padding: 14px 18px;
    color: #334155;
    vertical-align: middle;
    line-height: 1.5;
  }

  .row-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px; height: 26px;
    border-radius: 8px;
    background: #dbeafe;
    color: #1a3a6b;
    font-size: 11.5px;
    font-weight: 700;
  }

  .client-name {
    font-weight: 600;
    color: #1a3a6b;
  }

  .car-name {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #2563eb;
    font-weight: 500;
  }

  .car-name i { font-size: 12px; }

  .date-range {
    font-size: 12.5px;
    color: #475569;
  }

  .date-range .date-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #94a3b8;
    display: block;
    margin-bottom: 2px;
  }

  .date-range .date-sep {
    font-size: 10px;
    color: #94a3b8;
    font-weight: 500;
    display: block;
    margin-top: 2px;
  }

  .total-amount {
    font-weight: 700;
    color: #1a3a6b;
    font-size: 13.5px;
  }

  .badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: 0.02em;
  }

  .badge-status i { font-size: 10px; }

  /* booking status */
  .badge-pending   { background: #fef9c3; color: #854d0e; }
  .badge-confirmed { background: #dbeafe; color: #1e40af; }
  .badge-completed { background: #dcfce7; color: #15803d; }

  /* payment status */
  .badge-belum  { background: #fee2e2; color: #b91c1c; }
  .badge-paid   { background: #dcfce7; color: #15803d; }

  /* ── Action buttons ── */
  .btn-konfirmasi {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(245,158,11,0.28);
    transition: opacity 0.18s, transform 0.18s, box-shadow 0.18s;
    white-space: nowrap;
  }

  .btn-konfirmasi:hover {
    opacity: 0.88;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(245,158,11,0.38);
    color: #fff;
  }

  .btn-verifikasi {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #fff;
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(37,99,235,0.28);
    transition: opacity 0.18s, transform 0.18s, box-shadow 0.18s;
    white-space: nowrap;
    margin-left: 4px;
  }

  .btn-verifikasi:hover {
    opacity: 0.88;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37,99,235,0.38);
    color: #fff;
  }

  /* Empty state */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
  }

  .empty-state i {
    font-size: 42px;
    color: #dbeafe;
    display: block;
    margin-bottom: 14px;
  }

  .empty-state p {
    font-size: 14px;
    font-weight: 500;
    margin: 0;
  }
</style>

<div class="booking-wrapper">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-icon">
      <i class="fa-solid fa-calendar-check"></i>
    </div>
    <div class="page-header-text">
      <h1>Data Booking</h1>
      <p>Kelola dan pantau seluruh transaksi booking kendaraan</p>
    </div>
  </div>

  <!-- Booking Card -->
  <div class="booking-card">
    <div class="booking-card-header">
      <h5><i class="fa-solid fa-list-ul me-2"></i>Daftar Transaksi Booking</h5>
      <span>Diurutkan terbaru</span>
    </div>
    <div class="booking-card-body">
      <table class="booking-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Client</th>
            <th>Mobil</th>
            <th>Tanggal Sewa</th>
            <th>Total Pembayaran</th>
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

          $rowCount = mysqli_num_rows($query);

          if ($rowCount == 0): ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <i class="fa-solid fa-calendar-xmark"></i>
                <p>Belum ada data booking saat ini.</p>
              </div>
            </td>
          </tr>
          <?php else:
          while ($row = mysqli_fetch_assoc($query)):

            // Booking status badge
            $sb = $row['status_booking'];
            if ($sb == 'pending')        $sb_class = 'badge-pending';
            elseif ($sb == 'confirmed')  $sb_class = 'badge-confirmed';
            elseif ($sb == 'completed')  $sb_class = 'badge-completed';
            else                          $sb_class = 'badge-pending';

            $sb_icon = match($sb) {
              'pending'   => 'fa-clock',
              'confirmed' => 'fa-circle-check',
              'completed' => 'fa-flag-checkered',
              default     => 'fa-clock'
            };

            // Payment status badge
            $sp = $row['status_pembayaran'];
            $sp_class = ($sp == 'paid') ? 'badge-paid' : 'badge-belum';
            $sp_icon  = ($sp == 'paid') ? 'fa-check-circle' : 'fa-hourglass-half';
            $sp_label = ($sp == 'paid') ? 'Lunas' : 'Belum Bayar';
          ?>
          <tr>
            <td><span class="row-num"><?= $no++; ?></span></td>
            <td><span class="client-name"><?= htmlspecialchars($row['nama_client']); ?></span></td>
            <td>
              <span class="car-name">
                <?= htmlspecialchars($row['nama_mobil']); ?>
              </span>
            </td>
            <td>
              <div class="date-range">
                <span class="date-label">Mulai</span>
                <?= $row['tanggal_sewa']; ?>
                <span class="date-sep">↳ s/d &nbsp;<?= $row['tanggal_kembali']; ?></span>
              </div>
            </td>
            <td><span class="total-amount">Rp <?= number_format($row['grand_total']); ?></span></td>
            <td>
              <span class="badge-status <?= $sb_class; ?>">
                <i class="fa-solid <?= $sb_icon; ?>"></i>
                <?= ucfirst($sb); ?>
              </span>
            </td>
            <td>
              <span class="badge-status <?= $sp_class; ?>">
                <i class="fa-solid <?= $sp_icon; ?>"></i>
                <?= $sp_label; ?>
              </span>
            </td>
            <td>
              <?php if ($row['status_booking'] == 'pending'): ?>
                <a href="?konfirmasi=<?= $row['id']; ?>" class="btn-konfirmasi">
                  <i class="fa-solid fa-circle-check"></i> Konfirmasi
                </a>
              <?php endif; ?>

              <?php if ($row['status_pembayaran'] == 'belum_bayar'): ?>
                <a href="?bayar=<?= $row['id']; ?>" class="btn-verifikasi">
                  <i class="fa-solid fa-shield-check"></i> Verifikasi
                </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include "include/footer.php"; ?>