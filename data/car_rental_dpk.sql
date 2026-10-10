-- ============================================================
--  car_rental_dpk.sql
--  Database lengkap untuk aplikasi Car Rental DPK
--  Import via phpMyAdmin atau: mysql -u root < car_rental_dpk.sql
-- ============================================================

-- Buat & pilih database
CREATE DATABASE IF NOT EXISTS car_rental_dpk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_rental_dpk;

-- -----------------------------------------
--  TABEL ADMIN
-- -----------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin (username, password) VALUES ('admin', '12345');

-- -----------------------------------------
--  TABEL MOBIL
-- -----------------------------------------
CREATE TABLE IF NOT EXISTS mobil (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nama_mobil     VARCHAR(100) NOT NULL,
    brand          VARCHAR(100) NOT NULL,
    harga_per_hari INT          NOT NULL,
    status         VARCHAR(50)  NOT NULL,
    foto           VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO mobil (nama_mobil, brand, harga_per_hari, status, foto) VALUES
('BMW M4 CSL',                'BMW',          8500000,  'tersedia', 'm4csl.webp'),
('BMW M5',                    'BMW',          9000000,  'tersedia', 'm5.webp'),
('BMW M8 Competition',        'BMW',         11000000,  'tersedia', 'm8competition.webp'),
('Ferrari LaFerrari',         'Ferrari',     25000000,  'tersedia', 'LaFerrari.jpg'),
('Ferrari F8 Tributo',        'Ferrari',     18000000,  'tersedia', 'ferrarif8tributo.jpg'),
('Ferrari SF90 Stradale',     'Ferrari',     22000000,  'tersedia', 'ferrarisf90.jpeg'),
('Lamborghini Huracan',       'Lamborghini', 20000000,  'tersedia', 'huracan.webp'),
('Lamborghini Aventador SVJ', 'Lamborghini', 28000000,  'tersedia', 'aventadorsvj.webp'),
('Lamborghini Veneno',        'Lamborghini', 45000000,  'tersedia', 'veneno.jpeg'),
('Lamborghini Revuelto',      'Lamborghini', 35000000,  'tersedia', 'revuelto.jpg'),
('Lamborghini Centenario',    'Lamborghini', 50000000,  'tersedia', 'LamborghiniCentenario.webp'),
('McLaren Senna',             'McLaren',     30000000,  'tersedia', 'senna.jpeg'),
('McLaren P1',                'McLaren',     32000000,  'tersedia', 'McLaren_P1.jpg'),
('Porsche 911 GT2 RS',        'Porsche',     15000000,  'tersedia', '911gt2rs.jpg'),
('Porsche 911 Turbo S',       'Porsche',     14000000,  'tersedia', '911turboS.jpg'),
('Porsche 918 Spyder',        'Porsche',     28000000,  'tersedia', '918.jpg'),
('McLaren 720S',              'McLaren',     12000000,  'tersedia', '720s.jpg'),
('Bugatti Chiron',            'Bugatti',     75000000,  'tersedia', 'Bugatti-Chiron.webp'),
('Bugatti Veyron',            'Bugatti',     65000000,  'tersedia', 'Bugatti-Veyron.jpg'),
('Bugatti Divo',              'Bugatti',     90000000,  'tersedia', 'Bugatti_Divo.jpg'),
('Bugatti La Voiture Noire',  'Bugatti',    120000000,  'tersedia', 'Bugatti_La_Voiture_Noire_-_001.jpg'),
('Koenigsegg Agera RS',       'Koenigsegg',  85000000,  'tersedia', 'Koenigsegg-Agera-RS.webp'),
('Koenigsegg Jesko',          'Koenigsegg', 100000000,  'tersedia', 'Koenigsegg-Jesko-Odin-delivery-3.webp'),
('Koenigsegg One:1',          'Koenigsegg', 110000000,  'tersedia', 'koenigsegg-one1.jpg'),
('Pagani Huayra',             'Pagani',      55000000,  'tersedia', 'huayra.jpg'),
('Pagani Zonda',              'Pagani',      48000000,  'tersedia', 'Pagani-Zonda.webp');

-- -----------------------------------------
--  TABEL CLIENT
-- -----------------------------------------
CREATE TABLE IF NOT EXISTS client (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    no_hp      VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------
--  TABEL BOOKING
-- -----------------------------------------
CREATE TABLE IF NOT EXISTS booking (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    nama_client       VARCHAR(100) NOT NULL,
    no_hp             VARCHAR(20)  NOT NULL,
    email             VARCHAR(100) NOT NULL,
    mobil_id          INT          NOT NULL,
    tanggal_sewa      DATE         NOT NULL,
    tanggal_kembali   DATE         NOT NULL,
    jumlah_hari       INT          NOT NULL,
    total_harga       INT          NOT NULL,
    diskon            INT          NOT NULL DEFAULT 0,
    grand_total       INT          NOT NULL,
    status_booking    VARCHAR(50)  NOT NULL DEFAULT 'Pending',
    status_pembayaran VARCHAR(50)  NOT NULL DEFAULT 'Belum Bayar',
    bukti_transfer    VARCHAR(255),
    CONSTRAINT fk_booking_mobil FOREIGN KEY (mobil_id) REFERENCES mobil(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
--  SELESAI - Database car_rental_dpk berhasil dibuat
-- ============================================================
