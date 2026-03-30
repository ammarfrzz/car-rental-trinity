<?php
include "include/koneksi.php";

session_start(); 
include "include/koneksi.php";

// brand_filter hanya dipakai untuk data awal halaman
$brand_filter = "";
// Semua query mobil kini ditangani oleh get_cars.php via AJAX
// Tapi kita tetap butuh koneksi DB untuk dropdown brand
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trinity Rental — Premium Drive Experience</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --dark: #0D0D0D;
            --dark-card: #161616;
            --dark-border: #252525;
            --text-muted: #888;
            --text-light: #CCCCCC;
            --white: #FFFFFF;
            --accent: #C9A84C;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--dark);
            color: var(--white);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========== BACKGROUND TEXTURE ========== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(201,168,76,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 90% 80%, rgba(201,168,76,0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* ========== NAVBAR ========== */
        .navbar-custom {
            background: rgba(13,13,13,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--dark-border);
            padding: 1.1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--dark);
        }

        .brand-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--white);
            letter-spacing: 0.5px;
        }

        .brand-text span {
            color: var(--gold);
        }

        .nav-badge {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--dark);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ========== HERO BANNER ========== */
        .hero-section {
            position: relative;
            padding: 5rem 0 7rem;
            text-align: center;
            overflow: hidden;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=1920&q=90&auto=format&fit=crop');
            background-size: cover;
            background-position: center 40%;
            background-repeat: no-repeat;
            z-index: 0;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                linear-gradient(to bottom, 
                    rgba(13,13,13,0.60) 0%, 
                    rgba(13,13,13,0.25) 35%, 
                    rgba(13,13,13,0.55) 75%, 
                    rgba(13,13,13,1) 100%),
                radial-gradient(ellipse 100% 60% at 50% 55%, rgba(201,168,76,0.10) 0%, transparent 65%);
            z-index: 1;
        }

        .hero-section > * {
            position: relative;
            z-index: 2;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.1);
            border: 1px solid rgba(201,168,76,0.3);
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 30px;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.6s ease forwards;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 6vw, 4.2rem);
            font-weight: 900;
            line-height: 1.12;
            color: var(--white);
            margin-bottom: 1.2rem;
            animation: fadeInUp 0.7s ease 0.1s both;
        }

        .hero-title em {
            font-style: italic;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            color: rgba(255,255,255,0.80);
            font-size: 1.05rem;
            font-weight: 300;
            max-width: 540px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
            text-shadow: 0 1px 8px rgba(0,0,0,0.6);
            animation: fadeInUp 0.7s ease 0.2s both;
        }

        /* ========== HERO SCROLL INDICATOR ========== */
        .hero-scroll-indicator {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            z-index: 2;
            animation: fadeInUp 1s ease 1s both;
            cursor: pointer;
        }

        .hero-scroll-indicator span {
            font-size: 0.65rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            font-weight: 600;
        }

        .scroll-mouse {
            width: 22px;
            height: 36px;
            border: 2px solid rgba(201,168,76,0.6);
            border-radius: 12px;
            position: relative;
        }

        .scroll-mouse::after {
            content: '';
            position: absolute;
            width: 4px;
            height: 8px;
            background: var(--gold);
            border-radius: 2px;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            animation: scrollDown 1.6s ease-in-out infinite;
        }

        @keyframes scrollDown {
            0%   { opacity: 1; transform: translateX(-50%) translateY(0); }
            80%  { opacity: 0; transform: translateX(-50%) translateY(14px); }
            100% { opacity: 0; transform: translateX(-50%) translateY(14px); }
        }

        /* ========== PROMO BANNER ========== */
        .promo-bar {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(232,201,106,0.08));
            border: 1px solid rgba(201,168,76,0.35);
            border-radius: 50px;
            padding: 0.7rem 1.8rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--gold-light);
            margin-bottom: 1rem;
            animation: fadeInUp 0.7s ease 0.3s both;
        }

        .promo-dot {
            width: 8px;
            height: 8px;
            background: var(--gold);
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        /* ========== STATS BAR ========== */
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 3rem;
            padding: 2rem 0;
            border-top: 1px solid var(--dark-border);
            border-bottom: 1px solid var(--dark-border);
            margin-bottom: 3rem;
            animation: fadeInUp 0.7s ease 0.4s both;
        }

        .stat-item {
            text-align: center;
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold);
            display: block;
        }

        .stat-label {
            font-size: 0.72rem;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* ========== FILTER SECTION ========== */
        .filter-section {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 18px;
            padding: 1.5rem 2rem;
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.7s ease 0.45s both;
        }

        .filter-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.8rem;
            display: block;
        }

        .filter-select {
            background: var(--dark) !important;
            border: 1px solid var(--dark-border) !important;
            color: var(--white) !important;
            border-radius: 12px !important;
            padding: 0.75rem 1.2rem !important;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23C9A84C' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 1rem center !important;
        }

        .filter-select:focus {
            border-color: var(--gold) !important;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15) !important;
            outline: none;
        }

        .filter-select option {
            background: var(--dark);
        }

        .btn-filter {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--dark);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.8rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201,168,76,0.4);
        }

        .btn-reset {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 0.75rem 1.4rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-reset:hover {
            border-color: var(--text-muted);
            color: var(--white);
        }

        /* ========== SECTION TITLE ========== */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.8rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, var(--dark-border), transparent);
            margin-left: 1.5rem;
        }

        /* ========== CAR CARD ========== */
        .car-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            height: 100%;
            position: relative;
            animation: fadeInUp 0.6s ease both;
        }

        .car-card:hover {
            border-color: rgba(201,168,76,0.4);
            transform: translateY(-6px);
            box-shadow: 0 24px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(201,168,76,0.2);
        }

        .car-card:hover .card-img-overlay-custom {
            opacity: 1;
        }

        .car-img-wrap {
            position: relative;
            overflow: hidden;
            height: 210px;
        }

        .car-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .car-card:hover .car-img-wrap img {
            transform: scale(1.05);
        }

        .card-img-overlay-custom {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13,13,13,0.85) 0%, transparent 50%);
            opacity: 0.6;
            transition: opacity 0.4s;
        }

        .badge-status {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(201,168,76,0.4);
            color: var(--gold-light);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .badge-brand {
            position: absolute;
            top: 14px;
            right: 14px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--dark);
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .card-body-custom {
            padding: 1.4rem 1.6rem 1.6rem;
        }

        .car-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .car-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .car-meta i {
            color: var(--gold);
            font-size: 0.7rem;
        }

        .divider-custom {
            height: 1px;
            background: var(--dark-border);
            margin: 1rem 0;
        }

        .price-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 1.2rem;
        }

        .price-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 500;
            display: block;
            margin-bottom: 2px;
        }

        .price-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gold);
            line-height: 1;
        }

        .price-unit {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
        }

        .price-discount {
            text-align: right;
        }

        .discount-badge {
            background: rgba(201,168,76,0.15);
            border: 1px solid rgba(201,168,76,0.3);
            color: var(--gold);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .btn-booking {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            color: var(--dark);
            border: none;
            border-radius: 12px;
            padding: 0.85rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.35s;
            position: relative;
            overflow: hidden;
        }

        .btn-booking::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-booking:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(201,168,76,0.45);
            color: var(--dark);
        }

        .btn-booking:hover::before {
            opacity: 1;
        }

        .btn-booking i {
            margin-right: 6px;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon {
            font-size: 3.5rem;
            color: var(--dark-border);
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .empty-sub {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        /* ========== FOOTER ========== */
        .footer-custom {
            border-top: 1px solid var(--dark-border);
            padding: 2.5rem 0;
            margin-top: 5rem;
            text-align: center;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 900;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }

        .footer-tagline {
            font-size: 0.8rem;
            color: var(--text-muted);
            letter-spacing: 1px;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .car-card:nth-child(1) { animation-delay: 0.05s; }
        .car-card:nth-child(2) { animation-delay: 0.12s; }
        .car-card:nth-child(3) { animation-delay: 0.19s; }
        .car-card:nth-child(4) { animation-delay: 0.26s; }
        .car-card:nth-child(5) { animation-delay: 0.33s; }
        .car-card:nth-child(6) { animation-delay: 0.40s; }

        /* ========== ABOUT SECTION ========== */
        .about-section {
            padding: 4rem 0;
            margin-bottom: 2rem;
            position: relative;
        }

        .about-section::before {
            content: '';
            position: absolute;
            left: 0; right: 0; top: 0; bottom: 0;
            background: radial-gradient(ellipse 60% 80% at 0% 50%, rgba(201,168,76,0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .about-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.1);
            border: 1px solid rgba(201,168,76,0.3);
            color: var(--gold);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 5px 16px;
            border-radius: 30px;
            margin-bottom: 1.2rem;
        }

        .about-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 900;
            line-height: 1.2;
            color: var(--white);
            margin-bottom: 1.2rem;
        }

        .about-title em {
            font-style: italic;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .about-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            max-width: 520px;
        }

        .about-features {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .about-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            color: var(--text-light);
        }

        .about-features li .feat-icon {
            width: 28px;
            height: 28px;
            background: rgba(201,168,76,0.15);
            border: 1px solid rgba(201,168,76,0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        .about-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--dark);
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .about-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(201,168,76,0.4);
            color: var(--dark);
        }

        .about-visual {
            position: relative;
        }

        .about-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .about-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 18px;
            padding: 1.6rem 1.4rem;
            text-align: center;
            transition: all 0.35s;
            position: relative;
            overflow: hidden;
        }

        .about-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201,168,76,0.04), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .about-card:hover {
            border-color: rgba(201,168,76,0.4);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.4);
        }

        .about-card:hover::after { opacity: 1; }

        .about-card-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--dark);
            margin: 0 auto 0.8rem;
        }

        .about-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.4rem;
        }

        .about-card-text {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .about-card.featured {
            grid-column: span 2;
            background: linear-gradient(135deg, rgba(201,168,76,0.1), rgba(201,168,76,0.04));
            border-color: rgba(201,168,76,0.3);
            display: flex;
            align-items: center;
            gap: 1.2rem;
            text-align: left;
            padding: 1.4rem 1.6rem;
        }

        .about-card.featured .about-card-icon {
            margin: 0;
            flex-shrink: 0;
            width: 52px;
            height: 52px;
        }

        .about-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--dark-border), transparent);
            margin: 3.5rem 0;
        }

        /* ========== SECTION DIVIDER ========== */
        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--dark-border), transparent);
            margin: 4rem 0;
        }

        .section-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.1);
            border: 1px solid rgba(201,168,76,0.3);
            color: var(--gold);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 5px 16px;
            border-radius: 30px;
            margin-bottom: 1rem;
        }

        .section-heading {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            font-weight: 900;
            color: var(--white);
            margin-bottom: 0.6rem;
            line-height: 1.2;
        }

        .section-heading em {
            font-style: italic;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-sub {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
            max-width: 480px;
        }

        /* ========== TESTIMONIAL ========== */
        .testimonial-section {
            padding: 3rem 0;
        }

        .testi-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            position: relative;
            transition: all 0.35s;
            overflow: hidden;
        }

        .testi-card::before {
            content: '\201C';
            position: absolute;
            top: -10px;
            right: 20px;
            font-family: 'Playfair Display', serif;
            font-size: 7rem;
            color: rgba(201,168,76,0.08);
            line-height: 1;
            pointer-events: none;
        }

        .testi-card:hover {
            border-color: rgba(201,168,76,0.35);
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        }

        .testi-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 1rem;
        }

        .testi-stars i {
            color: var(--gold);
            font-size: 0.8rem;
        }

        .testi-text {
            color: var(--text-light);
            font-size: 0.9rem;
            line-height: 1.75;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid var(--dark-border);
            padding-top: 1.2rem;
        }

        .testi-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--dark);
            flex-shrink: 0;
        }

        .testi-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--white);
            margin-bottom: 2px;
        }

        .testi-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .testi-badge {
            margin-left: auto;
            background: rgba(201,168,76,0.12);
            border: 1px solid rgba(201,168,76,0.25);
            color: var(--gold);
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* ========== FAQ ========== */
        .faq-section {
            padding: 3rem 0;
        }

        .faq-item {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 16px;
            margin-bottom: 0.9rem;
            overflow: hidden;
            transition: border-color 0.3s;
        }

        .faq-item.active {
            border-color: rgba(201,168,76,0.4);
        }

        .faq-question {
            width: 100%;
            background: transparent;
            border: none;
            padding: 1.2rem 1.6rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            cursor: pointer;
            text-align: left;
            transition: background 0.3s;
        }

        .faq-question:hover {
            background: rgba(201,168,76,0.04);
        }

        .faq-q-text {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--white);
        }

        .faq-icon {
            width: 30px;
            height: 30px;
            background: rgba(201,168,76,0.1);
            border: 1px solid rgba(201,168,76,0.25);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 0.7rem;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .faq-item.active .faq-icon {
            background: var(--gold);
            color: var(--dark);
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.3s;
        }

        .faq-answer.open {
            max-height: 300px;
            padding-bottom: 1.4rem;
        }

        .faq-answer-inner {
            padding: 0 1.6rem;
            color: var(--text-muted);
            font-size: 0.88rem;
            line-height: 1.75;
            border-top: 1px solid var(--dark-border);
            padding-top: 1.1rem;
        }

        /* ========== CONTACT ========== */
        .contact-section {
            padding: 3rem 0;
        }

        .contact-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.35s;
        }

        .contact-card:hover {
            border-color: rgba(201,168,76,0.35);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.35);
        }

        .contact-icon-wrap {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--dark);
            margin-bottom: 1.2rem;
        }

        .contact-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }

        .contact-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.3rem;
        }

        .contact-note {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .contact-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 0.8rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--gold);
            text-decoration: none;
            transition: color 0.3s;
        }

        .contact-link:hover {
            color: var(--gold-light);
        }

        .contact-map-box {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            min-height: 280px;
            display: flex;
            flex-direction: column;
        }

        .contact-map-header {
            padding: 1.2rem 1.6rem;
            border-bottom: 1px solid var(--dark-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-map-header i {
            color: var(--gold);
        }

        .contact-map-title {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--white);
        }

        .contact-map-body {
            flex: 1;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 0.8rem;
            padding: 2rem;
            text-align: center;
        }

        .map-pin-icon {
            width: 56px;
            height: 56px;
            background: rgba(201,168,76,0.12);
            border: 2px solid rgba(201,168,76,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }

        .map-address {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .map-open-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--dark);
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            margin-top: 0.5rem;
            transition: all 0.3s;
        }

        .map-open-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(201,168,76,0.35);
            color: var(--dark);
        }

        /* ========== FOOTER (REDESIGNED) ========== */
        .footer-new {
            background: var(--dark-card);
            border-top: 1px solid var(--dark-border);
            margin-top: 5rem;
        }

        .footer-top {
            padding: 3.5rem 0 2.5rem;
        }

        .footer-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 0.3rem;
        }

        .footer-brand-name span {
            color: var(--gold);
        }

        .footer-tagline-new {
            font-size: 0.78rem;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }

        .footer-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 280px;
            margin-bottom: 1.4rem;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
        }

        .footer-social-btn {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--dark-border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-social-btn:hover {
            background: rgba(201,168,76,0.15);
            border-color: rgba(201,168,76,0.4);
            color: var(--gold);
            transform: translateY(-2px);
        }

        .footer-col-title {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 0.7rem;
        }

        .footer-col-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 28px;
            height: 2px;
            background: linear-gradient(to right, var(--gold), var(--gold-light));
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .footer-links li a {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s, gap 0.3s;
        }

        .footer-links li a i {
            font-size: 0.5rem;
            color: var(--gold);
            opacity: 0.6;
        }

        .footer-links li a:hover {
            color: var(--gold-light);
            gap: 10px;
        }

        .footer-contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .footer-contact-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.83rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .footer-contact-list li i {
            color: var(--gold);
            font-size: 0.8rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .footer-bottom {
            border-top: 1px solid var(--dark-border);
            padding: 1.4rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .footer-copy {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .footer-copy span {
            color: var(--gold);
        }

        .footer-bottom-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-bottom-links a {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-bottom-links a:hover {
            color: var(--gold);
        }

        /* ========== VIEW MORE ========== */
        .car-hidden {
            display: none !important;
        }

        .car-reveal {
            display: block !important;
            animation: carReveal 0.45s ease both;
        }

        @keyframes carReveal {
            from { opacity: 0; transform: translateY(22px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        .view-more-wrap {
            position: relative;
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
        }

        /* soft fade gradient above button hinting more content */
        .view-more-fade {
            position: absolute;
            top: -80px; left: 0; right: 0;
            height: 80px;
            background: linear-gradient(to bottom, transparent, var(--dark));
            pointer-events: none;
            transition: opacity 0.4s;
        }

        .view-more-wrap.expanded .view-more-fade {
            opacity: 0;
        }

        .btn-view-more {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 50px;
            padding: 0.85rem 2rem;
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-view-more::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201,168,76,0.12), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-view-more:hover {
            border-color: rgba(201,168,76,0.5);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.4), 0 0 0 1px rgba(201,168,76,0.15);
        }

        .btn-view-more:hover::before { opacity: 1; }

        .btn-view-more-inner {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .btn-view-more-inner > i {
            color: var(--gold);
            font-size: 0.9rem;
        }

        .view-more-count {
            background: rgba(201,168,76,0.15);
            border: 1px solid rgba(201,168,76,0.3);
            color: var(--gold);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .btn-view-more-chevron {
            color: var(--text-muted);
            font-size: 0.8rem;
            transition: transform 0.35s ease;
            position: relative;
            z-index: 1;
        }

        .btn-view-more.expanded .btn-view-more-chevron {
            transform: rotate(180deg);
        }

        /* ========== SCROLLBAR ========== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--dark); }
        ::-webkit-scrollbar-thumb { background: var(--dark-border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        /* ========== NAV LINKS ========== */
        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .nav-link-item {
            display: inline-block;
            padding: 0.45rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: color 0.25s, background 0.25s;
            letter-spacing: 0.3px;
        }

        .nav-link-item:hover,
        .nav-link-item.active {
            color: var(--white);
            background: rgba(255,255,255,0.06);
        }

        .nav-link-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 8px;
            letter-spacing: 0.3px;
            transition: transform 0.25s, box-shadow 0.25s;
            margin-left: 0.4rem;
        }

        .nav-link-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201,168,76,0.4);
            color: var(--dark);
        }

        /* ========== HAMBURGER ========== */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
        }

        .nav-hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--text-muted);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .nav-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); background: var(--gold); }
        .nav-hamburger.open span:nth-child(2) { opacity: 0; }
        .nav-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); background: var(--gold); }

        /* ========== MOBILE DRAWER ========== */
        .nav-mobile-drawer {
            display: none;
            border-top: 1px solid var(--dark-border);
            padding: 1rem 0 0.5rem;
        }

        .nav-mobile-drawer.open {
            display: block;
        }

        .nav-mobile-links {
            list-style: none;
            margin: 0;
            padding: 0 1rem;
        }

        .nav-mobile-links li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.25s;
        }

        .nav-mobile-links li a i {
            color: var(--gold);
            font-size: 0.8rem;
            width: 16px;
        }

        .nav-mobile-links li a:hover {
            color: var(--white);
            background: rgba(255,255,255,0.05);
        }

        .nav-mobile-links li a.mobile-cta-link {
            color: var(--gold);
            font-weight: 700;
        }

        .nav-mobile-links li a.mobile-cta-link:hover {
            background: rgba(201,168,76,0.1);
        }

        @media (max-width: 900px) {
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
        }

        @media (max-width: 576px) {
            .nav-mobile-links li a { font-size: 0.85rem; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom" id="mainNavbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="navbar-brand-custom">
                <div class="brand-icon">
                    <i class="fas fa-car"></i>
                </div>
                <div class="brand-text">TRINITY  <span> Car Rental</span></div>
            </a>

            <!-- Desktop Nav Links -->
            <ul class="nav-links" id="navLinks">
                <li><a href="#beranda" class="nav-link-item" onclick="navScrollTo('beranda')">Beranda</a></li>
                <li><a href="#tentang" class="nav-link-item" onclick="navScrollTo('tentang')">Tentang</a></li>
                <li><a href="#armada" class="nav-link-item" onclick="navScrollTo('armada')">Armada</a></li>
                <li><a href="#testimoni" class="nav-link-item" onclick="navScrollTo('testimoni')">Testimoni</a></li>
                <li><a href="#faq" class="nav-link-item" onclick="navScrollTo('faq')">FAQ</a></li>
                <li>
                    <?php if (isset($_SESSION['client_id'])): ?>
                        <div class="dropdown" style="display:inline-block;">
                            <button class="nav-link-cta" style="border:none;cursor:pointer;"
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                                <?= htmlspecialchars($_SESSION['client_nama']) ?>
                                <i class="fas fa-chevron-down" style="font-size:0.65rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end"
                                style="background:#161616;border-color:#252525;min-width:180px;">
                                <li><hr class="dropdown-divider" style="border-color:#252525;"></li>
                                <li><a class="dropdown-item" href="logout_client.php"
                                       style="color:#ff6b6b;font-size:0.85rem;">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <button class="nav-link-cta" style="border:none;cursor:pointer;"
                                data-bs-toggle="modal" data-bs-target="#modalLogin">
                            <i class="fas fa-user" style="font-size:0.75rem;"></i> Login
                        </button>
                    <?php endif; ?>
                </li>
            </ul>

            <!-- Hamburger -->
            <button class="nav-hamburger" id="navToggle" onclick="toggleMobileNav()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>

        <!-- Mobile Drawer -->
        <div class="nav-mobile-drawer" id="navDrawer">
            <ul class="nav-mobile-links">
                <li><a href="#beranda" onclick="mobileNavGo('beranda')"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="#tentang" onclick="mobileNavGo('tentang')"><i class="fas fa-gem"></i> Tentang</a></li>
                <li><a href="#armada" onclick="mobileNavGo('armada')"><i class="fas fa-car"></i> Armada</a></li>
                <li><a href="#testimoni" onclick="mobileNavGo('testimoni')"><i class="fas fa-heart"></i> Testimoni</a></li>
                <li><a href="#faq" onclick="mobileNavGo('faq')"><i class="fas fa-question-circle"></i> FAQ</a></li>
                <li><a href="#kontak" onclick="mobileNavGo('kontak')" class="mobile-cta-link"><i class="fas fa-paper-plane"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- HERO — full width, outside .container -->
    <div class="hero-section" id="beranda">
        <div class="container position-relative" style="z-index:2;">
            <div class="hero-eyebrow">
                <i class="fas fa-star" style="font-size:0.6rem;"></i>
                Mobil Pilihan Terbaik
            </div>
            <h1 class="hero-title">
                Perjalanan <em>Mewah</em><br>Dimulai dari Sini
            </h1>
            <p class="hero-sub">
                Nikmati kenyamanan berkendara dengan koleksi mobil premium kami. Mudah, cepat, dan terpercaya.
            </p>

            <div class="promo-bar">
                <div class="promo-dot"></div>
                🎉&nbsp; Promo Spesial — Diskon <strong>10%</strong> untuk semua mobil!
                <div class="promo-dot"></div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="hero-scroll-indicator" onclick="navScrollTo('tentang')">
            <div class="scroll-mouse"></div>
            <span>Scroll</span>
        </div>
    </div>

    <div class="container position-relative" style="z-index:1;">

        <!-- ABOUT / COMPANY PROFILE -->
        <div class="about-section" id="tentang">
            <div class="row align-items-center g-5">
                <!-- LEFT: TEXT -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-eyebrow">
                        <i class="fas fa-gem" style="font-size:0.6rem;"></i>
                        Tentang Kami
                    </div>
                    <h2 class="about-title">
                        Lebih dari Sekadar<br><em>Rental Mobil</em>
                    </h2>
                    <p class="about-desc">
                        Trinity Car Rental berdiri sejak 2015 dengan misi menghadirkan pengalaman berkendara premium yang mudah dijangkau oleh semua kalangan. Berbasis di Surabaya, kami telah melayani lebih dari 10.000 pelanggan dengan armada terbaik dan pelayanan bintang lima.
                    </p>
                    <p class="about-desc" style="margin-bottom:2rem;">
                        Setiap kendaraan kami dirawat secara berkala, diasuransikan penuh, dan siap mengantarkan Anda ke mana pun dengan nyaman dan aman.
                    </p>

                    <ul class="about-features">
                        <li>
                            <span class="feat-icon"><i class="fas fa-shield-alt"></i></span>
                            Asuransi penuh termasuk di setiap sewa
                        </li>
                        <li>
                            <span class="feat-icon"><i class="fas fa-headset"></i></span>
                            Layanan pelanggan 24/7 siap membantu Anda
                        </li>
                        <li>
                            <span class="feat-icon"><i class="fas fa-tools"></i></span>
                            Perawatan kendaraan rutin setiap 3.000 km
                        </li>
                        <li>
                            <span class="feat-icon"><i class="fas fa-map-marker-alt"></i></span>
                            Antar-jemput gratis area Surabaya & sekitarnya
                        </li>
                        <li>
                            <span class="feat-icon"><i class="fas fa-clock"></i></span>
                            Proses booking cepat, konfirmasi dalam 15 menit
                        </li>
                    </ul>

                    <a href="#armada" class="about-cta">
                        <i class="fas fa-car"></i> Lihat Armada Kami
                    </a>
                </div>

                <!-- RIGHT: CARD GRID -->
                <div class="col-lg-6">
                    <div class="about-card-grid">
                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="about-card-title">Berdiri Sejak</div>
                            <div class="about-card-text">2015 dan sudah 10+ tahun melayani pelanggan setia di Surabaya</div>
                        </div>
                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-car-side"></i>
                            </div>
                            <div class="about-card-title">20+ Armada</div>
                            <div class="about-card-text">Dari city car hingga super car dan hyper car semua tersedia</div>
                        </div>
                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="about-card-title">10K+ Pelanggan</div>
                            <div class="about-card-text">Ribuan pelanggan puas yang terus kembali mempercayai kami</div>
                        </div>
                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="about-card-title">Rating 5 Bintang</div>
                            <div class="about-card-text">Penilaian tertinggi di platform Google & Trustpilot</div>
                        </div>
                        <div class="about-card featured">
                            <div class="about-card-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <div class="about-card-title">Penghargaan Rental Terbaik 2023</div>
                                <div class="about-card-text">Dinobatkan sebagai rental mobil premium terbaik di Jawa Timur oleh Asosiasi Otomotif Indonesia.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-divider"></div>

        <!-- FILTER -->
        <div id="armada"></div>
        <div class="filter-section">
            <span class="filter-label"><i class="fas fa-sliders-h me-2" style="color:var(--gold)"></i>Filter Kendaraan</span>
            <form id="filterForm" onsubmit="filterArmada(event)">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label" style="font-size:0.8rem; color:var(--text-muted); margin-bottom:6px;">Pilih Brand</label>
                        <select name="brand" id="brandSelect" class="form-control filter-select">
                            <option value="">✦ Semua Brand</option>
                            <?php
                            $brand_query = mysqli_query($conn, "SELECT DISTINCT brand FROM mobil");
                            while ($b = mysqli_fetch_assoc($brand_query)) {
                                echo "<option value='{$b['brand']}'>{$b['brand']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn-filter w-100">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn-reset w-100" onclick="resetFilter()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- SECTION HEADER — diupdate dinamis oleh JS -->
        <div class="section-header">
            <h2 class="section-title" id="armadaTitle">Semua Armada Tersedia</h2>
            <div class="section-line"></div>
        </div>

        <!-- CAR GRID — diisi ulang oleh AJAX -->
        <div class="row g-4" id="carGrid">
            <!-- konten dimuat saat halaman pertama kali load via JS -->
        </div>

        <!-- VIEW MORE BUTTON — ditampilkan/disembunyikan oleh JS -->
        <div class="view-more-wrap" id="viewMoreWrap" style="display:none;">
            <div class="view-more-fade"></div>
            <button class="btn-view-more" id="btnViewMore" onclick="toggleViewMore()">
                <span class="btn-view-more-inner">
                    <i class="fas fa-th-large"></i>
                    <span id="btnViewMoreText">Lihat Semua Armada</span>
                    <span class="view-more-count" id="viewMoreCount"></span>
                </span>
                <i class="fas fa-chevron-down btn-view-more-chevron" id="btnChevron"></i>
            </button>
        </div>

        <!-- SECTION DIVIDER -->
        <div class="section-divider"></div>

        <!-- ===== TESTIMONIAL ===== -->
        <div class="testimonial-section" id="testimoni">
            <div class="text-center mb-5">
                <div class="section-eyebrow mx-auto" style="width:fit-content;">
                    <i class="fas fa-heart" style="font-size:0.6rem;"></i>
                    Kata Mereka
                </div>
                <h2 class="section-heading">Apa Kata <em>Pelanggan Kami</em></h2>
                <p class="section-sub mx-auto text-center">Lebih dari 10.000 pelanggan telah merasakan layanan terbaik Trinity Car Rental.</p>
            </div>

            <div class="row g-4">
                <!-- Testi 1 -->
                <div class="col-md-4">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testi-text">"Pelayanannya luar biasa! Mobilnya bersih, terawat, dan proses booking sangat mudah. Saya sudah sewa lebih dari 5 kali dan selalu puas."</p>
                        <div class="testi-author">
                            <div class="testi-avatar">A</div>
                            <div>
                                <div class="testi-name">Andika Pratama</div>
                                <div class="testi-role">Pengusaha · Surabaya</div>
                            </div>
                            <span class="testi-badge">Pelanggan Setia</span>
                        </div>
                    </div>
                </div>
                <!-- Testi 2 -->
                <div class="col-md-4">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testi-text">"Sewa BMW untuk pernikahan saya, dan semuanya berjalan sempurna. Tim Trinity sangat profesional dan responsif. Sangat direkomendasikan!"</p>
                        <div class="testi-author">
                            <div class="testi-avatar">R</div>
                            <div>
                                <div class="testi-name">Rina Kusuma</div>
                                <div class="testi-role">Event Organizer · Sidoarjo</div>
                            </div>
                            <span class="testi-badge">Verified</span>
                        </div>
                    </div>
                </div>
                <!-- Testi 3 -->
                <div class="col-md-4">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="testi-text">"Harga terjangkau, kondisi mobil prima, dan antar-jemput gratis ke hotel! CS mereka fast response banget. Bakal balik lagi next trip ke Surabaya."</p>
                        <div class="testi-author">
                            <div class="testi-avatar">F</div>
                            <div>
                                <div class="testi-name">Fajar Hidayat</div>
                                <div class="testi-role">Traveler · Jakarta</div>
                            </div>
                            <span class="testi-badge">Google Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- ===== FAQ / Q&A ===== -->
        <div class="faq-section" id="faq">
            <div class="row g-5 align-items-start">
                <div class="col-lg-4">
                    <div class="section-eyebrow">
                        <i class="fas fa-question-circle" style="font-size:0.6rem;"></i>
                        FAQ
                    </div>
                    <h2 class="section-heading">Pertanyaan yang <em>Sering Ditanya</em></h2>
                    <p class="section-sub" style="margin-top:0.8rem;">Belum menemukan jawaban? Hubungi kami langsung via WhatsApp, kami siap membantu 24/7.</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="about-cta" style="margin-top:1.5rem; display:inline-flex;">
                        <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="faq-item active">
                        <button class="faq-question" onclick="toggleFaq(this)">
                            <span class="faq-q-text">Apa saja syarat untuk menyewa mobil di Trinity?</span>
                            <span class="faq-icon"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer open">
                            <div class="faq-answer-inner">
                                Syarat sewa cukup mudah: (1) KTP asli yang masih berlaku, (2) SIM A aktif, (3) Deposit sesuai jenis kendaraan. Untuk tamu luar kota, bisa menggunakan KTP + paspor. Tidak perlu kartu kredit!
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFaq(this)">
                            <span class="faq-q-text">Apakah tersedia layanan antar-jemput?</span>
                            <span class="faq-icon"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Ya! Kami menyediakan layanan antar-jemput gratis untuk area Surabaya, Sidoarjo, dan Gresik. Untuk area luar dapat dikenakan biaya tambahan tergantung jarak. Hubungi kami untuk detailnya.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFaq(this)">
                            <span class="faq-q-text">Bagaimana jika terjadi kerusakan atau kecelakaan saat menyewa?</span>
                            <span class="faq-icon"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Semua armada kami dilindungi asuransi penuh (comprehensive). Jika terjadi insiden, segera hubungi CS kami 24/7 dan kami akan memandu Anda langkah selanjutnya. Tidak perlu panik — kami siap membantu.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFaq(this)">
                            <span class="faq-q-text">Berapa lama minimal masa sewa?</span>
                            <span class="faq-icon"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Minimal sewa adalah 1 hari (24 jam). Tersedia juga paket sewa mingguan dan bulanan dengan harga lebih hemat hingga 25%. Hubungi kami untuk info paket spesial.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFaq(this)">
                            <span class="faq-q-text">Apakah bisa menyewa dengan supir?</span>
                            <span class="faq-icon"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Tentu bisa! Kami menyediakan layanan sewa dengan pengemudi profesional berpengalaman dengan biaya tambahan mulai Rp 300.000/hari. Pengemudi kami ramah, hafal rute Surabaya, dan siap siaga selama durasi sewa.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- ===== CONTACT ===== -->
        <div class="contact-section" id="kontak">
            <div class="text-center mb-5">
                <div class="section-eyebrow mx-auto" style="width:fit-content;">
                    <i class="fas fa-paper-plane" style="font-size:0.6rem;"></i>
                    Hubungi Kami
                </div>
                <h2 class="section-heading">Ada Pertanyaan? <em>Kami Siap Membantu</em></h2>
                <p class="section-sub mx-auto text-center">Tim kami siap merespons Anda kapan saja selama 24/7.</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon-wrap">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-label">WhatsApp</div>
                        <div class="contact-value">+62 812-3456-7890</div>
                        <div class="contact-note">Respon cepat · 24/7</div>
                        <a href="https://wa.me/6281234567890" target="_blank" class="contact-link">
                            <i class="fas fa-arrow-right" style="font-size:0.65rem;"></i> Chat Sekarang
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon-wrap"> 
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-label">Email</div>
                        <div class="contact-value">info@trinity.id</div>
                        <div class="contact-note">Balas dalam 1–2 jam</div>
                        <a href="mailto:info@trinity.id" class="contact-link">
                            <i class="fas fa-arrow-right" style="font-size:0.65rem;"></i> Kirim Email
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon-wrap">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-label">Telepon</div>
                        <div class="contact-value">(031) 555-1234</div>
                        <div class="contact-note">Senin–Minggu, 08.00–21.00</div>
                        <a href="tel:+62315551234" class="contact-link">
                            <i class="fas fa-arrow-right" style="font-size:0.65rem;"></i> Hubungi
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon-wrap">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-label">Lokasi</div>
                        <div class="contact-value">Surabaya Pusat</div>
                        <div class="contact-note">Jl. Raya Darmo No. 88, Surabaya</div>
                        <a href="https://maps.google.com" target="_blank" class="contact-link">
                            <i class="fas fa-arrow-right" style="font-size:0.65rem;"></i> Buka Maps
                        </a>
                    </div>
                </div>
            </div>

            <!-- Map placeholder + CTA -->
            <div class="contact-map-box">
                <div class="contact-map-header">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="contact-map-title">Lokasi Trinity Car Rental — Surabaya</span>
                </div>
                <div class="contact-map-body">
                    <div class="map-pin-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="map-address">
                        Jl. Raya Darmo No. 88, Darmo,<br>Kec. Wonokromo, Kota Surabaya,<br>Jawa Timur 60241
                    </div>
                    <a href="https://maps.google.com/?q=Jl+Raya+Darmo+Surabaya" target="_blank" class="map-open-btn">
                        <i class="fas fa-directions"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>

    </div><!-- end .container -->

    <!-- ===== FOOTER (REDESIGNED) ===== -->
    <footer class="footer-new">
        <div class="container">
            <div class="footer-top">
                <div class="row g-4">
                    <!-- Brand col -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand-name">TRINITY CAR <span>Rental</span></div>
                        <div class="footer-tagline-new">Premium Drive Experience · Surabaya</div>
                        <p class="footer-desc">Menghadirkan pengalaman berkendara premium sejak 2015. Armada lengkap, layanan terpercaya, harga terjangkau.</p>
                        <div class="footer-socials">
                            <a href="#" class="footer-social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="footer-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="footer-social-btn" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="footer-social-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                    <!-- Quick links -->
                    <div class="col-lg-2 col-md-6 col-6">
                        <div class="footer-col-title">Navigasi</div>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-circle"></i> Beranda</a></li>
                            <li><a href="#armada"><i class="fas fa-circle"></i> Armada</a></li>
                            <li><a href="#testimoni"><i class="fas fa-circle"></i> Testimoni</a></li>
                            <li><a href="#faq"><i class="fas fa-circle"></i> FAQ</a></li>
                            <li><a href="#kontak"><i class="fas fa-circle"></i> Kontak</a></li>
                        </ul>
                    </div>
                    <!-- Services -->
                    <div class="col-lg-2 col-md-6 col-6">
                        <div class="footer-col-title">Layanan</div>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-circle"></i> Sewa Harian</a></li>
                            <li><a href="#"><i class="fas fa-circle"></i> Sewa Mingguan</a></li>
                            <li><a href="#"><i class="fas fa-circle"></i> Sewa Bulanan</a></li>
                            <li><a href="#"><i class="fas fa-circle"></i> Dengan Supir</a></li>
                        </ul>
                    </div>
                    <!-- Contact -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-col-title">Kontak</div>
                        <ul class="footer-contact-list">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                Jl. Raya Darmo No. 88, Darmo, Surabaya, Jawa Timur 60241
                            </li>
                            <li>
                                <i class="fab fa-whatsapp"></i>
                                +62 812-3456-7890
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                (031) 555-1234
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                info@trinity.id
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                Buka setiap hari · 08.00 – 21.00 WIB
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-copy">
                    &copy; 2024 <span>Trinity Car Rental</span>. All rights reserved. Made with <span>♥</span> in Surabaya.
                </div>
                <div class="footer-bottom-links">
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ===== VIEW MORE ===== */
        let expanded = false;

        function toggleViewMore() {
            expanded = !expanded;
            const hiddenCols = document.querySelectorAll('.car-hidden, .car-reveal');
            const btn        = document.getElementById('btnViewMore');
            const wrap       = document.getElementById('viewMoreWrap');
            const text       = document.getElementById('btnViewMoreText');
            const count      = document.getElementById('viewMoreCount');
            const chevron    = document.getElementById('btnChevron');

            if (expanded) {
                // Show all hidden cards with staggered animation
                let delay = 0;
                hiddenCols.forEach(col => {
                    if (col.classList.contains('car-hidden')) {
                        col.classList.remove('car-hidden');
                        col.classList.add('car-reveal');
                        col.querySelector('.car-reveal, div').style.animationDelay = delay + 's';
                        delay += 0.07;
                    }
                });
                text.textContent = 'Sembunyikan';
                count.textContent = 'tampilkan lebih sedikit';
                btn.classList.add('expanded');
                wrap.classList.add('expanded');
            } else {
                // Hide cards beyond 6 again
                const allCols = document.querySelectorAll('#carGrid > [class*="col-"]');
                allCols.forEach((col, i) => {
                    if (i >= 6) {
                        col.classList.remove('car-reveal');
                        col.classList.add('car-hidden');
                    }
                });
                text.textContent = 'Lihat Semua Armada';
                btn.classList.remove('expanded');
                wrap.classList.remove('expanded');
                // Restore count badge
                count.textContent = (allCols.length - 6) + ' mobil lainnya';

                // Smooth scroll back to grid
                setTimeout(() => {
                    document.getElementById('armada').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 80);
            }
        }

        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const answer = item.querySelector('.faq-answer');
            const isActive = item.classList.contains('active');

            // Close all
            document.querySelectorAll('.faq-item').forEach(i => {
                i.classList.remove('active');
                i.querySelector('.faq-answer').classList.remove('open');
            });

            // Open clicked if was not active
            if (!isActive) {
                item.classList.add('active');
                answer.classList.add('open');
            }
        }

        /* ===== NAVBAR SCROLL TO ===== */
        function navScrollTo(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const navH = document.getElementById('mainNavbar').offsetHeight;
            const top = el.getBoundingClientRect().top + window.scrollY - navH - 12;
            window.scrollTo({ top, behavior: 'smooth' });
        }

        function mobileNavGo(id) {
            closeMobileNav();
            setTimeout(() => navScrollTo(id), 280);
        }

        function toggleMobileNav() {
            const drawer = document.getElementById('navDrawer');
            const btn    = document.getElementById('navToggle');
            drawer.classList.toggle('open');
            btn.classList.toggle('open');
        }

        function closeMobileNav() {
            document.getElementById('navDrawer').classList.remove('open');
            document.getElementById('navToggle').classList.remove('open');
        }

        /* ===== ACTIVE LINK HIGHLIGHT ON SCROLL ===== */
        const sections = ['beranda','tentang','armada','testimoni','faq','kontak'];
        const navItems = document.querySelectorAll('.nav-link-item');

        function onScroll() {
            const navH = document.getElementById('mainNavbar').offsetHeight + 20;
            let current = 'beranda';

            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top - navH <= 0) {
                    current = id;
                }
            });

            navItems.forEach(a => {
                const href = a.getAttribute('href').replace('#','');
                a.classList.toggle('active', href === current);
            });
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        // ══════════════════════════════════════════════
        //  AJAX FILTER ARMADA — tanpa reload halaman
        // ══════════════════════════════════════════════

        function loadArmada(brand) {
            const grid         = document.getElementById('carGrid');
            const title        = document.getElementById('armadaTitle');
            const viewMoreWrap = document.getElementById('viewMoreWrap');

            grid.innerHTML = `
                <div class="col-12 text-center" style="padding:3rem 0;">
                    <div style="display:inline-flex;align-items:center;gap:12px;
                                color:var(--text-muted);font-size:0.88rem;">
                        <i class="fas fa-spinner fa-spin"
                           style="color:var(--gold);font-size:1.2rem;"></i>
                        Memuat armada…
                    </div>
                </div>`;
            viewMoreWrap.style.display = 'none';
            title.textContent = brand ? 'Armada ' + brand : 'Semua Armada Tersedia';

            const url = 'get_cars.php' + (brand ? '?brand=' + encodeURIComponent(brand) : '');

            fetch(url)
                .then(function(res) {
                    if (!res.ok) throw new Error('Network error');
                    return res.text();
                })
                .then(function(html) {
                    grid.innerHTML = html;
                    var hiddenItems = grid.querySelectorAll('.car-hidden').length;
                    if (hiddenItems > 0) {
                        document.getElementById('viewMoreCount').textContent = hiddenItems + ' mobil lainnya';
                        document.getElementById('btnViewMoreText').textContent = 'Lihat Semua Armada';
                        var btn = document.getElementById('btnViewMore');
                        btn.classList.remove('expanded');
                        document.getElementById('btnChevron').style.transform = '';
                        viewMoreWrap.style.display = 'block';
                        viewMoreWrap.classList.remove('expanded');
                    } else {
                        viewMoreWrap.style.display = 'none';
                    }
                })
                .catch(function() {
                    grid.innerHTML = '<div class="col-12"><div class="empty-state">'
                        + '<div class="empty-icon"><i class="fas fa-exclamation-triangle"></i></div>'
                        + '<h3 class="empty-title">Gagal memuat armada</h3>'
                        + '<p class="empty-sub">Periksa koneksi Anda dan coba lagi</p>'
                        + '</div></div>';
                });
        }

        function filterArmada(event) {
            event.preventDefault();
            var brand = document.getElementById('brandSelect').value;
            loadArmada(brand);
        }

        function resetFilter() {
            document.getElementById('brandSelect').value = '';
            loadArmada('');
        }

        // Load armada pertama kali
        loadArmada('');

    </script>

            <?php if (!isset($_SESSION['client_id'])): ?>
<!-- ===== MODAL LOGIN / REGISTER ===== -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="background:#161616;border:1px solid #252525;border-radius:20px;">
            <div class="modal-header" style="border-bottom:1px solid #252525;padding:1.5rem 1.8rem 1rem;">
                <div>
                    <h5 style="font-family:'Playfair Display',serif;color:#fff;margin:0;font-size:1.3rem;">
                        Masuk / Daftar
                    </h5>
                    <p style="color:#888;font-size:0.8rem;margin:4px 0 0;">
                        Akses akun Trinity Car Rental kamu
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Tab Nav -->
            <div style="display:flex;gap:0;padding:1rem 1.8rem 0;">
                <button id="tabLoginBtn" onclick="switchTab('login')"
                        style="flex:1;background:none;border:none;border-bottom:2px solid var(--gold);
                               color:#fff;font-family:'DM Sans',sans-serif;font-weight:700;
                               font-size:0.9rem;padding:0.5rem;cursor:pointer;">
                    Masuk
                </button>
                <button id="tabRegisterBtn" onclick="switchTab('register')"
                        style="flex:1;background:none;border:none;border-bottom:2px solid #252525;
                               color:#888;font-family:'DM Sans',sans-serif;font-size:0.9rem;
                               padding:0.5rem;cursor:pointer;">
                    Daftar
                </button>
            </div>

            <div class="modal-body" style="padding:1.5rem 1.8rem 1.8rem;">

                <!-- Alert -->
                <div id="alertLogin" style="display:none;padding:0.75rem 1rem;border-radius:10px;
                     font-size:0.84rem;margin-bottom:1rem;"></div>

                <!-- FORM LOGIN -->
                <div id="formLoginWrap">
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:0.78rem;color:#888;letter-spacing:1px;
                                      text-transform:uppercase;display:block;margin-bottom:6px;">Email</label>
                        <input type="email" id="loginEmail" placeholder="email@kamu.com"
                               style="width:100%;background:#0d0d0d;border:1px solid #252525;
                                      border-radius:10px;padding:0.75rem 1rem;color:#fff;
                                      font-family:'DM Sans',sans-serif;font-size:0.9rem;outline:none;">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <label style="font-size:0.78rem;color:#888;letter-spacing:1px;
                                      text-transform:uppercase;display:block;margin-bottom:6px;">Password</label>
                        <input type="password" id="loginPassword" placeholder="••••••••"
                               style="width:100%;background:#0d0d0d;border:1px solid #252525;
                                      border-radius:10px;padding:0.75rem 1rem;color:#fff;
                                      font-family:'DM Sans',sans-serif;font-size:0.9rem;outline:none;">
                    </div>
                    <button onclick="doLogin()"
                            style="width:100%;background:linear-gradient(135deg,#C9A84C,#E8C96A);
                                   color:#0d0d0d;border:none;border-radius:12px;padding:0.85rem;
                                   font-family:'DM Sans',sans-serif;font-weight:700;font-size:0.95rem;
                                   cursor:pointer;transition:all 0.3s;">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </div>

                <!-- FORM REGISTER -->
                <div id="formRegisterWrap" style="display:none;">
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:0.78rem;color:#888;letter-spacing:1px;
                                      text-transform:uppercase;display:block;margin-bottom:6px;">Nama Lengkap</label>
                        <input type="text" id="regNama" placeholder="John Doe"
                               style="width:100%;background:#0d0d0d;border:1px solid #252525;
                                      border-radius:10px;padding:0.75rem 1rem;color:#fff;
                                      font-family:'DM Sans',sans-serif;font-size:0.9rem;outline:none;">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:0.78rem;color:#888;letter-spacing:1px;
                                      text-transform:uppercase;display:block;margin-bottom:6px;">Email</label>
                        <input type="email" id="regEmail" placeholder="email@kamu.com"
                               style="width:100%;background:#0d0d0d;border:1px solid #252525;
                                      border-radius:10px;padding:0.75rem 1rem;color:#fff;
                                      font-family:'DM Sans',sans-serif;font-size:0.9rem;outline:none;">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:0.78rem;color:#888;letter-spacing:1px;
                                      text-transform:uppercase;display:block;margin-bottom:6px;">No. HP</label>
                        <input type="text" id="regHp" placeholder="08xxxxxxxxxx"
                               style="width:100%;background:#0d0d0d;border:1px solid #252525;
                                      border-radius:10px;padding:0.75rem 1rem;color:#fff;
                                      font-family:'DM Sans',sans-serif;font-size:0.9rem;outline:none;">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <label style="font-size:0.78rem;color:#888;letter-spacing:1px;
                                      text-transform:uppercase;display:block;margin-bottom:6px;">Password</label>
                        <input type="password" id="regPassword" placeholder="Min. 6 karakter"
                               style="width:100%;background:#0d0d0d;border:1px solid #252525;
                                      border-radius:10px;padding:0.75rem 1rem;color:#fff;
                                      font-family:'DM Sans',sans-serif;font-size:0.9rem;outline:none;">
                    </div>
                    <button onclick="doRegister()"
                            style="width:100%;background:linear-gradient(135deg,#C9A84C,#E8C96A);
                                   color:#0d0d0d;border:none;border-radius:12px;padding:0.85rem;
                                   font-family:'DM Sans',sans-serif;font-weight:700;font-size:0.95rem;
                                   cursor:pointer;transition:all 0.3s;">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const isLogin = tab === 'login';
    document.getElementById('formLoginWrap').style.display    = isLogin ? 'block' : 'none';
    document.getElementById('formRegisterWrap').style.display = isLogin ? 'none'  : 'block';
    document.getElementById('tabLoginBtn').style.borderBottomColor    = isLogin ? 'var(--gold)' : '#252525';
    document.getElementById('tabLoginBtn').style.color                = isLogin ? '#fff'        : '#888';
    document.getElementById('tabRegisterBtn').style.borderBottomColor = isLogin ? '#252525'     : 'var(--gold)';
    document.getElementById('tabRegisterBtn').style.color             = isLogin ? '#888'        : '#fff';
    showAlert('', '');
}

