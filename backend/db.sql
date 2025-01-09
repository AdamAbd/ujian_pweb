CREATE DATABASE IF NOT EXISTS db_notes;

USE db_notes;

CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Kolom ID pengguna, auto increment
    username VARCHAR(255) NOT NULL,                  -- Username pengguna, tidak perlu unik, tetapi tidak boleh kosong
    email VARCHAR(255) NOT NULL UNIQUE,              -- Email pengguna, harus unik dan tidak boleh kosong
    password VARCHAR(255) NOT NULL,                  -- Password pengguna, akan disimpan dalam format hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Waktu pembuatan data pengguna
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Waktu terakhir data diperbarui
);
