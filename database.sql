-- Database Medical Inventory System - Muhammad Zaky Alfiansyah
CREATE DATABASE IF NOT EXISTS db_alat_elektromedis
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_alat_elektromedis;

CREATE TABLE IF NOT EXISTS alat_elektromedis (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nama_alat VARCHAR(100) NOT NULL,
  tahun YEAR(4) NOT NULL,
  merek VARCHAR(100) NOT NULL,
  lokasi VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO alat_elektromedis (nama_alat,tahun,merek,lokasi) VALUES
('Patient Monitor',2024,'Mindray','Ruang ICU'),
('Electrocardiograph (ECG)',2023,'GE Healthcare','Ruang Jantung'),
('Infusion Pump',2022,'B. Braun','Ruang Rawat Inap'),
('Defibrillator',2024,'ZOLL','Ruang IGD'),
('Suction Pump',2023,'Yuwell','Ruang Operasi');


-- Tabel akun untuk fitur Login dan Register
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_lengkap VARCHAR(120) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