function showAlert(msg, type) {
    const el = document.getElementById('alertLogin');
    if (!msg) { el.style.display = 'none'; return; }
    el.style.display     = 'block';
    el.style.background  = type === 'success' ? 'rgba(40,167,69,0.15)' : 'rgba(220,53,69,0.15)';
    el.style.border      = type === 'success' ? '1px solid rgba(40,167,69,0.4)' : '1px solid rgba(220,53,69,0.4)';
    el.style.color       = type === 'success' ? '#6fcf97' : '#ff6b6b';
    el.textContent       = msg;
}

function doLogin() {
    const email    = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    if (!email || !password) { showAlert('Email dan password wajib diisi.', 'error'); return; }

    fetch('login_client.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=login&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(r => r.json())
    .then(data => {
        showAlert(data.message, data.status);
        if (data.status === 'success') {
            setTimeout(() => location.reload(), 1200);
        }
    })
    .catch(() => showAlert('Terjadi kesalahan, coba lagi.', 'error'));
}

function doRegister() {
    const nama     = document.getElementById('regNama').value.trim();
    const email    = document.getElementById('regEmail').value.trim();
    const no_hp    = document.getElementById('regHp').value.trim();
    const password = document.getElementById('regPassword').value;
    if (!nama || !email || !password) { showAlert('Nama, email, dan password wajib diisi.', 'error'); return; }

    fetch('login_client.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=register&nama=${encodeURIComponent(nama)}&email=${encodeURIComponent(email)}&no_hp=${encodeURIComponent(no_hp)}&password=${encodeURIComponent(password)}`
    })
    .then(r => r.json())
    .then(data => {
        showAlert(data.message, data.status);
        if (data.status === 'success') {
            setTimeout(() => location.reload(), 1200);
        }
    })
    .catch(() => showAlert('Terjadi kesalahan, coba lagi.', 'error'));
}
</script>
<?php endif; ?>
</body>
</html>