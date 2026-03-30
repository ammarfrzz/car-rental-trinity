-- DATABASE
CREATE DATABASE IF NOT EXISTS car_rental_dpk;
USE car_rental_dpk;


-- TABEL ADMIN
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);

INSERT INTO admin (username, password)
VALUES ('admin', '12345');

-- TABEL MOBIL
CREATE TABLE mobil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mobil VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    harga_per_hari INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    foto VARCHAR(255) NOT NULL
);


-- TABEL BOOKING
CREATE TABLE booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_client VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobil_id INT NOT NULL,
    tanggal_sewa DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,
    jumlah_hari INT NOT NULL,
    total_harga INT NOT NULL,
    diskon INT NOT NULL,
    grand_total INT NOT NULL,
    status_booking VARCHAR(50) NOT NULL,
    status_pembayaran VARCHAR(50) NOT NULL,
    bukti_transfer VARCHAR(255)
);

-- TABEL CLIENT
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);