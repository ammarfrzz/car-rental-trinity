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

<style>
  /* ── Page wrapper ── */
  .client-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 36px 36px 48px;
    min-height: 100vh;
    background: #f0f5ff;
    flex: 1;
  }

  /* ── Page header ── */
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

  /* ── Card ── */
  .client-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(26, 58, 107, 0.09);
    border: 1px solid #e2eaf7;
    overflow: hidden;
  }

  .client-card-header {
    padding: 20px 28px;
    background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .client-card-header h5 {
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    margin: 0;
    letter-spacing: 0.01em;
  }

  .client-card-header span {
    font-size: 12px;
    color: rgba(255,255,255,0.65);
    font-weight: 500;
  }

  .client-card-body {
    padding: 0;
    overflow-x: auto;
  }

  /* ── Table ── */
  .client-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
  }

  .client-table thead tr {
    background: #f0f5ff;
    border-bottom: 2px solid #dbeafe;
  }

  .client-table thead th {
    padding: 14px 18px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #1a3a6b;
    white-space: nowrap;
  }

  .client-table tbody tr {
    border-bottom: 1px solid #f0f5ff;
    transition: background 0.16s;
  }

  .client-table tbody tr:last-child {
    border-bottom: none;
  }

  .client-table tbody tr:hover {
    background: #f8faff;
  }

  .client-table tbody td {
    padding: 14px 18px;
    color: #334155;
    vertical-align: middle;
    line-height: 1.5;
  }

  /* Row number badge */
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

  /* Client name */
  .client-name {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .client-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .client-avatar i {
    color: #fff;
    font-size: 13px;
  }

  .client-name-text {
    font-weight: 600;
    color: #1a3a6b;
  }

  /* Email & phone */
  .info-cell {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #475569;
    font-size: 13px;
  }

  .info-cell i {
    color: #93c5fd;
    font-size: 12px;
    flex-shrink: 0;
  }

  /* Total booking badge */
  .total-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 13px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #dbeafe;
    color: #1e40af;
  }

  .total-badge i { font-size: 11px; }

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

<div class="client-wrapper">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-icon">
      <i class="fa-solid fa-users"></i>
    </div>
    <div class="page-header-text">
      <h1>Data Client</h1>
      <p>Daftar seluruh client yang telah melakukan booking kendaraan</p>
    </div>
  </div>

  <!-- Client Card -->
  <div class="client-card">
    <div class="client-card-header">
      <h5><i class="fa-solid fa-list-ul me-2"></i>Daftar Client</h5>
      <span>Diurutkan berdasarkan total booking terbanyak</span>
    </div>
    <div class="client-card-body">
      <table class="client-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Client</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Total Booking</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $query = mysqli_query($conn, "
              SELECT nama_client, email, no_hp, COUNT(id) as total_booking
              FROM booking
              GROUP BY nama_client, email, no_hp
              ORDER BY total_booking DESC");

          $rowCount = mysqli_num_rows($query);

          if ($rowCount == 0): ?>
          <tr>
            <td colspan="5">
              <div class="empty-state">
                <i class="fa-solid fa-users-slash"></i>
                <p>Belum ada data client saat ini.</p>
              </div>
            </td>
          </tr>
          <?php else:
          while ($row = mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><span class="row-num"><?= $no++; ?></span></td>
            <td>
              <div class="client-name">
                <div class="client-avatar">
                  <i class="fa-solid fa-user"></i>
                </div>
                <span class="client-name-text"><?= htmlspecialchars($row['nama_client']); ?></span>
              </div>
            </td>
            <td>
              <div class="info-cell">
                <i class="fa-solid fa-envelope"></i>
                <?= htmlspecialchars($row['email']); ?>
              </div>
            </td>
            <td>
              <div class="info-cell">
                <i class="fa-solid fa-phone"></i>
                <?= htmlspecialchars($row['no_hp']); ?>
              </div>
            </td>
            <td>
              <span class="total-badge">
                <i class="fa-solid fa-calendar-check"></i>
                <?= $row['total_booking']; ?> Booking
              </span>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include "include/footer.php"; ?>