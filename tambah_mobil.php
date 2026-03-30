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

// Ambil next auto_increment ID
$nextId = 1;
$aiRes = mysqli_query($conn, "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mobil'");
if ($aiRes && $row = mysqli_fetch_assoc($aiRes)) {
    $nextId = (int)$row['AUTO_INCREMENT'];
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

  /* ─── Two-column grid ─── */
  .add-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 22px;
    align-items: stretch;
    animation: fadeUp 0.4s ease both;
    animation-delay: 0.05s;
  }

  .add-grid > .add-card:last-child {
    display: flex;
    flex-direction: column;
  }

  /* Foto mengisi sisa tinggi; info rows kompak di bawah */
  .add-grid > .add-card:last-child .photo-wrap {
    flex: 1;
    min-height: 160px;
    max-height: 320px;   /* hindari zoom berlebih pada foto landscape lebar */
  }

  .add-grid > .add-card:last-child .photo-info {
    flex-shrink: 0;      /* info rows selalu kompak, tidak ikut meregang */
  }

  @media (max-width: 900px) {
    .add-grid { grid-template-columns: 1fr; }
  }

  /* ─── Shared card ─── */
  .add-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
    overflow: hidden;
  }

  .add-card-header {
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

  .add-card-body { padding: 24px; }

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
    letter-spacing: 0.01em;
  }

  .field-label i { color: var(--text-muted); font-size: 12px; width: 14px; }

  .field-required { color: #ef4444; font-size: 12px; margin-left: 2px; }

  .field-input {
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

  .field-input:focus {
    background: #fff;
    border-color: var(--blue-accent);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
  }

  .field-input::placeholder { color: var(--text-muted); font-weight: 400; }

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
    user-select: none;
  }
  .input-prefix-wrap .field-input { padding-left: 42px; }

  /* ─── File Upload ─── */
  .file-upload-area {
    width: 100%;
    border: 2px dashed var(--border);
    border-radius: 10px;
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color var(--transition), background var(--transition);
    background: var(--gray-soft);
    position: relative;
  }

  .file-upload-area:hover,
  .file-upload-area.dragover {
    border-color: var(--blue-accent);
    background: var(--blue-light);
  }

  .file-upload-area input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
  }

  .file-upload-icon {
    width: 48px; height: 48px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
  }

  .file-upload-icon i { color: var(--blue-accent); font-size: 20px; }

  .file-upload-text { font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 4px; }
  .file-upload-sub  { font-size: 11.5px; color: var(--text-muted); }

  .file-name-badge {
    margin-top: 10px;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    align-items: center;
    gap: 5px;
  }

  /* ─── Action Buttons ─── */
  .form-actions { display: flex; gap: 10px; padding-top: 4px; }

  .btn-simpan {
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

  .btn-simpan:hover {
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
    cursor: pointer;
    transition: all var(--transition);
  }

  .btn-batal:hover {
    background: #fff;
    color: var(--text-main);
    border-color: #cbd5e1;
  }

  /* ─── Preview Card (right column) ─── */
  .photo-wrap {
    position: relative;
    overflow: hidden;
    background: #e2e8f0;
  }

  /* photo-aspect mengisi seluruh .photo-wrap */
  .photo-aspect {
    position: absolute;
    inset: 0;
    overflow: hidden;
  }

  .photo-aspect img {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center top;   /* posisi atas agar bagian penting gambar tidak terpotong */
    display: block;
    transition: transform 0.5s cubic-bezier(0.4,0,0.2,1);
  }

  .photo-wrap:hover .photo-aspect img { transform: scale(1.03); }

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

  /* Empty state */
  .photo-empty {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, #e9eef6 0%, #f1f5f9 100%);
  }

  .photo-empty i    { font-size: 36px; color: var(--text-muted); opacity: 0.3; }
  .photo-empty span { font-size: 12px; font-weight: 600; color: var(--text-muted); opacity: 0.45; letter-spacing: 0.02em; }

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
    width: 14px; height: 2px;
    background: rgba(255,255,255,0.55);
    border-radius: 1px;
    flex-shrink: 0;
  }

  /* Info rows */
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
  .info-icon.orange { background: #ffedd5; color: #ea580c; }
  .info-icon.purple { background: #ede9fe; color: #7c3aed; }

  .info-row .info-value {
    font-weight: 700;
    color: var(--text-main);
    font-size: 13px;
  }

  .info-placeholder {
    color: var(--text-muted);
    font-weight: 400;
    font-style: italic;
    font-size: 12px;
  }

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

  .lightbox-backdrop.open {
    opacity: 1;
    visibility: visible;
  }

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

  .lightbox-backdrop.open .lightbox-box {
    transform: scale(1);
  }

  .lightbox-box img {
    display: block;
    max-width: 100%;
    max-height: calc(82vh - 62px);
    width: auto;
    height: auto;
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

  .lightbox-caption-text {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    opacity: 0.85;
  }

  .lightbox-caption-sub {
    font-size: 11.5px;
    color: rgba(255,255,255,0.45);
    font-weight: 400;
  }

  .lightbox-close {
    width: 30px; height: 30px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #fff;
    font-size: 13px;
    flex-shrink: 0;
    transition: background 0.18s;
  }

  .lightbox-close:hover { background: rgba(255,255,255,0.2); }

  /* Hint kursor zoom saat hover foto */
  .photo-wrap.has-image {
    cursor: zoom-in;
  }

  .photo-wrap.has-image::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 2;
    pointer-events: none;
  }
</style>

<div class="page-main col-md-10">

  <!-- Header -->
  <div class="page-header">
    <div>
      <div class="page-eyebrow">Manajemen Armada</div>
      <h1>Tambah Mobil</h1>
    </div>
    <a href="mobil.php" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <!-- Two-column grid -->
  <div class="add-grid">

    <!-- Left: Form -->
    <div class="add-card">
      <div class="add-card-header">
        <div class="header-icon"><i class="fa-solid fa-car"></i></div>
        <div>
          <div class="header-text">Informasi Kendaraan</div>
          <div class="header-sub">Isi data kendaraan baru yang akan ditambahkan ke armada</div>
        </div>
      </div>

      <div class="add-card-body">
        <form method="POST" enctype="multipart/form-data">

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-tag"></i>
              Nama Mobil <span class="field-required">*</span>
            </label>
            <input type="text" name="nama_mobil" id="inputNama" class="field-input" required>
          </div>

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-copyright"></i>
              Brand <span class="field-required">*</span>
            </label>
            <input type="text" name="brand" id="inputBrand" class="field-input" required>
          </div>

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-money-bill-wave"></i>
              Harga per Hari <span class="field-required">*</span>
            </label>
            <div class="input-prefix-wrap">
              <span class="input-prefix">Rp</span>
              <input type="number" min="0" name="harga" id="inputHarga" class="field-input"
                    placeholder="0" required>
            </div>
          </div>

          <div class="field-group">
            <label class="field-label">
              <i class="fa-solid fa-image"></i>
              Foto Mobil <span class="field-required">*</span>
            </label>
            <div class="file-upload-area" id="uploadArea">
              <input type="file" name="foto" id="fotoInput" accept="image/*" required>
              <div class="file-upload-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i>
              </div>
              <div class="file-upload-text">Klik atau drag foto ke sini</div>
              <div class="file-upload-sub">PNG, JPG, JPEG — Maks. 5MB</div>
            </div>
            <div id="fileNameBadge" class="file-name-badge" style="display:none;">
              <i class="fa-solid fa-file-image"></i>
              <span id="fileNameText"></span>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" name="simpan" class="btn-simpan">
              <i class="fa-solid fa-floppy-disk"></i>
              Simpan Kendaraan
            </button>
            <a href="mobil.php" class="btn-batal">
              <i class="fa-solid fa-xmark"></i>
              Batal
            </a>
          </div>

        </form>
      </div>
    </div>

    <!-- Right: Live Preview -->
    <div class="add-card">
      <div class="add-card-header">
        <div class="header-icon"><i class="fa-solid fa-eye"></i></div>
        <div>
          <div class="header-text">Pratinjau Kendaraan</div>
          <div class="header-sub">Tampilan live sebelum disimpan</div>
        </div>
      </div>

      <!-- photo-wrap sekarang flex:1, butuh wrapper relatif agar photo-aspect bisa absolute -->
      <div class="photo-wrap" id="photoWrap"><div class="photo-aspect">
          <!-- Empty state -->
          <div class="photo-empty" id="photoEmpty">
            <i class="fa-regular fa-image"></i>
            <span>Belum ada foto dipilih</span>
          </div>

          <!-- Preview image -->
          <img id="previewImg" src="" alt="Preview" style="display:none;">

          <!-- Hint ikon zoom di pojok kanan atas (muncul saat ada gambar) -->
          <div id="zoomHint" style="display:none;position:absolute;top:10px;right:10px;z-index:3;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:5px 9px;color:#fff;font-size:11px;font-weight:600;gap:5px;align-items:center;pointer-events:none;">
            <i class="fa-solid fa-magnifying-glass-plus" style="font-size:11px;"></i> Klik untuk perbesar
          </div>

          <div class="photo-overlay" id="photoOverlay" style="display:none;">
            <div class="photo-name" id="previewNama">—</div>
            <div class="photo-brand" id="previewBrand">—</div>
          </div>
      </div></div>

      <div class="photo-info">
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon blue"><i class="fa-solid fa-file-image"></i></span>
            Nama File
          </span>
          <span class="info-value" id="infoNamaFile"><span class="info-placeholder">—</span></span>
        </div>
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon orange"><i class="fa-solid fa-layer-group"></i></span>
            Format
          </span>
          <span class="info-value" id="infoFormat"><span class="info-placeholder">—</span></span>
        </div>
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon green"><i class="fa-solid fa-weight-scale"></i></span>
            Ukuran
          </span>
          <span class="info-value" id="infoUkuran"><span class="info-placeholder">—</span></span>
        </div>
        <div class="info-row">
          <span class="info-label">
            <span class="info-icon purple"><i class="fa-solid fa-hashtag"></i></span>
            ID Kendaraan
          </span>
          <span class="info-value">#<?= str_pad($nextId, 4, '0', STR_PAD_LEFT) ?></span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ── Lightbox Modal ── -->
<div class="lightbox-backdrop" id="lightboxBackdrop">
  <div class="lightbox-box" id="lightboxBox">
    <img id="lightboxImg" src="" alt="Preview besar">
    <div class="lightbox-caption">
      <div>
        <div class="lightbox-caption-text" id="lightboxNama">—</div>
        <div class="lightbox-caption-sub" id="lightboxSub">Pratinjau Foto Kendaraan</div>
      </div>
      <div class="lightbox-close" id="lightboxClose">
        <i class="fa-solid fa-xmark"></i>
      </div>
    </div>
  </div>
</div>

<script>
  const fotoInput     = document.getElementById('fotoInput');
  const uploadArea    = document.getElementById('uploadArea');
  const previewImg    = document.getElementById('previewImg');
  const photoEmpty    = document.getElementById('photoEmpty');
  const photoOverlay  = document.getElementById('photoOverlay');
  const fileNameBadge = document.getElementById('fileNameBadge');
  const fileNameText  = document.getElementById('fileNameText');

  const inputNama  = document.getElementById('inputNama');
  const inputBrand = document.getElementById('inputBrand');
  const previewNama  = document.getElementById('previewNama');
  const previewBrand = document.getElementById('previewBrand');

  // Info file elements
  const infoNamaFile = document.getElementById('infoNamaFile');
  const infoFormat   = document.getElementById('infoFormat');
  const infoUkuran   = document.getElementById('infoUkuran');

  // ── Live overlay sync (nama + brand) ──
  function syncOverlay() {
    const nama  = inputNama.value.trim();
    const brand = inputBrand.value.trim();
    previewNama.textContent  = nama  || '—';
    previewBrand.textContent = brand || '—';
    if ((nama || brand) && previewImg.style.display !== 'none') {
      photoOverlay.style.display = 'flex';
    }
  }

  inputNama.addEventListener('input', syncOverlay);
  inputBrand.addEventListener('input', syncOverlay);

  // ── Helper: format bytes ──
  function formatBytes(bytes) {
    if (bytes < 1024)        return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
  }

  // ── File / image preview ──
  fotoInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    // Badge bawah area upload
    fileNameText.textContent    = file.name;
    fileNameBadge.style.display = 'inline-flex';

    // Metadata file → info rows
    const ext = file.name.split('.').pop().toUpperCase();
    infoNamaFile.textContent = file.name.length > 22 ? file.name.substring(0, 20) + '…' : file.name;
    infoFormat.innerHTML  = `<span style="background:#ffedd5;color:#ea580c;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;">${ext}</span>`;
    infoUkuran.textContent = formatBytes(file.size);

    const reader = new FileReader();
    reader.onload = e => {
      previewImg.src             = e.target.result;
      previewImg.style.display   = 'block';
      photoEmpty.style.display   = 'none';
      photoOverlay.style.display = 'flex';
      syncOverlay();
    };
    reader.readAsDataURL(file);
  });

  // ── Drag feedback ──
  uploadArea.addEventListener('dragover',  e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
  uploadArea.addEventListener('dragleave', ()  => uploadArea.classList.remove('dragover'));
  uploadArea.addEventListener('drop',      ()  => uploadArea.classList.remove('dragover'));

  // ── Lightbox ──
  const photoWrap        = document.getElementById('photoWrap');
  const zoomHint         = document.getElementById('zoomHint');
  const lightboxBackdrop = document.getElementById('lightboxBackdrop');
  const lightboxImg      = document.getElementById('lightboxImg');
  const lightboxNama     = document.getElementById('lightboxNama');
  const lightboxSub      = document.getElementById('lightboxSub');
  const lightboxClose    = document.getElementById('lightboxClose');

  function openLightbox() {
    if (!previewImg.src || previewImg.style.display === 'none') return;
    lightboxImg.src          = previewImg.src;
    lightboxNama.textContent = previewNama.textContent || 'Kendaraan';
    lightboxSub.textContent  = previewBrand.textContent
      ? previewBrand.textContent + ' · Pratinjau Foto'
      : 'Pratinjau Foto Kendaraan';
    lightboxBackdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightboxBackdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  // Klik foto → buka lightbox (hanya jika ada gambar)
  photoWrap.addEventListener('click', openLightbox);

  // Tombol X
  lightboxClose.addEventListener('click', e => { e.stopPropagation(); closeLightbox(); });

  // Klik backdrop di luar kotak → tutup
  lightboxBackdrop.addEventListener('click', e => {
    if (e.target === lightboxBackdrop) closeLightbox();
  });

  // Esc → tutup
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLightbox();
  });

  // Tampilkan zoom hint + kursor zoom setelah gambar load
  fotoInput.addEventListener('change', () => {
    setTimeout(() => {
      if (previewImg.src && previewImg.style.display !== 'none') {
        photoWrap.classList.add('has-image');
        zoomHint.style.display = 'inline-flex';
      }
    }, 200);
  });
</script>

<?php include "include/footer.php"; ?>