-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Jun 2019 pada 18.12
-- Versi server: 10.1.34-MariaDB
-- Versi PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyek_akhir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `no_akun` char(3) NOT NULL,
  `nama_akun` varchar(50) DEFAULT NULL,
  `header_akun` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`no_akun`, `nama_akun`, `header_akun`) VALUES
('111', 'Kas', '1'),
('112', 'Persediaan Bahan', '1'),
('113', 'Persediaan Produk Jadi', '1'),
('114', 'Persediaan Produk Dalam Proses', '1'),
('411', 'Penjualan', '4'),
('511', 'Harga Pokok Penjualan', '5'),
('512', 'Beban Upah', '5'),
('513', 'Beban Gaji', '5'),
('514', 'Beban Listrik', '5'),
('515', 'Beban Air', '5'),
('520', 'Beban Listrik (Pabrik)', '5'),
('521', 'Beban Air (Pabrik)', '5'),
('522', 'BDP Biaya Bahan Baku', '5'),
('524', 'BDP Biaya Tenaga Kerja Langsung', '5'),
('525', 'BDP Biaya Overhead Pabrik', '5'),
('800', 'lain', '8');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` varchar(10) NOT NULL,
  `nama_bahan` varchar(30) DEFAULT NULL,
  `stok_digudang` float DEFAULT NULL,
  `stok_diproduksi` float NOT NULL,
  `satuan` char(10) DEFAULT NULL,
  `jenis_bahan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `stok_digudang`, `stok_diproduksi`, `satuan`, `jenis_bahan`) VALUES
