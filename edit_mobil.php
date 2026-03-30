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

  .page-eyebrow {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--blue-accent);
    margin-bottom: 4px;
  }

  .page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 600;
    color: var(--blue-deep);
    line-height: 1.15;
  }

  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--card-bg);
    color: var(--text-muted);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 10px;
    text-decoration: none;
    border: 1px solid var(--border);
    transition: all var(--transition);
  }

  .btn-back:hover {
    background: var(--gray-soft);
    color: var(--text-main);
    border-color: #cbd5e1;
  }

  /* ─── Two-column layout ─── */
  .edit-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 22px;
    align-items: stretch;
    animation: fadeUp 0.4s ease both;
    animation-delay: 0.05s;
  }

  /* Foto card isi tinggi penuh mengikuti kolom form */
  .edit-grid > .edit-card:last-child {
    display: flex;
    flex-direction: column;
  }

  .edit-grid > .edit-card:last-child .photo-info {
    flex: 1;
    justify-content: center;
  }

  @media (max-width: 900px) {
    .edit-grid { grid-template-columns: 1fr; }
  }

  /* ─── Shared card style ─── */
  .edit-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
    overflow: hidden;
  }

  .edit-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .header-icon {
    width: 34px; height: 34px;
    background: var(--blue-light);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .header-icon i { color: var(--blue-mid); font-size: 14px; }

  .header-text {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--blue-deep);
  }

  .header-sub {
    font-size: 11.5px;
    color: var(--text-muted);
    font-weight: 400;
    margin-top: 1px;
  }

  .edit-card-body { padding: 24px; }

  /* ─── Form Fields ─── */
  .field-group { margin-bottom: 20px; }
  .field-group:last-of-type { margin-bottom: 26px; }

  .field-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 7px;
  }

  .field-label i {
    color: var(--text-muted);
    font-size: 12px;
    width: 14px;
  }

  .field-required { color: #ef4444; font-size: 12px; margin-left: 2px; }

  .field-input,
  .field-select {
    width: 100%;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-main);
    background: var(--gray-soft);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 11px 14px;
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    appearance: none;
    -webkit-appearance: none;
  }

  .field-input:focus,
  .field-select:focus {
    background: #fff;
    border-color: var(--blue-accent);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
  }

  .field-input::placeholder { color: var(--text-muted); font-weight: 400; }

  /* Select arrow */
  .select-wrap { position: relative; }
  .select-wrap::after {
    content: '\f078';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 10px;
    color: var(--text-muted);
    position: absolute;
    right: 14px; top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
  }

  /* Rp prefix */
  .input-prefix-wrap { position: relative; }
  .input-prefix {
    position: absolute;
    left: 14px; top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    pointer-events: none;
  }
  .input-prefix-wrap .field-input { padding-left: 42px; }

  /* ─── Action Buttons ─── */
  .form-actions {
    display: flex;
    gap: 10px;
  }

  .btn-update {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--blue-mid) 0%, #1d4ed8 100%);
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    padding: 11px 28px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    transition: transform var(--transition), box-shadow var(--transition);
  }

  .btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37,99,235,0.42);
  }

  .btn-batal {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--gray-soft);
    color: var(--text-muted);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    padding: 11px 22px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    text-decoration: none;
    transition: all var(--transition);
  }

  .btn-batal:hover {
    background: #fff;
    color: var(--text-main);
    border-color: #cbd5e1;
  }

  /* ─── Photo Card ─── */
  .photo-wrap {
    position: relative;
    overflow: hidden;
    background: #e2e8f0;
  }

  .photo-aspect {
    position: relative;
    width: 100%;
    padding-top: 62%;   /* ~16:10 ratio */
    overflow: hidden;
  }

  .photo-aspect img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.5s cubic-bezier(0.4,0,0.2,1);
  }

  .photo-wrap:hover .photo-aspect img { transform: scale(1.05); }

  .photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      to top,
      rgba(15, 35, 75, 0.75) 0%,
      rgba(15, 35, 75, 0.15) 50%,
      transparent 100%
    );
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 18px 20px;
  }

  .photo-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 10.5px;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.04em;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .photo-name {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    line-height: 1.25;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
  }

  .photo-brand {
    font-size: 12px;
    color: rgba(255,255,255,0.78);
    font-weight: 500;
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .photo-brand::before {
    content: '';
    display: inline-block;
    width: 14px;
    height: 2px;
    background: rgba(255,255,255,0.55);
    border-radius: 1px;
    flex-shrink: 0;
  }

  /* No-image placeholder */
  .photo-aspect.no-img {
    background: linear-gradient(135deg, #e2e8f0 0%, #f1f5f9 100%);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .no-img-hint {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--text-muted);
    pointer-events: none;
  }

  .no-img-hint i { font-size: 32px; opacity: 0.35; }
  .no-img-hint span { font-size: 12px; font-weight: 500; opacity: 0.5; }

  /* Info rows bawah foto */
  .photo-info {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 0;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12.5px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
  }

  .info-row:last-child { border-bottom: none; }

  .info-row .info-label {
    color: var(--text-muted);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .info-icon {
    width: 26px; height: 26px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .info-icon i { font-size: 11px; }
  .info-icon.green  { background: #dcfce7; color: #16a34a; }
  .info-icon.blue   { background: #dbeafe; color: #2563eb; }
  .info-icon.purple { background: #ede9fe; color: #7c3aed; }

  .info-row .info-value {
    font-weight: 700;
    color: var(--text-main);
    font-size: 13px;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 20px;
  }

  .status-pill.tersedia { background: #dcfce7; color: #16a34a; }
  .status-pill.disewa   { background: #fef9c3; color: #ca8a04; }
  .status-pill i { font-size: 9px; }

  /* ─── Animation ─── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ─── Lightbox Modal ─── */
  .lightbox-backdrop {
    position: fixed;
    inset: 0;
    z-index: 9000;
    background: rgba(10, 18, 40, 0.82);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
  }
  .lightbox-backdrop.open { opacity: 1; visibility: visible; }

  .lightbox-box {
    position: relative;
    background: #0f1828;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 24px 80px rgba(0,0,0,0.55);
    max-width: 82vw;
    max-height: 82vh;
    display: flex;
    flex-direction: column;
    transform: scale(0.94);
    transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .lightbox-backdrop.open .lightbox-box { transform: scale(1); }

  .lightbox-box img {
    display: block;
    max-width: 100%;
    max-height: calc(82vh - 62px);
    width: auto; height: auto;
    object-fit: contain;
  }

  .lightbox-caption {
    padding: 12px 20px;
    background: rgba(255,255,255,0.05);
    border-top: 1px solid rgba(255,255,255,0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-shrink: 0;
  }
  .lightbox-caption-text { font-size: 13px; font-weight: 600; color: #fff; opacity: 0.85; }
  .lightbox-caption-sub  { font-size: 11.5px; color: rgba(255,255,255,0.45); }

  .lightbox-close {
    width: 30px; height: 30px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #fff; font-size: 13px; flex-shrink: 0;
    transition: background 0.18s;
  }
  .lightbox-close:hover { background: rgba(255,255,255,0.2); }

  /* Kursor zoom-in saat hover foto */
  .photo-wrap.has-image { cursor: zoom-in; }
</style>

<div class="page-main col-md-10">

  <!-- Header -->
  <div class="page-header">
    <div>
      <div class="page-eyebrow">Manajemen Armada</div>
      <h1>Edit Mobil</h1>
    </div>
    <a href="mobil.php" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <!-- Two-column grid -->
  <div class="edit-grid">

    <!-- Left: Form -->
    <div class="edit-card">
      <div class="edit-card-header">
        <div class="header-icon"><i class="fa-solid fa-pen-to-square"></i></div>
        <div>
          <div class="header-text">Ubah Data Kendaraan</div>
          <div class="header-sub">Perbarui informasi kendaraan yang dipilih</div>
        </div>
      </div>

      <div class="edit-card-body">
        <form method="POST">

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-tag"></i>
              Nama Mobil <span class="field-required">*</span>
            </label>
            <input type="text" name="nama_mobil" class="field-input"
                   value="<?= htmlspecialchars($data['nama_mobil']) ?>" required>
          </div>

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-copyright"></i>
              Brand <span class="field-required">*</span>
            </label>
            <input type="text" name="brand" class="field-input"
                   value="<?= htmlspecialchars($data['brand']) ?>" required>
          </div>

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-money-bill-wave"></i>
              Harga per Hari <span class="field-required">*</span>
            </label>
            <div class="input-prefix-wrap">
              <span class="input-prefix">Rp</span>
              <input type="number" name="harga" min="0" class="field-input"
                     value="<?= $data['harga_per_hari'] ?>" required>
            </div>
          </div>

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-circle-half-stroke"></i>
              Status <span class="field-required">*</span>
            </label>
            <div class="select-wrap">
              <select name="status" class="field-select">
                <option value="tersedia" <?= $data['status']=="tersedia" ? "selected" : "" ?>>Tersedia</option>
                <option value="disewa"   <?= $data['status']=="disewa"   ? "selected" : "" ?>>Disewa</option>
              </select>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" name="update" class="btn-update">
              <i class="fa-solid fa-floppy-disk"></i>
              Simpan Perubahan
            </button>
            <a href="mobil.php" class="btn-batal">
              <i class="fa-solid fa-xmark"></i>
              Batal
            </a>
          </div>

        </form>
      </div>
    </div>

    <!-- Right: Photo Preview -->
    <div class="edit-card">
      <div class="edit-card-header">
        <div class="header-icon"><i class="fa-solid fa-image"></i></div>
        <div>
          <div class="header-text">Foto Kendaraan</div>
          <div class="header-sub">Pratinjau foto kendaraan saat ini</div>
        </div>
      </div>

      <div class="photo-wrap has-image" id="photoWrap">
        <div class="photo-aspect" id="photoAspect">
          <!-- No-image placeholder (hidden when image loads) -->
          <div class="no-img-hint" id="noImgHint" style="display:none">
            <i class="fa-regular fa-image"></i>
            <span>Foto tidak tersedia</span>
          </div>

          <img id="carPhoto"
               src="<?= 'assets/upload/' . $data['foto'] ?>"
               alt="<?= htmlspecialchars($data['nama_mobil']) ?>"
               onerror="this.style.display='none'; document.getElementById('noImgHint').style.display='flex'; document.getElementById('photoWrap').classList.remove('has-image');">

          <!-- Zoom hint pojok kanan atas -->
          <div id="zoomHint" style="position:absolute;top:10px;right:10px;z-index:3;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:5px 9px;color:#fff;font-size:11px;font-weight:600;display:inline-flex;gap:5px;align-items:center;pointer-events:none;">
            <i class="fa-solid fa-magnifying-glass-plus" style="font-size:11px;"></i> Klik untuk perbesar
          </div>

          <div class="photo-overlay">
            <div class="photo-name"><?= htmlspecialchars($data['nama_mobil']) ?></div>
            <div class="photo-brand"><?= htmlspecialchars($data['brand']) ?></div>
          </div>
        </div>
      </div>

      <div class="photo-info">
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon green"><i class="fa-solid fa-money-bill-wave"></i></span>
            Harga / Hari
          </span>
          <span class="info-value">Rp <?= number_format($data['harga_per_hari'], 0, ',', '.') ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon blue"><i class="fa-solid fa-circle-dot"></i></span>
            Status
          </span>
          <?php
            $st = strtolower($data['status']);
            $ic = $st === 'tersedia' ? 'fa-circle-check' : 'fa-clock';
          ?>
          <span class="status-pill <?= $st ?>">
            <i class="fa-solid <?= $ic ?>"></i>
            <?= ucfirst($data['status']) ?>
          </span>
        </div>
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon purple"><i class="fa-solid fa-hashtag"></i></span>
            ID Kendaraan
          </span>
          <span class="info-value">#<?= str_pad($data['id'], 4, '0', STR_PAD_LEFT) ?></span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ── Lightbox Modal ── -->
<div class="lightbox-backdrop" id="lightboxBackdrop">
  <div class="lightbox-box">
    <img id="lightboxImg" src="" alt="Preview besar">
    <div class="lightbox-caption">
      <div>
        <div class="lightbox-caption-text"><?= htmlspecialchars($data['nama_mobil']) ?></div>
        <div class="lightbox-caption-sub"><?= htmlspecialchars($data['brand']) ?> &middot; Foto Kendaraan</div>
      </div>
      <div class="lightbox-close" id="lightboxClose">
        <i class="fa-solid fa-xmark"></i>
      </div>
    </div>
  </div>
</div>

<script>
  const photoWrap        = document.getElementById('photoWrap');
  const carPhoto         = document.getElementById('carPhoto');
  const lightboxBackdrop = document.getElementById('lightboxBackdrop');
  const lightboxImg      = document.getElementById('lightboxImg');
  const lightboxClose    = document.getElementById('lightboxClose');

  function openLightbox() {
    if (!carPhoto.src || carPhoto.style.display === 'none') return;
    lightboxImg.src = carPhoto.src;
    lightboxBackdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightboxBackdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  photoWrap.addEventListener('click', openLightbox);
  lightboxClose.addEventListener('click', e => { e.stopPropagation(); closeLightbox(); });
  lightboxBackdrop.addEventListener('click', e => { if (e.target === lightboxBackdrop) closeLightbox(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>

<?php include "include/footer.php"; ?>