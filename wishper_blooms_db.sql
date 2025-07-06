  -- Buat database
  CREATE DATABASE IF NOT EXISTS whisper_blooms;
  USE whisper_blooms;

  -- Tabel user
  CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    foto_profil VARCHAR(255) DEFAULT 'default.jpg'
  );

  -- Tabel alamat (relasi ke user)
  CREATE TABLE alamat (
    id_alamat INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    jalan VARCHAR(255),
    rt VARCHAR(10),
    rw VARCHAR(10),
    desa VARCHAR(100),
    kecamatan VARCHAR(100),
    kota VARCHAR(100),
    provinsi VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
  );

  -- Tabel produk
  CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga INT NOT NULL,
    harga_diskon INT,
    gambar VARCHAR(255),
    rating INT DEFAULT 0,
    stok INT NOT NULL DEFAULT 50

  );

  -- Data awal produk
  INSERT INTO produk (nama_produk, deskripsi, harga, harga_diskon, gambar, rating) VALUES
  ('Paket Bunga 1', 'Kartu ucapan handmade bunga biru dengan pesan “Happy Graduation”.', 30000, 55000, 'asset/menu/2.png', 4),
  ('Paket Bunga 2', 'Kartu ucapan handmade dengan nuansa pink romantis.', 30000, 55000, 'asset/menu/3.png', 3),
  ('Paket Bunga 3', 'Kartu ucapan handmade dengan hiasan bunga lavender.', 30000, 55000, 'asset/menu/4.png', 4),
  ('Paket Bunga 4', 'Kartu ucapan handmade dengan detail bunga penuh warna.', 30000, 55000, 'asset/menu/5.png', 5),
  ('Paket Bunga Custom', 'Kartu ucapan custom sesuai permintaan tema dan warna.', 30000, 55000, 'asset/header.jpg', 3);

  -- Tabel desain custom
  CREATE TABLE desain_custom (
  id_desain INT AUTO_INCREMENT PRIMARY KEY,
  nama_desain VARCHAR(100),
  gambar_desain VARCHAR(255)
);

INSERT into desain_custom (nama_desain, gambar_desain) VALUES
('Desain Custom 1', 'asset/custom/1.png'),
('Desain Custom 2', 'asset/custom/2.png'),
('Desain Custom 3', 'asset/custom/3.png'),
('Desain Custom 4', 'asset/custom/4.png'),
('Desain Custom 5', 'asset/custom/5.png');

-- Tabel warna custom
CREATE TABLE warna_custom (
  id_warna INT AUTO_INCREMENT PRIMARY KEY,
  nama_warna VARCHAR(100),
  gambar_warna VARCHAR(255)
);

INSERT into warna_custom (nama_warna, gambar_warna) VALUES
('Biru', 'asset/warna/biru.png'),
('Merah', 'asset/warna/merah.png'),
('Pink', 'asset/warna/pink.png'),
('Oren', 'asset/warna/oren.png'),
('Kuning', 'asset/warna/kuning.png'),
('Ungu', 'asset/warna/ungu.png'),
('Hijau', 'asset/warna/hijau.png');

-- Tabel tema custom
CREATE TABLE tema_custom (
  id_tema INT AUTO_INCREMENT PRIMARY KEY,
  nama_tema VARCHAR(100),
  gambar_tema VARCHAR(255)
);  

INSERT into tema_custom (nama_tema, gambar_tema) VALUES
('Wisuda', 'asset/tema/romantis.png'),
('Ulang Tahun', 'asset/tema/minimalis.png'),
('Wedding', 'asset/tema/vintage.png'),
('Akademik', 'asset/tema/tropical.png');

  -- Tabel keranjang
  CREATE TABLE keranjang (
    id_keranjang INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_produk INT,
    jumlah INT DEFAULT 1,
    tanggal_ditambahkan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
  );

CREATE TABLE detail_keranjang (
  id_detail_keranjang INT AUTO_INCREMENT PRIMARY KEY,
  id_keranjang INT,
  nama_penerima VARCHAR(100),
  ucapan TEXT,
  keterangan TEXT,
  id_desain INT,
  id_warna INT,
  id_tema INT,
  FOREIGN KEY (id_keranjang) REFERENCES keranjang(id_keranjang) ON DELETE CASCADE,
  FOREIGN KEY (id_desain) REFERENCES desain_custom(id_desain),
  FOREIGN KEY (id_warna) REFERENCES warna_custom(id_warna),
  FOREIGN KEY (id_tema) REFERENCES tema_custom(id_tema)
);

  -- Tabel pesanan (checkout)
  CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    tanggal_pesanan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'pending',
    total_harga INT,
    alamat_kirim TEXT,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
  );

  -- Tabel detail pesanan
CREATE TABLE pesanan_detail (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT,
  id_produk INT,
  jumlah INT,
  harga_satuan INT,
  total_harga INT,
  id_desain INT,
  id_warna INT,
  id_tema INT,
  nama_penerima VARCHAR(100),
  ucapan TEXT,
  keterangan TEXT,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
  FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
  FOREIGN KEY (id_desain) REFERENCES desain_custom(id_desain),
  FOREIGN KEY (id_warna) REFERENCES warna_custom(id_warna),
  FOREIGN KEY (id_tema) REFERENCES tema_custom(id_tema)
);




