<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  :root {
    --blue-deep: #1a3a6b;
    --blue-mid: #2563eb;
    --blue-light: #dbeafe;
    --blue-accent: #3b82f6;
    --blue-glow: rgba(59, 130, 246, 0.15);
    --white: #ffffff;
    --gray-soft: #f0f5ff;
    --text-muted: #94a3b8;
    --sidebar-width: 240px;
    --transition: 0.22s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .admin-sidebar {
    width: var(--sidebar-width);
    min-height: 100vh;
    background: var(--white);
    display: flex;
    flex-direction: column;
    padding: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border-right: 1px solid #e2eaf7;
    box-shadow: 4px 0 24px rgba(26, 58, 107, 0.07);
    position: relative;
    overflow: hidden;
  }

  .admin-sidebar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--blue-deep), var(--blue-accent), #60a5fa);
  }

  .admin-sidebar::after {
    content: '';
    position: absolute;
    bottom: -60px; right: -60px;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  .sidebar-brand {
    padding: 28px 22px 20px;
    border-bottom: 1px solid #e8eef8;
    margin-bottom: 8px;
  }

  .brand-logo-wrap {
    display: flex;
    align-items: center;
    gap: 11px;
  }

  .brand-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, var(--blue-deep) 0%, var(--blue-accent) 100%);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
  }

  .brand-icon i {
    color: #fff;
    font-size: 16px;
  }

  .brand-text {
    display: flex;
    flex-direction: column;
  }

  .brand-title {
    font-family: 'Playfair Display', serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--blue-deep);
    line-height: 1.1;
    letter-spacing: 0.01em;
  }

  .brand-subtitle {
    font-size: 10.5px;
    font-weight: 500;
    color: var(--text-muted);
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-top: 2px;
  }

  .nav-section-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--text-muted);
    padding: 12px 22px 6px;
  }

  .sidebar-nav {
    list-style: none;
    margin: 0;
    padding: 0 12px;
    flex: 1;
  }

  .sidebar-nav li {
    margin-bottom: 2px;
  }

  .sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    color: #475569;
    font-size: 13.5px;
    font-weight: 500;
    letter-spacing: 0.01em;
    transition: background var(--transition), color var(--transition), box-shadow var(--transition), transform var(--transition);
    position: relative;
  }

  .sidebar-nav a .nav-icon {
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px;
    background: transparent;
    font-size: 14px;
    flex-shrink: 0;
    transition: background var(--transition), color var(--transition);
    color: #94a3b8;
  }

  .sidebar-nav a:hover {
    background: var(--gray-soft);
    color: var(--blue-mid);
    transform: translateX(2px);
  }

  .sidebar-nav a:hover .nav-icon {
    background: var(--blue-light);
    color: var(--blue-mid);
  }

  .sidebar-nav a.active {
    background: linear-gradient(135deg, var(--blue-mid) 0%, #1d4ed8 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    font-weight: 600;
  }

  .sidebar-nav a.active .nav-icon {
    background: rgba(255,255,255,0.2);
    color: #fff;
  }

  .sidebar-nav a.active::after {
    content: '';
    position: absolute;
    right: 12px;
    width: 6px; height: 6px;
    background: #bfdbfe;
    border-radius: 50%;
  }

  .sidebar-divider {
    height: 1px;
    background: #e8eef8;
    margin: 14px 22px;
  }

  .sidebar-logout {
    padding: 0 12px 20px;
  }

  .sidebar-logout a {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    color: #ef4444;
    font-size: 13.5px;
    font-weight: 600;
    letter-spacing: 0.01em;
    transition: background var(--transition), transform var(--transition);
    border: 1px solid transparent;
  }

  .sidebar-logout a .nav-icon {
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px;
    background: #fef2f2;
    color: #ef4444;
    font-size: 14px;
    flex-shrink: 0;
    transition: background var(--transition);
  }

  .sidebar-logout a:hover {
    background: #fef2f2;
    border-color: #fecaca;
    transform: translateX(2px);
  }

  .sidebar-user {
    margin: 0 12px 12px;
    padding: 12px;
    background: var(--gray-soft);
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #e2eaf7;
  }

  .user-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-deep), var(--blue-accent));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .user-avatar i {
    color: #fff;
    font-size: 14px;
  }

  .user-info {
    flex: 1;
    min-width: 0;
  }

  .user-name {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--blue-deep);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .user-role {
    font-size: 10.5px;
    color: var(--text-muted);
    font-weight: 500;
    letter-spacing: 0.04em;
  }

  @keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-12px); }
    to   { opacity: 1; transform: translateX(0); }
  }

  .sidebar-nav li {
    animation: slideInLeft 0.3s ease both;
  }
  .sidebar-nav li:nth-child(1) { animation-delay: 0.05s; }
  .sidebar-nav li:nth-child(2) { animation-delay: 0.10s; }
  .sidebar-nav li:nth-child(3) { animation-delay: 0.15s; }
  .sidebar-nav li:nth-child(4) { animation-delay: 0.20s; }
  .sidebar-nav li:nth-child(5) { animation-delay: 0.25s; }
</style>

<div class="admin-sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <div class="brand-logo-wrap">
      <div class="brand-icon">
        <i class="fa-solid fa-car-side"></i>
      </div>
      <div class="brand-text">
        <span class="brand-title">RentaCar</span>
        <span class="brand-subtitle">Admin Panel</span>
      </div>
    </div>
  </div>

  <!-- Main Navigation -->
  <span class="nav-section-label">Main Menu</span>
  <ul class="sidebar-nav">
    <li>
      <a href="dashboard.php" class="active">
        <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
        Dashboard
      </a>
    </li>
    <li>
      <a href="mobil.php">
        <span class="nav-icon"><i class="fa-solid fa-car"></i></span>
        Data Mobil
      </a>
    </li>
    <li>
      <a href="booking.php">
        <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
        Data Booking
      </a>
    </li>
    <li>
      <a href="client.php">
        <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
        Data Client
      </a>
    </li>
    <li>
      <a href="laporan.php">
        <span class="nav-icon"><i class="fa-solid fa-chart-bar"></i></span>
        Laporan
      </a>
    </li>
  </ul>

  <!-- Divider -->
  <div class="sidebar-divider"></div>

  <!-- User Card -->
  <div class="sidebar-user">
    <div class="user-avatar">
      <i class="fa-solid fa-user-tie"></i>
    </div>
    <div class="user-info">
      <div class="user-name">Administrator</div>
      <div class="user-role">Admin</div>
    </div>
  </div>

  <!-- Logout -->
  <div class="sidebar-logout">
    <a href="logout.php">
      <span class="nav-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
      Logout
    </a>
  </div>

</div>

<script>
  (function () {
    // Ambil nama file dari URL halaman saat ini
    var currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';

    var navLinks = document.querySelectorAll('.sidebar-nav a');

    // Reset semua active, lalu set sesuai URL aktif
    navLinks.forEach(function (link) {
      link.classList.remove('active');
      var linkPage = link.getAttribute('href').split('/').pop();
      if (linkPage === currentPage) {
        link.classList.add('active');
      }
    });

    // Fallback ke Dashboard jika tidak ada yang cocok
    if (!document.querySelector('.sidebar-nav a.active') && navLinks.length > 0) {
      navLinks[0].classList.add('active');
    }

    // Saat diklik: pindahkan active state langsung sebelum navigasi
    navLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        navLinks.forEach(function (l) { l.classList.remove('active'); });
        link.classList.add('active');
      });
    });
  })();
</script>