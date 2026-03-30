<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "include/koneksi.php";
include "include/header.php";
include "include/sidebar.php";


// TOTAL PENDAPATAN
$total_pendapatan = mysqli_query($conn, "
    SELECT SUM(grand_total) as total 
    FROM booking 
    WHERE status_pembayaran='paid'
");
$data_total = mysqli_fetch_assoc($total_pendapatan);


// PENDAPATAN BULAN INI
$bulan_ini = date('m');
$tahun_ini = date('Y');

$pendapatan_bulan = mysqli_query($conn, "
    SELECT SUM(grand_total) as total 
    FROM booking 
    WHERE status_pembayaran='paid'
    AND MONTH(tanggal_sewa)='$bulan_ini'
    AND YEAR(tanggal_sewa)='$tahun_ini'
");
$data_bulan = mysqli_fetch_assoc($pendapatan_bulan);

// TOTAL BOOKING
$total_booking = mysqli_query($conn, "
    SELECT COUNT(id) as total FROM booking
");
$data_booking = mysqli_fetch_assoc($total_booking);


// MOBIL PALING SERING DISEWA
$mobil_populer = mysqli_query($conn, "
    SELECT mobil.nama_mobil, COUNT(booking.id) as total_sewa
    FROM booking
    JOIN mobil ON booking.mobil_id = mobil.id
    GROUP BY mobil_id
    ORDER BY total_sewa DESC
    LIMIT 1
");
$data_mobil = mysqli_fetch_assoc($mobil_populer);

$data_chart_bulan = [];
$label_bulan = [];

for ($i = 1; $i <= 12; $i++) {
    $query = mysqli_query($conn, "
        SELECT SUM(grand_total) as total 
        FROM booking 
        WHERE status_pembayaran='paid'
        AND MONTH(tanggal_sewa)='$i'
    ");
    $row = mysqli_fetch_assoc($query);
    $data_chart_bulan[] = $row['total'] ?? 0;
    $label_bulan[] = date("M", mktime(0,0,0,$i,1));
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  /* ── Page wrapper ── */
  .laporan-wrapper {
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

  .page-header-icon i { color: #fff; font-size: 20px; }

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

  /* ── Stat cards grid ── */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 22px 22px 18px;
    border: 1px solid #e2eaf7;
    box-shadow: 0 4px 20px rgba(26,58,107,0.07);
    display: flex;
    flex-direction: column;
    gap: 12px;
    position: relative;
    overflow: hidden;
    transition: transform 0.18s, box-shadow 0.18s;
  }

  .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(26,58,107,0.13);
  }

  /* Decorative top bar */
  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #1a3a6b, #2563eb);
  }

  .stat-card-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
  }

  .icon-blue   { background: #dbeafe; color: #2563eb; }
  .icon-indigo { background: #e0e7ff; color: #4338ca; }
  .icon-green  { background: #dcfce7; color: #15803d; }
  .icon-amber  { background: #fef9c3; color: #b45309; }

  .stat-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #94a3b8;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-top: 4px;
  }

  .stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #1a3a6b;
    line-height: 1.2;
    word-break: break-word;
  }

  /* ── Chart card ── */
  .chart-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2eaf7;
    box-shadow: 0 4px 32px rgba(26,58,107,0.08);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .chart-card-header {
    padding: 18px 28px;
    background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .chart-card-header h5 {
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    margin: 0;
  }

  .chart-card-header span {
    font-size: 12px;
    color: rgba(255,255,255,0.65);
    font-weight: 500;
  }

  .chart-card-body {
    padding: 24px 28px;
  }

  /* ── Quick Actions ── */
  .qa-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1a3a6b;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .qa-section-title i { color: #2563eb; font-size: 15px; }

  .qa-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .qa-item {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2eaf7;
    box-shadow: 0 2px 16px rgba(26,58,107,0.07);
    padding: 22px 18px 18px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
  }

  .qa-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(37,99,235,0.15);
    border-color: #bfdbfe;
  }

  .qa-item-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: #dbeafe;
    display: flex; align-items: center; justify-content: center;
    color: #2563eb;
    font-size: 18px;
  }

  .qa-item h4 {
    font-size: 14px;
    font-weight: 700;
    color: #1a3a6b;
    margin: 0;
  }

  .qa-item p {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
    line-height: 1.5;
  }

  @media (max-width: 900px) {
    .stat-grid, .qa-grid { grid-template-columns: repeat(2, 1fr); }
  }
</style>

<div class="laporan-wrapper">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-icon">
      <i class="fa-solid fa-chart-line"></i>
    </div>
    <div class="page-header-text">
      <h1>Laporan</h1>
      <p>Ringkasan performa dan statistik bisnis rental kendaraan</p>
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="stat-grid">

    <div class="stat-card">
      <div class="stat-card-icon icon-blue">
        <i class="fa-solid fa-money-bill-wave"></i>
      </div>
      <div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp <?= number_format($data_total['total'] ?? 0); ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-card-icon icon-indigo">
        <i class="fa-solid fa-calendar-day"></i>
      </div>
      <div>
        <div class="stat-label">Pendapatan Bulan Ini</div>
        <div class="stat-value">Rp <?= number_format($data_bulan['total'] ?? 0); ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-card-icon icon-green">
        <i class="fa-solid fa-calendar-check"></i>
      </div>
      <div>
        <div class="stat-label">Total Booking</div>
        <div class="stat-value"><?= $data_booking['total']; ?></div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-card-icon icon-amber">
        <i class="fa-solid fa-star"></i>
      </div>
      <div>
        <div class="stat-label">Mobil Terfavorit</div>
        <div class="stat-value"><?= $data_mobil['nama_mobil'] ?? '-'; ?></div>
      </div>
    </div>

  </div>

  <!-- Chart Card -->
  <div class="chart-card">
    <div class="chart-card-header">
      <h5><i class="fa-solid fa-chart-area me-2"></i>Grafik Pendapatan Bulanan</h5>
      <span><?= date('Y'); ?></span>
    </div>
    <div class="chart-card-body">
      <canvas id="chartPendapatan" height="90"></canvas>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="qa-section-title">
    <i class="fa-solid fa-bolt"></i> Quick Actions
  </div>
  <div class="qa-grid">

    <a href="mobil.php" class="qa-item">
      <div class="qa-item-icon"><i class="fa-solid fa-car"></i></div>
      <h4>Kontrol Mobil</h4>
      <p>Kelola dan cek status ketersediaan mobil</p>
    </a>

    <a href="booking.php" class="qa-item">
      <div class="qa-item-icon"><i class="fa-solid fa-circle-check"></i></div>
      <h4>Verifikasi</h4>
      <p>Cek dan verifikasi data klien baru</p>
    </a>

    <a href="client.php" class="qa-item">
      <div class="qa-item-icon"><i class="fa-solid fa-phone"></i></div>
      <h4>Hubungi</h4>
      <p>Hubungi atau kirim pesan untuk klien</p>
    </a>

    <a href="index.php" class="qa-item">
      <div class="qa-item-icon"><i class="fa-solid fa-globe"></i></div>
      <h4>Website Klien</h4>
      <p>Akses halaman website khusus untuk klien</p>
    </a>

  </div>

</div>

<script>
const ctx = document.getElementById('chartPendapatan');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($label_bulan); ?>,
        datasets: [{
            label: 'Pendapatan Bulanan',
            data: <?= json_encode($data_chart_bulan); ?>,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#2563eb',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return ' Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { color: '#f0f5ff' },
                ticks: { color: '#94a3b8', font: { size: 12 } }
            },
            y: {
                grid: { color: '#f0f5ff' },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 12 },
                    callback: function(v) { return 'Rp ' + v.toLocaleString('id-ID'); }
                }
            }
        }
    }
});
</script>

<?php include "include/footer.php"; ?>