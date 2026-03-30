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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  :root {
    --blue-deep:   #1a3a6b;
    --blue-mid:    #2563eb;
    --blue-light:  #eff6ff;
    --blue-accent: #3b82f6;
    --card-bg:     #ffffff;
    --gray-soft:   #f8fafc;
    --gray-bg:     #f1f5f9;
    --text-main:   #1e293b;
    --text-muted:  #94a3b8;
    --border:      #e2e8f0;
    --transition:  0.22s cubic-bezier(0.4, 0, 0.2, 1);
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  .page-main {
    flex: 1;
    padding: 36px 36px 48px;
    background: var(--gray-bg);
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main);
    overflow-y: auto;
  }

  /* ─── Header ─── */
  .page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 28px;
    animation: fadeUp 0.35s ease both;
  }

  .page-header-left .page-eyebrow {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--blue-accent);
    margin-bottom: 4px;
  }

  .page-header-left h1 {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 600;
    color: var(--blue-deep);
    line-height: 1.15;
  }

  /* ─── Tambah Button ─── */
  .btn-tambah {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--blue-mid) 0%, #1d4ed8 100%);
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    transition: transform var(--transition), box-shadow var(--transition);
  }

  .btn-tambah:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
    color: #fff;
  }

  .btn-tambah i { font-size: 13px; }

  /* ─── Summary Bar ─── */
  .summary-bar {
    display: flex;
    gap: 14px;
    margin-bottom: 22px;
    animation: fadeUp 0.4s ease both;
    animation-delay: 0.05s;
  }

  .summary-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 9px 16px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-muted);
  }

  .summary-pill .pill-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .summary-pill .pill-count {
    font-size: 15px;
    font-weight: 700;
    color: var(--blue-deep);
  }

  /* ─── Table Card ─── */
  .table-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    animation: fadeUp 0.45s ease both;
    animation-delay: 0.1s;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
  }

  /* Search bar inside card header */
  .table-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
  }

  .table-card-top .section-title {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--blue-deep);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .table-card-top .section-title i { color: var(--blue-accent); }

  .search-wrap {
    position: relative;
  }

  .search-wrap i {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 12px;
    pointer-events: none;
  }

  .search-input {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 12.5px;
    color: var(--text-main);
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 7px 12px 7px 32px;
    width: 200px;
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition);
  }

  .search-input:focus {
    border-color: var(--blue-accent);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
  }

  /* ─── Table ─── */
  .mobil-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .mobil-table thead th {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--text-muted);
    padding: 13px 20px;
    text-align: left;
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }

  .mobil-table thead th:first-child { border-radius: 0; }

  .mobil-table tbody tr {
    transition: background var(--transition);
  }

  .mobil-table tbody tr:hover td {
    background: #f8fafc;
  }

  .mobil-table tbody td {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--text-main);
    font-weight: 500;
    vertical-align: middle;
  }

  .mobil-table tbody tr:last-child td { border-bottom: none; }

  /* No cell */
  .no-cell {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-muted);
    width: 40px;
  }

  /* Mobil name cell */
  .nama-cell {
    display: flex;
    align-items: center;
    gap: 11px;
  }

  .mobil-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--blue-deep), var(--blue-accent));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .mobil-icon i { color: #fff; font-size: 15px; }

  .nama-text {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--blue-deep);
  }

  /* Brand badge */
  .brand-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: var(--blue-mid);
    background: #eff6ff;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.03em;
  }

  /* Harga */
  .harga-cell {
    font-size: 13px;
    font-weight: 700;
    color: var(--blue-deep);
    white-space: nowrap;
  }

  /* Status badge */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 20px;
    letter-spacing: 0.03em;
    white-space: nowrap;
  }

  .status-badge.tersedia  { background: #dcfce7; color: #16a34a; }
  .status-badge.disewa    { background: #fef9c3; color: #ca8a04; }
  .status-badge.tidak     { background: #fee2e2; color: #dc2626; }
  .status-badge i { font-size: 9px; }

  /* Action buttons */
  .action-wrap {
    display: flex;
    gap: 7px;
    align-items: center;
  }

  .btn-edit, .btn-hapus {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 11.5px;
    font-weight: 600;
    padding: 6px 13px;
    border-radius: 8px;
    text-decoration: none;
    transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
    border: none;
    cursor: pointer;
  }

  .btn-edit {
    background: #eff6ff;
    color: var(--blue-mid);
  }

  .btn-edit:hover {
    background: #b0c4f8;
    transform: translateY(-1px);
    color: var(--blue-deep);
  }

  .btn-hapus {
    background: #fee2e2;
    color: #b91c1c;
  }

  .btn-hapus:hover {
    background: #fecaca;
    transform: translateY(-1px);
    color: #7f1d1d;
  }

  /* ─── Empty state ─── */
  .empty-state {
    text-align: center;
    padding: 52px 20px;
    color: var(--text-muted);
  }

  .empty-state i {
    font-size: 40px;
    color: var(--border);
    margin-bottom: 12px;
    display: block;
  }

  .empty-state p { font-size: 13.5px; font-weight: 500; }

  /* ─── Animation ─── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Row stagger */
  .mobil-table tbody tr {
    animation: fadeUp 0.3s ease both;
  }
</style>

<?php
$query        = mysqli_query($conn, "SELECT * FROM mobil");
$total        = mysqli_num_rows($query);
$q_tersedia   = mysqli_query($conn, "SELECT COUNT(*) as c FROM mobil WHERE status='tersedia'");
$jml_tersedia = mysqli_fetch_assoc($q_tersedia)['c'] ?? 0;
$q_disewa     = mysqli_query($conn, "SELECT COUNT(*) as c FROM mobil WHERE status='disewa'");
$jml_disewa   = mysqli_fetch_assoc($q_disewa)['c'] ?? 0;
// Rerun query karena pointer sudah di akhir
$query = mysqli_query($conn, "SELECT * FROM mobil ORDER BY id DESC");
?>

<div class="page-main col-md-10">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-left">
      <div class="page-eyebrow">Manajemen Armada</div>
      <h1>Data Mobil</h1>
    </div>
    <a href="tambah_mobil.php" class="btn-tambah">
      <i class="fa-solid fa-plus"></i>
      Tambah Mobil
    </a>
  </div>

  <!-- Summary Pills -->
  <div class="summary-bar">
    <div class="summary-pill">
      <span class="pill-dot" style="background:#3b82f6;"></span>
      Total Armada
      <span class="pill-count"><?= $total ?></span>
    </div>
    <div class="summary-pill">
      <span class="pill-dot" style="background:#10b981;"></span>
      Tersedia
      <span class="pill-count"><?= $jml_tersedia ?></span>
    </div>
    <div class="summary-pill">
      <span class="pill-dot" style="background:#f59e0b;"></span>
      Sedang Disewa
      <span class="pill-count"><?= $jml_disewa ?></span>
    </div>
  </div>

  <!-- Table Card -->
  <div class="table-card">

    <div class="table-card-top">
      <div class="section-title">
        <i class="fa-solid fa-car"></i>
        Daftar Kendaraan
      </div>
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari mobil...">
      </div>
    </div>

    <table class="mobil-table" id="mobilTable">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Mobil</th>
          <th>Brand</th>
          <th>Harga / Hari</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="mobilBody">
        <?php
        $no = 1;
        while ($data = mysqli_fetch_assoc($query)):
          // Status badge class
          $st = strtolower($data['status']);
          if ($st === 'tersedia')       $badgeClass = 'tersedia';
          elseif ($st === 'disewa')     $badgeClass = 'disewa';
          else                          $badgeClass = 'tidak';

          // Status icon
          if ($badgeClass === 'tersedia')  $icon = 'fa-circle-check';
          elseif ($badgeClass === 'disewa') $icon = 'fa-clock';
          else                              $icon = 'fa-circle-xmark';
        ?>
        <tr>
          <td class="no-cell"><?= $no++ ?></td>
          <td>
            <span class="nama-text"><?= htmlspecialchars($data['nama_mobil']) ?></span>
          </td>
          <td><span class="brand-badge"><?= htmlspecialchars($data['brand']) ?></span></td>
          <td class="harga-cell">Rp <?= number_format($data['harga_per_hari'], 0, ',', '.') ?></td>
          <td>
            <span class="status-badge <?= $badgeClass ?>">
              <i class="fa-solid <?= $icon ?>"></i>
              <?= ucfirst($data['status']) ?>
            </span>
          </td>
          <td>
            <div class="action-wrap">
              <a href="edit_mobil.php?id=<?= $data['id'] ?>" class="btn-edit">
                <i class="fa-solid fa-pen-to-square"></i> Edit
              </a>
              <a href="hapus_mobil.php?id=<?= $data['id'] ?>"
                 class="btn-hapus"
                 onclick="return confirm('Yakin ingin menghapus mobil ini?')">
                <i class="fa-solid fa-trash"></i> Hapus
              </a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>

        <!-- Empty state jika tidak ada data -->
        <?php if ($no === 1): ?>
        <tr>
          <td colspan="6">
            <div class="empty-state">
              <i class="fa-solid fa-car-rear"></i>
              <p>Belum ada data mobil. Tambahkan kendaraan pertama Anda.</p>
            </div>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>
</div>

<script>
  // Live search filter
  document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const rows    = document.querySelectorAll('#mobilBody tr');

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(keyword) ? '' : 'none';
    });
  });

  // Row animation stagger
  document.querySelectorAll('#mobilBody tr').forEach((row, i) => {
    row.style.animationDelay = (i * 0.04) + 's';
  });
</script>

<?php include "include/footer.php"; ?>