('BHP-000001', 'Kancing', 0, 0, 'Pcs', 'Bahan Penolong'),
('BHP-000002', 'Resleting', 0, 0, 'Pcs', 'Bahan Penolong'),
('BHU-000001', 'Kain Lexus', 0, 0, 'Meter', 'Bahan Utama'),
('BHU-000002', 'Kain Batik', 0, 0, 'Meter', 'Bahan Utama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `beban`
--

CREATE TABLE `beban` (
  `id_beban` varchar(10) NOT NULL,
  `nama_beban` varchar(50) DEFAULT NULL,
  `no_akun` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `beban`
--

INSERT INTO `beban` (`id_beban`, `nama_beban`, `no_akun`) VALUES
('BBN-000001', 'Beban Listrik (Pabrik)', '520'),
('BBN-000002', 'Beban Air (Pabrik)', '521'),
('BBN-000004', 'Beban Listrik', '514'),
('BBN-000005', 'Beban Air', '515');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bom`
--

CREATE TABLE `bom` (
  `id_produk` varchar(10) DEFAULT NULL,
  `id_bahan` varchar(10) DEFAULT NULL,
  `jumlah` float DEFAULT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `bom`
--

INSERT INTO `bom` (`id_produk`, `id_bahan`, `jumlah`, `status`) VALUES
('PRD-000001', 'BHU-000001', 2, 'Dikonfirmasi'),
('PRD-000001', 'BHP-000001', 10, 'Dikonfirmasi'),
('PRD-000001', 'BHP-000002', 1, 'Dikonfirmasi'),
('PRD-000002', 'BHU-000002', 2, 'Dikonfirmasi'),
('PRD-000002', 'BHP-000001', 5, 'Dikonfirmasi'),
('PRD-000002', 'BHP-000002', 1, 'Dikonfirmasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `btkl`
--

CREATE TABLE `btkl` (
  `id_btkl` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `total` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `btkl`
--

INSERT INTO `btkl` (`id_btkl`, `tanggal`, `total`, `status`) VALUES
('BTK-000001', '2019-05-04', 530000, 'Belum Dibayar'),
('BTK-000002', '2019-05-04', 265000, 'Belum Dibayar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_beban`
--

CREATE TABLE `detail_beban` (
  `id_beban` varchar(10) DEFAULT NULL,
  `id_produk` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_beban`
--

INSERT INTO `detail_beban` (`id_beban`, `id_produk`, `status`) VALUES
('BBN-000001', 'PRD-000001', 'Dikonfirmasi'),
('BBN-000002', 'PRD-000001', 'Dikonfirmasi'),
('BBN-000001', 'PRD-000002', 'Dikonfirmasi'),
('BBN-000002', 'PRD-000002', 'Dikonfirmasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_btkl`
--

CREATE TABLE `detail_btkl` (
  `id_btkl` varchar(10) DEFAULT NULL,
  `no_pesanan` varchar(10) DEFAULT NULL,
  `id_produk` varchar(10) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `id_pegawai` varchar(20) DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_btkl`
--

INSERT INTO `detail_btkl` (`id_btkl`, `no_pesanan`, `id_produk`, `jumlah`, `subtotal`, `id_pegawai`, `ip_address`) VALUES
('BTK-000001', 'PSN-000001', 'PRD-000001', 10, 150000, 'PGK-000001', '127.0.0.1'),
('BTK-000001', 'PSN-000001', 'PRD-000001', 10, 130000, 'PGK-000002', '127.0.0.1'),
('BTK-000001', 'PSN-000001', 'PRD-000001', 10, 100000, 'PGK-000003', '127.0.0.1'),
('BTK-000001', 'PSN-000001', 'PRD-000001', 10, 150000, 'PGK-000004', '127.0.0.1'),
('BTK-000002', 'PSN-000001', 'PRD-000002', 5, 75000, 'PGK-000001', '127.0.0.1'),
('BTK-000002', 'PSN-000001', 'PRD-000002', 5, 65000, 'PGK-000002', '127.0.0.1'),
('BTK-000002', 'PSN-000001', 'PRD-000002', 5, 50000, 'PGK-000003', '127.0.0.1'),
('BTK-000002', 'PSN-000001', 'PRD-000002', 5, 75000, 'PGK-000004', '127.0.0.1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pembayaran_beban`
--

CREATE TABLE `detail_pembayaran_beban` (
  `id_transaksi` varchar(10) DEFAULT NULL,
  `id_beban` varchar(10) DEFAULT NULL,
  `jumlah_produksi` int(11) NOT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_pembayaran_beban`
--

INSERT INTO `detail_pembayaran_beban` (`id_transaksi`, `id_beban`, `jumlah_produksi`, `subtotal`) VALUES
('PBN-000001', 'BBN-000001', 50, 300000),
('PBN-000001', 'BBN-000002', 50, 200000),
('PBN-000002', 'BBN-000001', 40, 300000),
('PBN-000002', 'BBN-000002', 40, 100000),
('PBN-000003', 'BBN-000001', 15, 700000),
('PBN-000003', 'BBN-000002', 15, 350000),
('PBN-000003', 'BBN-000004', 0, 250000),
('PBN-000003', 'BBN-000005', 0, 150000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_transaksi` varchar(10) DEFAULT NULL,
  `id_bahan` varchar(10) DEFAULT NULL,
  `no_pesanan` varchar(10) NOT NULL,
  `jumlah` float DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `status` varchar(30) NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id_transaksi`, `id_bahan`, `no_pesanan`, `jumlah`, `harga`, `subtotal`, `status`, `ip_address`) VALUES
('PBL-000001', 'BHP-000001', 'PSN-000001', 125, 1000, 125000, 'Konfirmasi', '127.0.0.1'),
('PBL-000001', 'BHP-000002', 'PSN-000001', 15, 2000, 30000, 'Konfirmasi', '127.0.0.1'),
('PBL-000001', 'BHU-000001', 'PSN-000001', 20, 65000, 1300000, 'Konfirmasi', '127.0.0.1'),
('PBL-000001', 'BHU-000002', 'PSN-000001', 10, 50000, 500000, 'Konfirmasi', '127.0.0.1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penggajian`
--

CREATE TABLE `detail_penggajian` (
  `id_gaji` varchar(10) DEFAULT NULL,
  `id_pegawai` varchar(10) DEFAULT NULL,
  `subtotal_perhari` int(11) DEFAULT NULL,
  `subtotal_tunjangan` int(11) DEFAULT NULL,
  `bonus` int(11) DEFAULT NULL,
  `lembur` int(11) DEFAULT NULL,
  `subtotal_gaji` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pengupahan`
--

CREATE TABLE `detail_pengupahan` (
  `id_upah` varchar(10) DEFAULT NULL,
  `id_pegawai` varchar(10) DEFAULT NULL,
  `subtotal_perproduk` int(11) DEFAULT NULL,
  `subtotal_tunjangan` int(11) DEFAULT NULL,
  `subtotal_upah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_transaksi` varchar(10) DEFAULT NULL,
  `no_pesanan` varchar(10) DEFAULT NULL,
  `id_produk` varchar(10) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `status` varchar(30) NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_transaksi`, `no_pesanan`, `id_produk`, `harga_jual`, `jumlah`, `subtotal`, `status`, `ip_address`) VALUES
('PJL-000001', 'PSN-000001', 'PRD-000001', 327750, 10, 3277500, 'Konfirmasi', '127.0.0.1'),
('PJL-000001', 'PSN-000001', 'PRD-000002', 306000, 5, 1530000, 'Konfirmasi', '127.0.0.1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penyerahan_bahan`
--

CREATE TABLE `detail_penyerahan_bahan` (
  `id_transaksi` varchar(10) DEFAULT NULL,
  `no_pesanan` varchar(10) DEFAULT NULL,
  `id_bahan` varchar(10) DEFAULT NULL,
  `id_produk` varchar(10) NOT NULL,
  `jumlah` float DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_penyerahan_bahan`
--

INSERT INTO `detail_penyerahan_bahan` (`id_transaksi`, `no_pesanan`, `id_bahan`, `id_produk`, `jumlah`, `subtotal`, `status`, `ip_address`) VALUES
('PYB-000001', 'PSN-000001', 'BHP-000001', 'PRD-000001', 100, 100000, 'Dikonfirmasi', '127.0.0.1'),
('PYB-000001', 'PSN-000001', 'BHP-000002', 'PRD-000001', 10, 20000, 'Dikonfirmasi', '127.0.0.1'),
('PYB-000001', 'PSN-000001', 'BHU-000001', 'PRD-000001', 20, 1300000, 'Dikonfirmasi', '127.0.0.1'),
('PYB-000002', 'PSN-000001', 'BHP-000001', 'PRD-000002', 25, 25000, 'Dikonfirmasi', '127.0.0.1'),
('PYB-000002', 'PSN-000001', 'BHP-000002', 'PRD-000002', 5, 10000, 'Dikonfirmasi', '127.0.0.1'),
('PYB-000002', 'PSN-000001', 'BHU-000002', 'PRD-000002', 10, 500000, 'Dikonfirmasi', '127.0.0.1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `no_pesanan` varchar(10) DEFAULT NULL,
  `id_produk` varchar(10) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal_pesan` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`no_pesanan`, `id_produk`, `jumlah`, `tanggal_pesan`, `tanggal_selesai`, `status`, `subtotal`, `ip_address`) VALUES
('PSN-000001', 'PRD-000001', 10, '2019-05-04', '2019-05-05', 'Terjual', 0, '127.0.0.1'),
('PSN-000001', 'PRD-000002', 5, '2019-05-04', '2019-05-05', 'Terjual', 0, '127.0.0.1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_tarif_posisi`
--

CREATE TABLE `detail_tarif_posisi` (
  `id_posisi` varchar(10) DEFAULT NULL,
  `id_pegawai` varchar(20) DEFAULT NULL,
  `tanggal_awal` date DEFAULT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_tarif_posisi`
--

INSERT INTO `detail_tarif_posisi` (`id_posisi`, `id_pegawai`, `tanggal_awal`, `tanggal_akhir`, `status`) VALUES
('PMK', '0009701117', '2019-01-24', '2030-12-15', 'Aktif'),
('PJH', 'PGK-000001', '2019-03-04', '2030-12-15', 'Aktif'),
('BRD', 'PGK-000002', '2019-03-04', '2030-12-15', 'Aktif'),
('FNH', 'PGK-000003', '2019-04-04', '2030-12-15', 'Aktif'),
('PMT', 'PGK-000004', '2019-04-22', '2030-12-15', 'Aktif'),
('SKU', '0009757896', '2019-04-22', '2030-12-15', 'Aktif'),
('KPP', '0009723211', '2019-04-22', '2030-12-15', 'Aktif'),
('STP', '0009724021', '2019-04-22', '2030-12-15', 'Aktif'),
('KPG', '0009722753', '2019-04-23', '2030-12-15', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal`
--

CREATE TABLE `jurnal` (
  `no_akun` char(3) DEFAULT NULL,
  `id_transaksi` varchar(10) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `posisi_db_cr` char(10) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jurnal`
--

INSERT INTO `jurnal` (`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES
('520', 'PBN-000001', '2019-02-27', 'Debet', 300000),
('111', 'PBN-000001', '2019-02-27', 'Kredit', 300000),
('521', 'PBN-000001', '2019-02-27', 'Debet', 200000),
('111', 'PBN-000001', '2019-02-27', 'Kredit', 200000),
('520', 'PBN-000002', '2019-03-27', 'Debet', 300000),
('111', 'PBN-000001', '2019-03-27', 'Kredit', 300000),
('521', 'PBN-000002', '2019-03-27', 'Debet', 100000),
('111', 'PBN-000002', '2019-03-27', 'Kredit', 100000),
('112', 'PBL-000001', '2019-05-05', 'Debet', 1955000),
('111', 'PBL-000001', '2019-05-05', 'Kredit', 1955000),
('522', 'PYB-000001', '2019-05-05', 'Debet', 1300000),
('112', 'PYB-000001', '2019-05-05', 'Kredit', 1300000),
('525', 'PYB-000001', '2019-05-05', 'Debet', 120000),
('112', 'PYB-000001', '2019-05-05', 'Kredit', 120000),
('522', 'PYB-000002', '2019-05-05', 'Debet', 500000),
('112', 'PYB-000002', '2019-05-05', 'Kredit', 500000),
('525', 'PYB-000002', '2019-05-05', 'Debet', 35000),
('112', 'PYB-000002', '2019-05-05', 'Kredit', 35000),
('113', 'PRS-000001', '2019-05-05', 'Debet', 2185000),
('522', 'PRS-000001', '2019-05-05', 'Kredit', 1300000),
('524', 'PRS-000001', '2019-05-05', 'Kredit', 530000),
('525', 'PRS-000001', '2019-05-05', 'Kredit', 355000),
('113', 'PRS-000002', '2019-05-05', 'Debet', 1020000),
('522', 'PRS-000002', '2019-05-05', 'Kredit', 500000),
('524', 'PRS-000002', '2019-05-05', 'Kredit', 265000),
('525', 'PRS-000002', '2019-05-05', 'Kredit', 255000),
('111', 'PJL-000001', '2019-05-05', 'Debet', 4807500),
('411', 'PJL-000001', '2019-05-05', 'Kredit', 4807500),
('511', 'PJL-000001', '2019-05-05', 'Debet', 3205000),
('113', 'PJL-000001', '2019-05-05', 'Kredit', 3205000),
('520', 'PBN-000003', '2019-05-05', 'Debet', 700000),
('111', 'PBN-000003', '2019-05-05', 'Kredit', 700000),
('521', 'PBN-000003', '2019-05-05', 'Debet', 350000),
('111', 'PBN-000003', '2019-05-05', 'Kredit', 350000),
('514', 'PBN-000003', '2019-05-05', 'Debet', 250000),
('111', 'PBN-000003', '2019-05-05', 'Kredit', 250000),
('515', 'PBN-000003', '2019-05-05', 'Debet', 150000),
('111', 'PBN-000003', '2019-05-05', 'Kredit', 150000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` bigint(20) NOT NULL,
  `notifikasi` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `waktu` time NOT NULL,
  `status` char(1) NOT NULL,
  `id_posisi` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES
(1, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:00:47', '0', 'KPG'),
(2, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:00:47', '1', 'KPP'),
(3, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:00:47', '0', 'SKU'),
(4, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:00:47', '0', 'STP'),
(5, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:10', '0', 'KPG'),
(6, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:10', '1', 'KPP'),
(7, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:10', '0', 'SKU'),
(8, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:10', '0', 'STP'),
(9, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:21:27', '0', 'KPG'),
(10, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:21:27', '1', 'KPP'),
(11, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:21:27', '0', 'SKU'),
(12, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:21:27', '0', 'STP'),
(13, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:27:49', '0', 'KPG'),
(14, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:27:49', '1', 'KPP'),
(15, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:27:49', '0', 'SKU'),
(16, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:27:49', '0', 'STP'),
(17, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '20:11:01', '0', 'KPG'),
(18, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '20:11:01', '1', 'KPP'),
(19, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '20:11:01', '0', 'SKU'),
(20, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '20:11:01', '0', 'STP'),
(21, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:11:36', '0', 'KPG'),
(22, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:11:36', '1', 'KPP'),
(23, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:11:36', '0', 'SKU'),
(24, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:11:36', '0', 'STP'),
(25, 'Kode Beban BBN-000004 Dimasukkan', 'bug_report', '13:49:17', '0', 'KPG'),
(26, 'Kode Beban BBN-000004 Dimasukkan', 'bug_report', '13:49:17', '1', 'KPP'),
(27, 'Kode Beban BBN-000004 Dimasukkan', 'bug_report', '13:49:17', '0', 'SKU'),
(28, 'Kode Beban BBN-000004 Dimasukkan', 'bug_report', '13:49:17', '0', 'STP'),
(29, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '13:49:24', '0', 'KPG'),
(30, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '13:49:24', '1', 'KPP'),
(31, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '13:49:24', '0', 'SKU'),
(32, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '13:49:24', '0', 'STP'),
(33, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '09:58:06', '0', 'KPG'),
(34, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '09:58:06', '1', 'KPP'),
(35, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '09:58:06', '0', 'SKU'),
(36, 'Kode Beban BBN-000005 Dimasukkan', 'bug_report', '09:58:06', '0', 'STP'),
(37, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:09', '0', 'KPG'),
(38, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:09', '1', 'KPP'),
(39, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:09', '0', 'SKU'),
(40, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:09', '0', 'STP'),
(41, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:19', '0', 'KPG'),
(42, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:19', '1', 'KPP'),
(43, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:19', '0', 'SKU'),
(44, 'Kode Beban BBN-000005 Diubah', 'bug_report', '10:27:19', '0', 'STP'),
(45, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:39:47', '0', 'KPG'),
(46, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:39:47', '1', 'KPP'),
(47, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:39:47', '0', 'SKU'),
(48, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:39:47', '0', 'STP'),
(49, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:41:55', '0', 'KPG'),
(50, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:41:55', '1', 'KPP'),
(51, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:41:55', '0', 'SKU'),
(52, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:41:55', '0', 'STP'),
(53, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:43:43', '0', 'KPG'),
(54, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:43:43', '1', 'KPP'),
(55, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:43:43', '0', 'SKU'),
(56, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:43:43', '0', 'STP'),
(57, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:46:13', '0', 'KPG'),
(58, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:46:13', '1', 'KPP'),
(59, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:46:13', '0', 'SKU'),
(60, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:46:13', '0', 'STP'),
(61, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:50:49', '0', 'KPG'),
(62, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:50:49', '1', 'KPP'),
(63, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:50:49', '0', 'SKU'),
(64, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:50:49', '0', 'STP'),
(65, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:56:43', '0', 'KPG'),
(66, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:56:43', '1', 'KPP'),
(67, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:56:43', '0', 'SKU'),
(68, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:56:43', '0', 'STP'),
(69, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:59:04', '0', 'KPG'),
(70, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:59:04', '1', 'KPP'),
(71, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:59:04', '0', 'SKU'),
(72, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '10:59:04', '0', 'STP'),
(73, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '10:21:39', '0', 'KPG'),
(74, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '10:21:39', '1', 'KPP'),
(75, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '10:21:39', '0', 'SKU'),
(76, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '10:21:39', '0', 'STP'),
(77, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '13:12:08', '0', 'KPG'),
(78, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '13:12:08', '1', 'KPP'),
(79, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '13:12:08', '0', 'SKU'),
(80, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '13:12:08', '0', 'STP'),
(81, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '03:24:24', '0', 'KPG'),
(82, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '03:24:24', '1', 'KPP'),
(83, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '03:24:24', '0', 'SKU'),
(84, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '03:24:24', '0', 'STP'),
(85, 'Bill Of Material PRD-000001 Telah Dikonfirmasi', 'extension', '12:33:47', '0', 'KPG'),
(86, 'Bill Of Material PRD-000001 Telah Dikonfirmasi', 'extension', '12:33:47', '1', 'KPP'),
(87, 'Bill Of Material PRD-000001 Telah Dikonfirmasi', 'extension', '12:33:47', '0', 'SKU'),
(88, 'Bill Of Material PRD-000001 Telah Dikonfirmasi', 'extension', '12:33:47', '0', 'STP'),
(89, 'Bill Of Material PRD-000002 Telah Dikonfirmasi', 'extension', '12:34:16', '0', 'KPG'),
(90, 'Bill Of Material PRD-000002 Telah Dikonfirmasi', 'extension', '12:34:16', '1', 'KPP'),
(91, 'Bill Of Material PRD-000002 Telah Dikonfirmasi', 'extension', '12:34:16', '0', 'SKU'),
(92, 'Bill Of Material PRD-000002 Telah Dikonfirmasi', 'extension', '12:34:16', '0', 'STP'),
(93, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '12:35:42', '0', 'KPG'),
(94, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '12:35:42', '1', 'KPP'),
(95, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '12:35:42', '0', 'SKU'),
(96, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '12:35:42', '0', 'STP'),
(97, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '12:36:56', '0', 'KPG'),
(98, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '12:36:56', '1', 'KPP'),
(99, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '12:36:56', '0', 'SKU'),
(100, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '12:36:56', '0', 'STP'),
(101, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '12:37:31', '0', 'KPG'),
(102, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '12:37:31', '1', 'KPP'),
(103, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '12:37:31', '0', 'SKU'),
(104, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '12:37:31', '0', 'STP'),
(105, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:37:47', '0', 'KPG'),
(106, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:37:47', '1', 'KPP'),
(107, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:37:47', '0', 'SKU'),
(108, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:37:47', '0', 'STP'),
(109, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '13:52:39', '0', 'KPG'),
(110, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '13:52:39', '1', 'KPP'),
(111, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '13:52:39', '0', 'SKU'),
(112, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '13:52:39', '0', 'STP'),
(113, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '14:14:20', '0', 'KPG'),
(114, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '14:14:20', '1', 'KPP'),
(115, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '14:14:20', '0', 'SKU'),
(116, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '14:14:20', '0', 'STP'),
(117, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '14:15:24', '0', 'KPG'),
(118, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '14:15:24', '1', 'KPP'),
(119, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '14:15:24', '0', 'SKU'),
(120, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '14:15:24', '0', 'STP'),
(121, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '14:15:44', '0', 'KPG'),
(122, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '14:15:44', '1', 'KPP'),
(123, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '14:15:44', '0', 'SKU'),
(124, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '14:15:44', '0', 'STP'),
(125, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '14:16:19', '0', 'KPG'),
(126, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '14:16:19', '1', 'KPP'),
(127, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '14:16:19', '0', 'SKU'),
(128, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '14:16:19', '0', 'STP'),
(129, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '14:21:08', '0', 'KPG'),
(130, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '14:21:08', '1', 'KPP'),
(131, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '14:21:08', '0', 'SKU'),
(132, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '14:21:08', '0', 'STP'),
(133, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:41', '0', 'KPG'),
(134, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:41', '1', 'KPP'),
(135, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:41', '0', 'SKU'),
(136, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '15:15:41', '0', 'STP'),
(137, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:42:07', '0', 'KPG'),
(138, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:42:07', '1', 'KPP'),
(139, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:42:07', '0', 'SKU'),
(140, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '09:42:07', '0', 'STP'),
(141, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '14:22:16', '0', 'KPG'),
(142, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '14:22:16', '1', 'KPP'),
(143, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '14:22:16', '0', 'SKU'),
(144, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '14:22:16', '0', 'STP'),
(145, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '14:29:56', '0', 'KPG'),
(146, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '14:29:56', '1', 'KPP'),
(147, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '14:29:56', '0', 'SKU'),
(148, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '14:29:56', '0', 'STP'),
(149, 'Kode Penyerahan PYB-000003 Dimasukkan', 'local_shipping', '14:30:23', '0', 'KPG'),
(150, 'Kode Penyerahan PYB-000003 Dimasukkan', 'local_shipping', '14:30:23', '1', 'KPP'),
(151, 'Kode Penyerahan PYB-000003 Dimasukkan', 'local_shipping', '14:30:23', '0', 'SKU'),
(152, 'Kode Penyerahan PYB-000003 Dimasukkan', 'local_shipping', '14:30:23', '0', 'STP'),
(153, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '14:31:26', '0', 'KPG'),
(154, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '14:31:26', '1', 'KPP'),
(155, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '14:31:26', '0', 'SKU'),
(156, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '14:31:26', '0', 'STP'),
(157, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '14:37:36', '0', 'KPG'),
(158, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '14:37:36', '1', 'KPP'),
(159, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '14:37:36', '0', 'SKU'),
(160, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '14:37:36', '0', 'STP'),
(161, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '14:40:30', '0', 'KPG'),
(162, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '14:40:30', '1', 'KPP'),
(163, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '14:40:30', '0', 'SKU'),
(164, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '14:40:30', '0', 'STP'),
(165, 'Kode Pesanan PSN-000003 Dimasukkan', 'rate_review', '00:00:34', '0', 'KPG'),
(166, 'Kode Pesanan PSN-000003 Dimasukkan', 'rate_review', '00:00:34', '1', 'KPP'),
(167, 'Kode Pesanan PSN-000003 Dimasukkan', 'rate_review', '00:00:34', '0', 'SKU'),
(168, 'Kode Pesanan PSN-000003 Dimasukkan', 'rate_review', '00:00:34', '0', 'STP'),
(169, 'Pegawai Kontrak 0009757896 Diubah', 'supervisor_account', '01:31:28', '0', 'KPG'),
(170, 'Pegawai Kontrak 0009757896 Diubah', 'supervisor_account', '01:31:28', '1', 'KPP'),
(171, 'Pegawai Kontrak 0009757896 Diubah', 'supervisor_account', '01:31:28', '0', 'SKU'),
(172, 'Pegawai Kontrak 0009757896 Diubah', 'supervisor_account', '01:31:28', '0', 'STP'),
(173, 'Pegawai Kontrak PGK-000001 Diubah', 'supervisor_account', '01:32:58', '0', 'KPG'),
(174, 'Pegawai Kontrak PGK-000001 Diubah', 'supervisor_account', '01:32:58', '1', 'KPP'),
(175, 'Pegawai Kontrak PGK-000001 Diubah', 'supervisor_account', '01:32:58', '0', 'SKU'),
(176, 'Pegawai Kontrak PGK-000001 Diubah', 'supervisor_account', '01:32:58', '0', 'STP'),
(177, 'Pegawai Kontrak PGK-000002 Diubah', 'supervisor_account', '01:33:22', '0', 'KPG'),
(178, 'Pegawai Kontrak PGK-000002 Diubah', 'supervisor_account', '01:33:22', '1', 'KPP'),
(179, 'Pegawai Kontrak PGK-000002 Diubah', 'supervisor_account', '01:33:22', '0', 'SKU'),
(180, 'Pegawai Kontrak PGK-000002 Diubah', 'supervisor_account', '01:33:22', '0', 'STP'),
(181, 'Pegawai Kontrak PGK-000003 Diubah', 'supervisor_account', '01:34:17', '0', 'KPG'),
(182, 'Pegawai Kontrak PGK-000003 Diubah', 'supervisor_account', '01:34:17', '1', 'KPP'),
(183, 'Pegawai Kontrak PGK-000003 Diubah', 'supervisor_account', '01:34:17', '0', 'SKU'),
(184, 'Pegawai Kontrak PGK-000003 Diubah', 'supervisor_account', '01:34:17', '0', 'STP'),
(185, 'Kode Pembelian PBL-000003 Dimasukkan', 'redeem', '16:08:52', '0', 'KPG'),
(186, 'Kode Pembelian PBL-000003 Dimasukkan', 'redeem', '16:08:52', '1', 'KPP'),
(187, 'Kode Pembelian PBL-000003 Dimasukkan', 'redeem', '16:08:52', '0', 'SKU'),
(188, 'Kode Pembelian PBL-000003 Dimasukkan', 'redeem', '16:08:52', '0', 'STP'),
(189, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '16:30:16', '0', 'KPG'),
(190, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '16:30:16', '1', 'KPP'),
(191, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '16:30:16', '0', 'SKU'),
(192, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '16:30:16', '0', 'STP'),
(193, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:39:30', '0', 'KPG'),
(194, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:39:30', '1', 'KPP'),
(195, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:39:30', '0', 'SKU'),
(196, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:39:30', '0', 'STP'),
(197, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:44:05', '0', 'KPG'),
(198, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:44:05', '1', 'KPP'),
(199, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:44:05', '0', 'SKU'),
(200, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:44:05', '0', 'STP'),
(201, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:47:20', '0', 'KPG'),
(202, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:47:20', '1', 'KPP'),
(203, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:47:20', '0', 'SKU'),
(204, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:47:20', '0', 'STP'),
(205, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:51:43', '0', 'KPG'),
(206, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:51:43', '1', 'KPP'),
(207, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:51:43', '0', 'SKU'),
(208, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '16:51:43', '0', 'STP'),
(209, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:53:53', '0', 'KPG'),
(210, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:53:53', '1', 'KPP'),
(211, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:53:53', '0', 'SKU'),
(212, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '16:53:53', '0', 'STP'),
(213, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '16:54:22', '0', 'KPG'),
(214, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '16:54:22', '1', 'KPP'),
(215, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '16:54:22', '0', 'SKU'),
(216, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '16:54:22', '0', 'STP'),
(217, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '16:55:31', '0', 'KPG'),
(218, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '16:55:31', '1', 'KPP'),
(219, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '16:55:31', '0', 'SKU'),
(220, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '16:55:31', '0', 'STP'),
(221, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '16:56:13', '0', 'KPG'),
(222, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '16:56:13', '1', 'KPP'),
(223, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '16:56:13', '0', 'SKU'),
(224, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '16:56:13', '0', 'STP'),
(225, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '16:57:44', '0', 'KPG'),
(226, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '16:57:44', '1', 'KPP'),
(227, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '16:57:44', '0', 'SKU'),
(228, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '16:57:44', '0', 'STP'),
(229, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '16:58:43', '0', 'KPG'),
(230, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '16:58:43', '1', 'KPP'),
(231, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '16:58:43', '0', 'SKU'),
(232, 'Kode Pembelian PBL-000002 Dimasukkan', 'redeem', '16:58:43', '0', 'STP'),
(233, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '02:02:15', '0', 'KPG'),
(234, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '02:02:15', '1', 'KPP'),
(235, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '02:02:15', '0', 'SKU'),
(236, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '02:02:15', '0', 'STP'),
(237, 'Kode Penjualan PJL-000002 Dimasukkan', 'shopping_basket', '02:17:56', '0', 'KPG'),
(238, 'Kode Penjualan PJL-000002 Dimasukkan', 'shopping_basket', '02:17:56', '1', 'KPP'),
(239, 'Kode Penjualan PJL-000002 Dimasukkan', 'shopping_basket', '02:17:56', '0', 'SKU'),
(240, 'Kode Penjualan PJL-000002 Dimasukkan', 'shopping_basket', '02:17:56', '0', 'STP'),
(241, 'Kode Akun 000 Dimasukkan', 'local_atm', '16:02:41', '0', 'KPG'),
(242, 'Kode Akun 000 Dimasukkan', 'local_atm', '16:02:41', '1', 'KPP'),
(243, 'Kode Akun 000 Dimasukkan', 'local_atm', '16:02:41', '0', 'SKU'),
(244, 'Kode Akun 000 Dimasukkan', 'local_atm', '16:02:41', '0', 'STP'),
(245, 'Kode Akun 800 Dimasukkan', 'local_atm', '16:03:52', '0', 'KPG'),
(246, 'Kode Akun 800 Dimasukkan', 'local_atm', '16:03:52', '1', 'KPP'),
(247, 'Kode Akun 800 Dimasukkan', 'local_atm', '16:03:52', '0', 'SKU'),
(248, 'Kode Akun 800 Dimasukkan', 'local_atm', '16:03:52', '0', 'STP'),
(249, 'Kode Posisi BRD Diubah', 'assignment_ind', '16:04:43', '0', 'KPG'),
(250, 'Kode Posisi BRD Diubah', 'assignment_ind', '16:04:43', '1', 'KPP'),
(251, 'Kode Posisi BRD Diubah', 'assignment_ind', '16:04:43', '0', 'SKU'),
(252, 'Kode Posisi BRD Diubah', 'assignment_ind', '16:04:43', '0', 'STP'),
(253, 'Pegawai Tetap 0009765241 Dimasukkan', 'supervisor_account', '16:14:44', '0', 'KPG'),
(254, 'Pegawai Tetap 0009765241 Dimasukkan', 'supervisor_account', '16:14:44', '1', 'KPP'),
(255, 'Pegawai Tetap 0009765241 Dimasukkan', 'supervisor_account', '16:14:44', '0', 'SKU'),
(256, 'Pegawai Tetap 0009765241 Dimasukkan', 'supervisor_account', '16:14:44', '0', 'STP'),
(257, 'Pegawai Tetap 0009876543 Dimasukkan', 'supervisor_account', '16:16:34', '0', 'KPG'),
(258, 'Pegawai Tetap 0009876543 Dimasukkan', 'supervisor_account', '16:16:34', '1', 'KPP'),
(259, 'Pegawai Tetap 0009876543 Dimasukkan', 'supervisor_account', '16:16:34', '0', 'SKU'),
(260, 'Pegawai Tetap 0009876543 Dimasukkan', 'supervisor_account', '16:16:34', '0', 'STP'),
(261, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '16:20:03', '0', 'KPG'),
(262, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '16:20:03', '1', 'KPP'),
(263, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '16:20:03', '0', 'SKU'),
(264, 'Pegawai Berhasil Dikeluarkan', 'supervisor_account', '16:20:03', '0', 'STP'),
(265, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '16:25:37', '0', 'KPG'),
(266, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '16:25:37', '1', 'KPP'),
(267, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '16:25:37', '0', 'SKU'),
(268, 'Pegawai Berhasil Dikonfirmasi', 'supervisor_account', '16:25:37', '0', 'STP'),
(269, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '16:33:26', '0', 'KPG'),
(270, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '16:33:26', '1', 'KPP'),
(271, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '16:33:26', '0', 'SKU'),
(272, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '16:33:26', '0', 'STP'),
(273, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '08:53:55', '0', 'KPG'),
(274, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '08:53:55', '1', 'KPP'),
(275, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '08:53:55', '0', 'SKU'),
(276, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '08:53:55', '0', 'STP'),
(277, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '08:58:29', '0', 'KPG'),
(278, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '08:58:29', '1', 'KPP'),
(279, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '08:58:29', '0', 'SKU'),
(280, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '08:58:29', '0', 'STP'),
(281, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:06:39', '0', 'KPG'),
(282, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:06:39', '1', 'KPP'),
(283, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:06:39', '0', 'SKU'),
(284, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:06:39', '0', 'STP'),
(285, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:13:31', '0', 'KPG'),
(286, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:13:31', '1', 'KPP'),
(287, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:13:31', '0', 'SKU'),
(288, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:13:31', '0', 'STP'),
(289, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:16:40', '0', 'KPG'),
(290, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:16:40', '1', 'KPP'),
(291, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:16:40', '0', 'SKU'),
(292, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:16:40', '0', 'STP'),
(293, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:25:38', '0', 'KPG'),
(294, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:25:38', '1', 'KPP'),
(295, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:25:38', '0', 'SKU'),
(296, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:25:38', '0', 'STP'),
(297, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:28:10', '0', 'KPG'),
(298, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:28:10', '1', 'KPP'),
(299, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:28:10', '0', 'SKU'),
(300, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:28:10', '0', 'STP'),
(301, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '11:54:28', '0', 'KPG'),
(302, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '11:54:28', '1', 'KPP'),
(303, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '11:54:28', '0', 'SKU'),
(304, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '11:54:28', '0', 'STP'),
(305, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:57:26', '0', 'KPG'),
(306, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:57:26', '1', 'KPP'),
(307, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:57:26', '0', 'SKU'),
(308, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '11:57:26', '0', 'STP'),
(309, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:07:55', '0', 'KPG'),
(310, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:07:55', '1', 'KPP'),
(311, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:07:55', '0', 'SKU'),
(312, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '12:07:55', '0', 'STP'),
(313, 'Kode Posisi PMT Dimasukkan', 'school', '12:41:26', '0', 'KPG'),
(314, 'Kode Posisi PMT Dimasukkan', 'school', '12:41:26', '1', 'KPP'),
(315, 'Kode Posisi PMT Dimasukkan', 'school', '12:41:26', '0', 'SKU'),
(316, 'Kode Posisi PMT Dimasukkan', 'school', '12:41:26', '0', 'STP'),
(317, 'Pegawai Kontrak PGK-000004 Dimasukkan', 'supervisor_account', '12:43:04', '0', 'KPG'),
(318, 'Pegawai Kontrak PGK-000004 Dimasukkan', 'supervisor_account', '12:43:04', '1', 'KPP'),
(319, 'Pegawai Kontrak PGK-000004 Dimasukkan', 'supervisor_account', '12:43:04', '0', 'SKU'),
(320, 'Pegawai Kontrak PGK-000004 Dimasukkan', 'supervisor_account', '12:43:04', '0', 'STP'),
(321, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:45:27', '0', 'KPG'),
(322, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:45:27', '1', 'KPP'),
(323, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:45:27', '0', 'SKU'),
(324, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:45:27', '0', 'STP'),
(325, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:46:49', '0', 'KPG'),
(326, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:46:49', '1', 'KPP'),
(327, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:46:49', '0', 'SKU'),
(328, 'Pegawai Tetap 0009724021 Diubah', 'supervisor_account', '12:46:49', '0', 'STP'),
(329, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '12:59:31', '0', 'KPG'),
(330, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '12:59:31', '1', 'KPP'),
(331, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '12:59:31', '0', 'SKU'),
(332, 'Pegawai Tetap 0009757896 Dimasukkan', 'supervisor_account', '12:59:31', '0', 'STP'),
(333, 'Pegawai Tetap 0009723211 Dimasukkan', 'supervisor_account', '13:01:48', '0', 'KPG'),
(334, 'Pegawai Tetap 0009723211 Dimasukkan', 'supervisor_account', '13:01:48', '1', 'KPP'),
(335, 'Pegawai Tetap 0009723211 Dimasukkan', 'supervisor_account', '13:01:48', '0', 'SKU'),
(336, 'Pegawai Tetap 0009723211 Dimasukkan', 'supervisor_account', '13:01:48', '0', 'STP'),
(337, 'Pegawai Tetap 0009724021 Dimasukkan', 'supervisor_account', '13:05:15', '0', 'KPG'),
(338, 'Pegawai Tetap 0009724021 Dimasukkan', 'supervisor_account', '13:05:15', '1', 'KPP'),
(339, 'Pegawai Tetap 0009724021 Dimasukkan', 'supervisor_account', '13:05:15', '0', 'SKU'),
(340, 'Pegawai Tetap 0009724021 Dimasukkan', 'supervisor_account', '13:05:15', '0', 'STP'),
(341, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '08:27:25', '0', 'KPG'),
(342, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '08:27:25', '1', 'KPP'),
(343, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '08:27:25', '0', 'SKU'),
(344, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '08:27:25', '0', 'STP'),
(345, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '09:07:10', '0', 'KPG'),
(346, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '09:07:10', '1', 'KPP'),
(347, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '09:07:10', '0', 'SKU'),
(348, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '09:07:10', '0', 'STP'),
(349, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '09:23:04', '0', 'KPG'),
(350, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '09:23:04', '1', 'KPP'),
(351, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '09:23:04', '0', 'SKU'),
(352, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '09:23:04', '0', 'STP'),
(353, 'Pegawai Tetap 0009722753 Dimasukkan', 'supervisor_account', '03:32:10', '0', 'KPG'),
(354, 'Pegawai Tetap 0009722753 Dimasukkan', 'supervisor_account', '03:32:10', '1', 'KPP'),
(355, 'Pegawai Tetap 0009722753 Dimasukkan', 'supervisor_account', '03:32:10', '0', 'SKU'),
(356, 'Pegawai Tetap 0009722753 Dimasukkan', 'supervisor_account', '03:32:10', '0', 'STP'),
(357, 'Kode Posisi STP Diubah', 'assignment_ind', '03:55:44', '0', 'KPG'),
(358, 'Kode Posisi STP Diubah', 'assignment_ind', '03:55:44', '1', 'KPP'),
(359, 'Kode Posisi STP Diubah', 'assignment_ind', '03:55:44', '0', 'SKU'),
(360, 'Kode Posisi STP Diubah', 'assignment_ind', '03:55:44', '0', 'STP'),
(361, 'Kode Posisi SKU Diubah', 'assignment_ind', '03:56:06', '0', 'KPG'),
(362, 'Kode Posisi SKU Diubah', 'assignment_ind', '03:56:06', '1', 'KPP'),
(363, 'Kode Posisi SKU Diubah', 'assignment_ind', '03:56:06', '0', 'SKU'),
(364, 'Kode Posisi SKU Diubah', 'assignment_ind', '03:56:06', '0', 'STP'),
(365, 'Kode Posisi STP Diubah', 'assignment_ind', '04:09:25', '0', 'KPG'),
(366, 'Kode Posisi STP Diubah', 'assignment_ind', '04:09:25', '1', 'KPP'),
(367, 'Kode Posisi STP Diubah', 'assignment_ind', '04:09:25', '0', 'SKU'),
(368, 'Kode Posisi STP Diubah', 'assignment_ind', '04:09:25', '0', 'STP'),
(369, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '22:46:28', '0', 'KPG'),
(370, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '22:46:28', '1', 'KPP'),
(371, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '22:46:28', '0', 'SKU'),
(372, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '22:46:28', '0', 'STP'),
(373, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '23:02:43', '0', 'KPG'),
(374, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '23:02:43', '1', 'KPP'),
(375, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '23:02:43', '0', 'SKU'),
(376, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '23:02:43', '0', 'STP'),
(377, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '23:16:44', '0', 'KPG'),
(378, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '23:16:44', '1', 'KPP'),
(379, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '23:16:44', '0', 'SKU'),
(380, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '23:16:44', '0', 'STP'),
(381, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:25:39', '0', 'KPG'),
(382, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:25:39', '1', 'KPP'),
(383, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:25:39', '0', 'SKU'),
(384, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:25:39', '0', 'STP'),
(385, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:48:20', '0', 'KPG'),
(386, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:48:20', '1', 'KPP'),
(387, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:48:20', '0', 'SKU'),
(388, 'Penggajian dan Pengupahan Berhasil Dimasukkan', 'contact_mail', '12:48:20', '0', 'STP'),
(389, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '00:24:06', '0', 'KPG'),
(390, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '00:24:06', '1', 'KPP'),
(391, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '00:24:06', '0', 'SKU'),
(392, 'Kode Pesanan PSN-000002 Dimasukkan', 'rate_review', '00:24:06', '0', 'STP'),
(393, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '00:26:32', '0', 'KPG'),
(394, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '00:26:32', '1', 'KPP'),
(395, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '00:26:32', '0', 'SKU'),
(396, 'Kode Pesanan PSN-000001 Dimasukkan', 'rate_review', '00:26:32', '0', 'STP'),
(397, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '00:28:50', '0', 'KPG'),
(398, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '00:28:50', '1', 'KPP'),
(399, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '00:28:50', '0', 'SKU'),
(400, 'Kode Pembelian PBL-000001 Dimasukkan', 'redeem', '00:28:50', '0', 'STP'),
(401, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '00:42:53', '0', 'KPG'),
(402, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '00:42:53', '1', 'KPP'),
(403, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '00:42:53', '0', 'SKU'),
(404, 'Kode Penyerahan PYB-000001 Dimasukkan', 'local_shipping', '00:42:53', '0', 'STP'),
(405, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '00:43:51', '0', 'KPG'),
(406, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '00:43:51', '1', 'KPP'),
(407, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '00:43:51', '0', 'SKU'),
(408, 'Kode Penyerahan PYB-000002 Dimasukkan', 'local_shipping', '00:43:51', '0', 'STP'),
(409, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '00:51:08', '0', 'KPG'),
(410, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '00:51:08', '1', 'KPP'),
(411, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '00:51:08', '0', 'SKU'),
(412, 'Kode Produksi PRS-000001 Dimasukkan', 'assignment_turned_in', '00:51:08', '0', 'STP'),
(413, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '01:04:37', '0', 'KPG'),
(414, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '01:04:37', '1', 'KPP'),
(415, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '01:04:37', '0', 'SKU'),
(416, 'Kode Produksi PRS-000002 Dimasukkan', 'assignment_turned_in', '01:04:37', '0', 'STP'),
(417, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '02:17:23', '0', 'KPG'),
(418, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '02:17:23', '1', 'KPP'),
(419, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '02:17:23', '0', 'SKU'),
(420, 'Kode Penjualan PJL-000001 Dimasukkan', 'shopping_basket', '02:17:23', '0', 'STP'),
(421, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '02:27:15', '0', 'KPG'),
(422, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '02:27:15', '1', 'KPP'),
(423, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '02:27:15', '0', 'SKU'),
(424, 'Kode Pembayaran Beban PBN-000003 Dimasukkan', 'local_library', '02:27:15', '0', 'STP');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` varchar(20) NOT NULL,
  `nama_pegawai` varchar(30) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `no_hp` char(12) DEFAULT NULL,
  `tanggal_masuk` date NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `foto` text NOT NULL,
  `nik_pegawai` char(20) NOT NULL,
  `status_pernikahan` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `alamat`, `no_hp`, `tanggal_masuk`, `username`, `password`, `foto`, `nik_pegawai`, `status_pernikahan`, `tanggal_lahir`) VALUES
('0009701117', 'Rike Raeni', 'Jagabaya', '087875914321', '2000-01-01', 'rikeraeni', '$2y$10$zUCPGi1h5mfaqBp6jWlCVO6r1dke1B4sfJAOXaKW9qX/gY5IFSmca', 'index.png', '3209876578765432', 'Menikah', '1970-03-05'),
('0009722753', 'Ajay', 'Garut', '081320456454', '2019-04-23', 'ajayy', '$2y$10$mP5X2wAuYJzSPq5bYa689eY17Xqp86oBt9Io9eWyl2sOjcA8L6q9a', 'index.png', '3202217649023546', 'Belum Menikah', '1992-01-03'),
('0009723211', 'Wahyu', 'Banjaran', '086753240987', '2003-02-08', 'wahyu', '$2y$10$gVVl028KryOTWNyitvWxXO2pu5wxAxU8EIQZGBI6FVvL1TEsU0iTK', 'index.png', '3200223454378654', 'Menikah', '1977-02-03'),
('0009724021', 'Riva', 'Jl. Babakan Ciparay', '086754321980', '2016-06-09', 'rivaa', '$2y$10$MgLU37zEOrLrp.mlQIw5oOAKX5c2W0o6fRabdejMwWJGbNzqcJR0e', 'index.png', '3220760123008787', 'Belum Menikah', '1994-04-29'),
('0009757896', 'Danis', 'Jl. Margahayu Permai No.24', '081320606324', '2012-01-02', 'danis', '$2y$10$LnEhe1k9Ligk/Zgjwp0hbuFoXTnF8qAZSJ.QA49YJdu8QNRAWuUai', 'index.png', '3201234009870876', 'Menikah', '1989-04-06'),
('PGK-000001', 'Erwin', 'Jl. Garut No.99', '087875914102', '2014-01-01', 'root', 'No Password', 'index.png', '12345678910', 'Belum Menikah', '0000-00-00'),
('PGK-000002', 'Yuda', 'Cianjur', '089871212222', '2014-05-01', 'root', 'No Password', 'index.png', '10987654321', 'Menikah', '0000-00-00'),
('PGK-000003', 'Lilis', 'Jl. Kenangan No.44', '089765231212', '2014-09-08', 'root', 'No Password', 'index.png', '00987654321', 'Menikah', '0000-00-00'),
('PGK-000004', 'Azam', 'jl. Bartu Rahayu no.9', '085678911234', '2014-04-02', 'root', 'No Password', 'index.png', '3204445671009876', 'Menikah', '1985-04-02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_beban`
--

CREATE TABLE `pembayaran_beban` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pembayaran_beban`
--

INSERT INTO `pembayaran_beban` (`id_transaksi`, `tanggal_transaksi`, `total`) VALUES
('PBN-000001', '2019-02-27', 500000),
('PBN-000002', '2019-03-27', 400000),
('PBN-000003', '2019-05-05', 1450000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_gaji`
--

CREATE TABLE `pembayaran_gaji` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `id_gaji` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_upah`
--

CREATE TABLE `pembayaran_upah` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `id_upah` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pembelian`
--

INSERT INTO `pembelian` (`id_transaksi`, `tanggal_transaksi`, `total`) VALUES
('PBL-000001', '2019-05-05', 1955000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penggajian`
--

CREATE TABLE `penggajian` (
  `id_gaji` varchar(10) NOT NULL,
  `tanggal_gaji` date DEFAULT NULL,
  `total_gaji` int(11) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengupahan`
--

CREATE TABLE `pengupahan` (
  `id_upah` varchar(10) NOT NULL,
  `tanggal_upah` date DEFAULT NULL,
  `total_upah` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id_transaksi`, `tanggal_transaksi`, `total`) VALUES
('PJL-000001', '2019-05-05', 4807500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyerahan_bahan`
--

CREATE TABLE `penyerahan_bahan` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penyerahan_bahan`
--

INSERT INTO `penyerahan_bahan` (`id_transaksi`, `tanggal_transaksi`, `total`) VALUES
('PYB-000001', '2019-05-05', 1420000),
('PYB-000002', '2019-05-05', 535000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `no_pesanan` varchar(10) NOT NULL,
  `nama_pemesan` varchar(30) DEFAULT NULL,
  `no_hp` char(20) NOT NULL,
  `barcode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`no_pesanan`, `nama_pemesan`, `no_hp`, `barcode`) VALUES
('PSN-000001', 'Okta Pascal Ibrahim', '(1234)-5678-9101', 'assets/barcode/PSN-000001.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `no_presensi` bigint(20) NOT NULL,
  `id_pegawai` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `presensi`
--

INSERT INTO `presensi` (`no_presensi`, `id_pegawai`, `tanggal`, `status`) VALUES
(1, '0009701117', '2019-02-28', 'Hadir'),
(2, '0009701117', '2019-03-01', 'Hadir'),
(3, '0009701117', '2019-03-02', 'Hadir'),
(4, '0009701117', '2019-03-04', 'Hadir'),
(5, '0009701117', '2019-03-05', 'Hadir'),
(6, '0009701117', '2019-03-06', 'Hadir'),
(7, '0009701117', '2019-03-07', 'Hadir'),
(8, '0009701117', '2019-03-08', 'Hadir'),
(9, '0009701117', '2019-03-09', 'Hadir'),
(10, '0009701117', '2019-03-11', 'Hadir'),
(11, '0009701117', '2019-03-12', 'Hadir'),
(12, '0009701117', '2019-03-13', 'Hadir'),
(13, '0009701117', '2019-03-14', 'Hadir'),
(14, '0009701117', '2019-03-15', 'Hadir'),
(15, '0009701117', '2019-03-16', 'Hadir'),
(16, '0009701117', '2019-03-18', 'Hadir'),
(17, '0009757896', '2019-04-01', 'Hadir'),
(18, '0009723211', '2019-04-01', 'Hadir'),
(19, '0009724021', '2019-04-01', 'Hadir'),
(20, '0009723211', '2019-04-02', 'Hadir'),
(21, '0009724021', '2019-04-02', 'Hadir'),
(22, '0009757896', '2019-04-02', 'Hadir'),
(23, '0009723211', '2019-04-05', 'Hadir'),
(24, '0009724021', '2019-04-05', 'Hadir'),
(25, '0009757896', '2019-04-06', 'Hadir'),
(26, '0009723211', '2019-04-08', 'Hadir'),
(27, '0009724021', '2019-04-08', 'Hadir'),
(28, '0009723211', '2019-04-09', 'Hadir'),
(29, '0009724021', '2019-04-09', 'Hadir'),
(30, '0009757896', '2019-04-09', 'Hadir'),
(31, '0009724021', '2019-04-10', 'Hadir'),
(32, '0009723211', '2019-04-11', 'Hadir'),
(33, '0009724021', '2019-04-11', 'Hadir'),
(34, '0009757896', '2019-04-11', 'Hadir'),
(35, '0009723211', '2019-04-12', 'Hadir'),
(36, '0009724021', '2019-04-12', 'Hadir'),
(37, '0009757896', '2019-04-12', 'Hadir'),
(38, '0009723211', '2019-04-13', 'Hadir'),
(39, '0009724021', '2019-04-14', 'Hadir'),
(40, '0009757896', '2019-04-15', 'Hadir'),
(41, '0009723211', '2019-04-16', 'Hadir'),
(42, '0009724021', '2019-04-16', 'Hadir'),
(43, '0009757896', '2019-04-16', 'Hadir'),
(44, '0009723211', '2019-04-18', 'Hadir'),
(45, '0009724021', '2019-04-18', 'Hadir'),
(46, '0009724021', '2019-04-22', 'Hadir'),
(47, '0009723211', '2019-04-22', 'Hadir'),
(48, '0009723211', '2019-04-27', 'Hadir'),
(49, '0009724021', '2019-04-29', 'Hadir'),
(50, '0009757896', '2019-04-27', 'Hadir'),
(51, '0009723211', '2019-04-30', 'Hadir'),
(52, '0009724021', '2019-04-30', 'Hadir'),
(53, '0009757896', '2019-04-30', 'Hadir'),
(54, '0009757896', '2019-04-24', 'Hadir'),
(55, '0009757896', '2019-04-22', 'Hadir'),
(56, '0009722753', '2019-03-31', 'Hadir'),
(57, '0009722753', '2019-04-01', 'Hadir'),
(58, '0009722753', '2019-04-04', 'Hadir'),
(59, '0009722753', '2019-04-07', 'Hadir'),
(60, '0009722753', '2019-04-08', 'Hadir'),
(61, '0009722753', '2019-04-10', 'Hadir'),
(62, '0009722753', '2019-04-11', 'Hadir'),
(63, '0009722753', '2019-04-12', 'Hadir'),
(64, '0009722753', '2019-04-15', 'Hadir'),
(65, '0009722753', '2019-04-17', 'Hadir'),
(66, '0009722753', '2019-04-21', 'Hadir'),
(67, '0009722753', '2019-04-26', 'Hadir'),
(68, '0009722753', '2019-04-28', 'Hadir'),
(69, '0009722753', '2019-04-29', 'Hadir'),
(70, '0009722753', '2019-04-23', 'Hadir');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` varchar(10) NOT NULL,
  `nama_produk` varchar(20) DEFAULT NULL,
  `ukuran` char(3) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `ukuran`, `stok`) VALUES
('PRD-000001', 'Gamis Muslim', 'L', 10),
('PRD-000002', 'Gamis Abaya', 'L', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi`
--

CREATE TABLE `produksi` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `no_pesanan` varchar(10) DEFAULT NULL,
  `id_produk` varchar(10) NOT NULL,
  `bbb` int(11) DEFAULT NULL,
  `btkl` int(11) DEFAULT NULL,
  `bop` int(11) DEFAULT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `produksi`
--

INSERT INTO `produksi` (`id_transaksi`, `tanggal_transaksi`, `no_pesanan`, `id_produk`, `bbb`, `btkl`, `bop`, `total`) VALUES
('PRS-000001', '2019-05-05', 'PSN-000001', 'PRD-000001', 1300000, 530000, 355000, 2185000),
('PRS-000002', '2019-05-05', 'PSN-000001', 'PRD-000002', 500000, 265000, 255000, 1020000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarif_posisi`
--

CREATE TABLE `tarif_posisi` (
  `id_posisi` varchar(10) NOT NULL,
  `nama_posisi` varchar(30) DEFAULT NULL,
  `status` char(10) DEFAULT NULL,
  `tunjangan_kesehatan` int(11) DEFAULT NULL,
  `tunjangan_makan` int(11) DEFAULT NULL,
  `tarif_per_produk` int(11) DEFAULT NULL,
  `tarif_per_hari` int(11) DEFAULT NULL,
  `status_keaktifan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tarif_posisi`
--

INSERT INTO `tarif_posisi` (`id_posisi`, `nama_posisi`, `status`, `tunjangan_kesehatan`, `tunjangan_makan`, `tarif_per_produk`, `tarif_per_hari`, `status_keaktifan`) VALUES
('BRD', 'Pembordir', 'Kontrak', 0, 250000, 13000, 0, 'Aktif'),
('FNH', 'Finishing', 'Kontrak', 0, 250000, 10000, 0, 'Aktif'),
('KPG', 'Staff Gudang', 'Tetap', 90000, 350000, 0, 120000, 'Aktif'),
('KPP', 'Kepala Produksi', 'Tetap', 90000, 350000, 0, 125000, 'Aktif'),
('PJH', 'Penjahit', 'Kontrak', 0, 250000, 15000, 0, 'Aktif'),
('PMK', 'Pemilik', 'Tetap', 50000, 100000, 10000, 15000, 'Aktif'),
('PMT', 'Pemotong Kain', 'Kontrak', 0, 250000, 15000, 0, 'Aktif'),
('SKU', 'Staff Keuangan', 'Tetap', 90000, 350000, 0, 125000, 'Aktif'),
('SKU-000001', 'Staff Keuangan', '0', 90000, 350000, 0, 125000, 'Tidak Aktif'),
('STP', 'Staff Penjualan', 'Tetap', 90000, 350000, 20000, 130000, 'Aktif'),
('STP-000001', 'Staff Penjualan', '0', 90000, 350000, 10000, 130000, 'Tidak Aktif'),
('STP-000002', 'Staff Penjualan', '0', 90000, 350000, 0, 130000, 'Tidak Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `total`) VALUES
('PBL-000001', '2019-05-05', 1955000),
('PBN-000001', '2019-02-27', 500000),
('PBN-000002', '2019-03-27', 400000),
('PBN-000003', '2019-05-05', 1450000),
('PJL-000001', '2019-05-05', 4807500),
('PRS-000001', '2019-05-05', 2185000),
('PRS-000002', '2019-05-05', 1020000),
('PYB-000001', '2019-05-05', 1420000),
('PYB-000002', '2019-05-05', 535000);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`no_akun`);

--
-- Indeks untuk tabel `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `beban`
--
ALTER TABLE `beban`
  ADD PRIMARY KEY (`id_beban`),
  ADD KEY `no_akun` (`no_akun`);

--
-- Indeks untuk tabel `bom`
--
ALTER TABLE `bom`
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indeks untuk tabel `btkl`
--
ALTER TABLE `btkl`
  ADD PRIMARY KEY (`id_btkl`);

--
-- Indeks untuk tabel `detail_beban`
--
ALTER TABLE `detail_beban`
  ADD KEY `id_biaya` (`id_beban`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_btkl`
--
ALTER TABLE `detail_btkl`
  ADD KEY `id_transaksi` (`id_btkl`),
  ADD KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `detail_pembayaran_beban`
--
ALTER TABLE `detail_pembayaran_beban`
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_beban` (`id_beban`);

--
-- Indeks untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_bahan` (`id_bahan`),
  ADD KEY `no_pesanan` (`no_pesanan`);

--
-- Indeks untuk tabel `detail_penggajian`
--
ALTER TABLE `detail_penggajian`
  ADD KEY `id_gaji` (`id_gaji`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `detail_pengupahan`
--
ALTER TABLE `detail_pengupahan`
  ADD KEY `id_upah` (`id_upah`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_penyerahan_bahan`
--
ALTER TABLE `detail_penyerahan_bahan`
  ADD KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `id_bahan` (`id_bahan`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_tarif_posisi`
--
ALTER TABLE `detail_tarif_posisi`
  ADD KEY `id_jabatan` (`id_posisi`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  ADD KEY `no_akun` (`no_akun`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `id_posisi` (`id_posisi`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indeks untuk tabel `pembayaran_beban`
--
ALTER TABLE `pembayaran_beban`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `pembayaran_gaji`
--
ALTER TABLE `pembayaran_gaji`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_gaji` (`id_gaji`);

--
-- Indeks untuk tabel `pembayaran_upah`
--
ALTER TABLE `pembayaran_upah`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_upah` (`id_upah`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `penggajian`
--
ALTER TABLE `penggajian`
  ADD PRIMARY KEY (`id_gaji`);

--
-- Indeks untuk tabel `pengupahan`
--
ALTER TABLE `pengupahan`
  ADD PRIMARY KEY (`id_upah`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `penyerahan_bahan`
--
ALTER TABLE `penyerahan_bahan`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`no_pesanan`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`no_presensi`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `produksi`
--
ALTER TABLE `produksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `tarif_posisi`
--
ALTER TABLE `tarif_posisi`
  ADD PRIMARY KEY (`id_posisi`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `no_presensi` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `beban`
--
ALTER TABLE `beban`
  ADD CONSTRAINT `beban_ibfk_1` FOREIGN KEY (`no_akun`) REFERENCES `akun` (`no_akun`);

--
-- Ketidakleluasaan untuk tabel `bom`
--
ALTER TABLE `bom`
  ADD CONSTRAINT `bom_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bom_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_beban`
--
ALTER TABLE `detail_beban`
  ADD CONSTRAINT `detail_beban_ibfk_1` FOREIGN KEY (`id_beban`) REFERENCES `beban` (`id_beban`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_beban_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_btkl`
--
ALTER TABLE `detail_btkl`
  ADD CONSTRAINT `detail_btkl_ibfk_1` FOREIGN KEY (`id_btkl`) REFERENCES `btkl` (`id_btkl`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_btkl_ibfk_2` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_btkl_ibfk_3` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_btkl_ibfk_4` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pembayaran_beban`
--
ALTER TABLE `detail_pembayaran_beban`
  ADD CONSTRAINT `detail_pembayaran_beban_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `pembayaran_beban` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembayaran_beban_ibfk_2` FOREIGN KEY (`id_beban`) REFERENCES `beban` (`id_beban`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `pembelian` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_3` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_penggajian`
--
ALTER TABLE `detail_penggajian`
  ADD CONSTRAINT `detail_penggajian_ibfk_1` FOREIGN KEY (`id_gaji`) REFERENCES `penggajian` (`id_gaji`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penggajian_ibfk_2` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pengupahan`
--
ALTER TABLE `detail_pengupahan`
  ADD CONSTRAINT `detail_pengupahan_ibfk_1` FOREIGN KEY (`id_upah`) REFERENCES `pengupahan` (`id_upah`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pengupahan_ibfk_2` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `penjualan` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penjualan_ibfk_3` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_penyerahan_bahan`
--
ALTER TABLE `detail_penyerahan_bahan`
  ADD CONSTRAINT `detail_penyerahan_bahan_ibfk_2` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penyerahan_bahan_ibfk_3` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penyerahan_bahan_ibfk_4` FOREIGN KEY (`id_transaksi`) REFERENCES `penyerahan_bahan` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penyerahan_bahan_ibfk_5` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_tarif_posisi`
--
ALTER TABLE `detail_tarif_posisi`
  ADD CONSTRAINT `detail_tarif_posisi_ibfk_1` FOREIGN KEY (`id_posisi`) REFERENCES `tarif_posisi` (`id_posisi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_tarif_posisi_ibfk_2` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  ADD CONSTRAINT `jurnal_ibfk_1` FOREIGN KEY (`no_akun`) REFERENCES `akun` (`no_akun`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_posisi`) REFERENCES `tarif_posisi` (`id_posisi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran_beban`
--
ALTER TABLE `pembayaran_beban`
  ADD CONSTRAINT `pembayaran_beban_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran_gaji`
--
ALTER TABLE `pembayaran_gaji`
  ADD CONSTRAINT `pembayaran_gaji_ibfk_1` FOREIGN KEY (`id_gaji`) REFERENCES `penggajian` (`id_gaji`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_gaji_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran_upah`
--
ALTER TABLE `pembayaran_upah`
  ADD CONSTRAINT `pembayaran_upah_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_upah_ibfk_2` FOREIGN KEY (`id_upah`) REFERENCES `pengupahan` (`id_upah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penyerahan_bahan`
--
ALTER TABLE `penyerahan_bahan`
  ADD CONSTRAINT `penyerahan_bahan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produksi`
--
ALTER TABLE `produksi`
  ADD CONSTRAINT `produksi_ibfk_1` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produksi_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produksi_ibfk_3` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
