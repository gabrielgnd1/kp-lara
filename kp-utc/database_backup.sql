-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 04, 2026 at 02:45 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kp-utc`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional`
--

CREATE TABLE `additional` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Available','Not Available') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `nama_area` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `nama_area`) VALUES
(1, 'Museum'),
(2, 'Aula');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diskusi_laporan`
--

CREATE TABLE `diskusi_laporan` (
  `user_id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `diskusi` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diskusi_laporan`
--

INSERT INTO `diskusi_laporan` (`user_id`, `laporan_id`, `diskusi`, `created_at`, `updated_at`) VALUES
(13, 5, 'tolong dipercepat', '2025-11-09 18:23:15', '2025-11-09 18:23:15'),
(14, 1, 'aa', '2025-11-11 03:03:44', '2025-11-11 03:03:44'),
(14, 5, 'baik', '2025-12-19 07:45:00', '2025-12-19 07:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_reservasi`
--

CREATE TABLE `dokumen_reservasi` (
  `id` int(11) NOT NULL,
  `tipe` enum('FORM','INVOICE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` blob NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `reservasi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `keterangan` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Available','Not Available') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_user` enum('Eksternal','Internal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `menginap` enum('Menginap','Tidak Menginap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `day` enum('Weekday','Weekend') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `nama`, `kapasitas`, `keterangan`, `status`, `jenis_user`, `menginap`, `harga`, `day`) VALUES
(349, 'Multifunction Hall', 275, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 800000, 'Weekday'),
(350, 'Multifunction Hall', 275, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 1500000, 'Weekend'),
(351, 'Multifunction Hall', 275, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 0, 'Weekday'),
(352, 'Multifunction Hall', 275, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 0, 'Weekend'),
(353, 'Albizia Cottage', 16, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Eksternal', 'Menginap', 700000, 'Weekday'),
(354, 'Albizia Cottage', 16, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Eksternal', 'Menginap', 900000, 'Weekend'),
(355, 'Albizia Cottage', 16, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Internal', 'Menginap', 550000, 'Weekday'),
(356, 'Albizia Cottage', 16, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(357, 'Bamboo Cottage', 8, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Eksternal', 'Menginap', 500000, 'Weekday'),
(358, 'Bamboo Cottage', 8, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Eksternal', 'Menginap', 700000, 'Weekend'),
(359, 'Bamboo Cottage', 8, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Internal', 'Menginap', 250000, 'Weekday'),
(360, 'Bamboo Cottage', 8, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Internal', 'Menginap', 350000, 'Weekend'),
(361, 'Coffee Cottage', 12, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Eksternal', 'Menginap', 500000, 'Weekday'),
(362, 'Coffee Cottage', 12, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Eksternal', 'Menginap', 700000, 'Weekend'),
(363, 'Coffee Cottage', 12, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Internal', 'Menginap', 250000, 'Weekday'),
(364, 'Coffee Cottage', 12, 'Fasilitas: Lemari Es, Tv Multi Channel, Water Heater', 'Available', 'Internal', 'Menginap', 350000, 'Weekend'),
(365, 'Camping + Tenda', 2, 'Camping', 'Available', 'Eksternal', 'Menginap', 35000, 'Weekday'),
(366, 'Camping + Tenda', 2, 'Camping', 'Available', 'Eksternal', 'Menginap', 40000, 'Weekend'),
(367, 'Camping + Tenda', 2, 'Camping', 'Available', 'Internal', 'Menginap', 25000, 'Weekday'),
(368, 'Camping + Tenda', 2, 'Camping', 'Available', 'Internal', 'Menginap', 35000, 'Weekend'),
(369, 'Pendapa Pawitra', 100, 'Back Sound 1 Unit', 'Available', 'Eksternal', 'Menginap', 800000, 'Weekday'),
(370, 'Pendapa Pawitra', 100, 'Back Sound 1 Unit', 'Available', 'Eksternal', 'Menginap', 1100000, 'Weekend'),
(371, 'Pendapa Pawitra', 100, 'Back Sound 1 Unit', 'Available', 'Internal', 'Menginap', 0, 'Weekday'),
(372, 'Pendapa Pawitra', 100, 'Back Sound 1 Unit', 'Available', 'Internal', 'Menginap', 0, 'Weekend'),
(373, 'Hall A/B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 500000, 'Weekday'),
(374, 'Hall A/B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 800000, 'Weekend'),
(375, 'Hall A/B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 400000, 'Weekday'),
(376, 'Hall A/B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 500000, 'Weekend'),
(377, 'Hall A+B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 800000, 'Weekday'),
(378, 'Hall A+B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(379, 'Hall A+B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 600000, 'Weekday'),
(380, 'Hall A+B', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 800000, 'Weekend'),
(381, 'Welirang Room', 50, '', 'Available', 'Eksternal', 'Menginap', 350000, 'Weekday'),
(382, 'Welirang Room', 50, '', 'Available', 'Eksternal', 'Menginap', 450000, 'Weekend'),
(383, 'Welirang Room', 50, '', 'Available', 'Internal', 'Menginap', 250000, 'Weekday'),
(384, 'Welirang Room', 50, '', 'Available', 'Internal', 'Menginap', 350000, 'Weekend'),
(385, 'Cinnamon Executive Meeting Room', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 400000, 'Weekday'),
(386, 'Cinnamon Executive Meeting Room', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Eksternal', 'Menginap', 500000, 'Weekend'),
(387, 'Cinnamon Executive Meeting Room', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 300000, 'Weekday'),
(388, 'Cinnamon Executive Meeting Room', 75, 'Fasilitas: Sound System, Microphone (Wireless & Cable), Screen', 'Available', 'Internal', 'Menginap', 400000, 'Weekend'),
(389, 'Arjuna Room', 50, '', 'Available', 'Eksternal', 'Menginap', 300000, 'Weekday'),
(390, 'Arjuna Room', 50, '', 'Available', 'Eksternal', 'Menginap', 400000, 'Weekend'),
(391, 'Arjuna Room', 50, '', 'Available', 'Internal', 'Menginap', 200000, 'Weekday'),
(392, 'Arjuna Room', 50, '', 'Available', 'Internal', 'Menginap', 300000, 'Weekend'),
(393, 'Camping Ground 1', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 25000, 'Weekday'),
(394, 'Camping Ground 1', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 30000, 'Weekend'),
(395, 'Camping Ground 1', 100, 'Camping', 'Available', 'Internal', 'Menginap', 15000, 'Weekday'),
(396, 'Camping Ground 1', 100, 'Camping', 'Available', 'Internal', 'Menginap', 20000, 'Weekend'),
(397, 'Driver Room', 2, 'Room', 'Available', 'Eksternal', 'Menginap', 150000, 'Weekday'),
(398, 'Driver Room', 2, 'Room', 'Available', 'Eksternal', 'Menginap', 200000, 'Weekend'),
(399, 'Driver Room', 2, 'Room', 'Available', 'Internal', 'Menginap', 100000, 'Weekday'),
(400, 'Driver Room', 2, 'Room', 'Available', 'Internal', 'Menginap', 150000, 'Weekend'),
(401, 'VIP Cottage - Asparagus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(402, 'VIP Cottage - Asparagus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(403, 'VIP Cottage - Asparagus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(404, 'VIP Cottage - Asparagus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(405, 'VIP Cottage - Brocolli', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(406, 'VIP Cottage - Brocolli', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(407, 'VIP Cottage - Brocolli', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(408, 'VIP Cottage - Brocolli', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(409, 'VIP Cottage - Celery', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(410, 'VIP Cottage - Celery', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(411, 'VIP Cottage - Celery', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(412, 'VIP Cottage - Celery', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(413, 'VIP Cottage - Eucalyptus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(414, 'VIP Cottage - Eucalyptus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(415, 'VIP Cottage - Eucalyptus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(416, 'VIP Cottage - Eucalyptus', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(417, 'VIP Cottage - Fennel', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(418, 'VIP Cottage - Fennel', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(419, 'VIP Cottage - Fennel', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(420, 'VIP Cottage - Fennel', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(421, 'VIP Cottage - Ginger', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(422, 'VIP Cottage - Ginger', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(423, 'VIP Cottage - Ginger', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(424, 'VIP Cottage - Ginger', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(425, 'VIP Cottage - Kiwi', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(426, 'VIP Cottage - Kiwi', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(427, 'VIP Cottage - Kiwi', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(428, 'VIP Cottage - Kiwi', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(429, 'VIP Cottage - Lemon', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(430, 'VIP Cottage - Lemon', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(431, 'VIP Cottage - Lemon', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(432, 'VIP Cottage - Lemon', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(433, 'VIP Cottage - Mango', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(434, 'VIP Cottage - Mango', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(435, 'VIP Cottage - Mango', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(436, 'VIP Cottage - Mango', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(437, 'VIP Cottage - Papaya', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(438, 'VIP Cottage - Papaya', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(439, 'VIP Cottage - Papaya', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(440, 'VIP Cottage - Papaya', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(441, 'VIP Cottage - Tomato', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(442, 'VIP Cottage - Tomato', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(443, 'VIP Cottage - Tomato', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(444, 'VIP Cottage - Tomato', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(445, 'VIP Cottage - Salacca', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1000000, 'Weekday'),
(446, 'VIP Cottage - Salacca', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Eksternal', 'Menginap', 1200000, 'Weekend'),
(447, 'VIP Cottage - Salacca', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekday'),
(448, 'VIP Cottage - Salacca', 6, 'Style Tidur - Single Bed; Fasilitas: Lemari Es, Tv Multi Channel, Water Jug, Water Heater, Voucher Tiket Renang 6 Tiket / Hari', 'Available', 'Internal', 'Menginap', 750000, 'Weekend'),
(449, 'Avocado Cottage', 56, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Eksternal', 'Menginap', 90000, 'Weekday'),
(450, 'Avocado Cottage', 56, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Eksternal', 'Menginap', 110000, 'Weekend'),
(451, 'Avocado Cottage', 56, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Internal', 'Menginap', 75000, 'Weekday'),
(452, 'Avocado Cottage', 56, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Internal', 'Menginap', 75000, 'Weekend'),
(453, 'Banana Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Eksternal', 'Menginap', 90000, 'Weekday'),
(454, 'Banana Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Eksternal', 'Menginap', 110000, 'Weekend'),
(455, 'Banana Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Internal', 'Menginap', 75000, 'Weekday'),
(456, 'Banana Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur - Ranjang Susun', 'Available', 'Internal', 'Menginap', 75000, 'Weekend'),
(457, 'Cassava Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Eksternal', 'Menginap', 75000, 'Weekday'),
(458, 'Cassava Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Eksternal', 'Menginap', 100000, 'Weekend'),
(459, 'Cassava Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Internal', 'Menginap', 65000, 'Weekday'),
(460, 'Cassava Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Internal', 'Menginap', 65000, 'Weekend'),
(461, 'Durian Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Eksternal', 'Menginap', 75000, 'Weekday'),
(462, 'Durian Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Eksternal', 'Menginap', 100000, 'Weekend'),
(463, 'Durian Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Internal', 'Menginap', 65000, 'Weekday'),
(464, 'Durian Cottage', 52, 'Meals 3x Masakan Indonesia (Semi Prasmanan), Snack 2x + Coffee Break; Style Tidur: Barak', 'Available', 'Internal', 'Menginap', 65000, 'Weekend'),
(465, 'Camping Ground 2', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 25000, 'Weekday'),
(466, 'Camping Ground 2', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 30000, 'Weekend'),
(467, 'Camping Ground 2', 100, 'Camping', 'Available', 'Internal', 'Menginap', 15000, 'Weekday'),
(468, 'Camping Ground 2', 100, 'Camping', 'Available', 'Internal', 'Menginap', 20000, 'Weekend'),
(469, 'Camping Ground 3', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 25000, 'Weekday'),
(470, 'Camping Ground 3', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 30000, 'Weekend'),
(471, 'Camping Ground 3', 100, 'Camping', 'Available', 'Internal', 'Menginap', 15000, 'Weekday'),
(472, 'Camping Ground 3', 100, 'Camping', 'Available', 'Internal', 'Menginap', 20000, 'Weekend'),
(473, 'Camping Ground 4', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 25000, 'Weekday'),
(474, 'Camping Ground 4', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 30000, 'Weekend'),
(475, 'Camping Ground 4', 100, 'Camping', 'Available', 'Internal', 'Menginap', 15000, 'Weekday'),
(476, 'Camping Ground 4', 100, 'Camping', 'Available', 'Internal', 'Menginap', 20000, 'Weekend'),
(477, 'Camping Ground 5', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 25000, 'Weekday'),
(478, 'Camping Ground 5', 100, 'Camping', 'Available', 'Eksternal', 'Menginap', 30000, 'Weekend'),
(479, 'Camping Ground 5', 100, 'Camping', 'Available', 'Internal', 'Menginap', 15000, 'Weekday'),
(480, 'Camping Ground 5', 100, 'Camping', 'Available', 'Internal', 'Menginap', 20000, 'Weekend');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `nama_laporan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_laporan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decision` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prioritas` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lapor` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `tanggal_deadline` date NOT NULL,
  `tipe_laporan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifikasi` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `nama_laporan`, `foto_laporan`, `decision`, `prioritas`, `tanggal_lapor`, `tanggal_selesai`, `tanggal_deadline`, `tipe_laporan`, `notifikasi`, `user_id`, `area_id`) VALUES
(1, 'Rusak', '01JWGMZDR7ZBBMZGG80T9VKZ5M.jpeg', 'Belum Diproses', 'Sedang', '2025-05-30', '2025-06-30', '2025-06-05', 'Kerusakan', 'Belum Dibaca', 4, 2),
(2, 'Ganti Pintu', '01K0XFE9DR2SWET7V9HYTKJX24.jpg', 'Belum Diproses', 'Tinggi', '2025-07-24', '2025-08-24', '2025-08-07', 'Perbaikan', 'Belum Dibaca', 4, 2),
(3, 'Mboh', '01K0XGKR7BKB37F0E4ZKZAHYZP.jpg', 'Belum Diproses', 'Rendah', '2025-07-24', '2025-08-24', '2025-08-01', 'Perbaikan', 'Belum Dibaca', 4, 1),
(4, 'Tes', '01K2F2A1Z4Z26914JJCXRPNFR0.png', 'Belum Diproses', 'Sedang', '2025-08-12', '2025-09-12', '2025-09-02', 'Kebersihan', 'Belum Dibaca', 4, 1),
(5, 'Pintu Rusak', '01K6B1JW1D5932DDXV31XQHZSQ.jpg', 'Belum Diproses', 'Sedang', '2025-09-29', '2025-10-29', '2025-09-30', 'Kerusakan', 'Belum Dibaca', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `log_laporan`
--

CREATE TABLE `log_laporan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `diskusi_laporan_user_id` int(11) NOT NULL,
  `diskusi_laporan_laporan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_makan`
--

CREATE TABLE `menu_makan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `status` enum('Available','Not Available') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_makan`
--

INSERT INTO `menu_makan` (`id`, `nama`, `harga`, `status`, `deskripsi`) VALUES
(7, 'Paket A+', 51000, 'Available', 'Meals + Coffee Break\nSudah termasuk minum air putih, kopi dan tea'),
(8, 'Paket A', 46000, 'Available', 'Meals + Coffee Break\nSudah termasuk minum air putih, kopi dan tea'),
(9, 'Paket B', 39000, 'Available', 'Meals + Coffee Break\nSudah termasuk minum air putih dan tea');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_10_012101_add_timestamps_to_diskusi_laporan', 1),
(2, '2025_11_11_000000_add_jumlah_orang_to_pemesanan_fasilitas', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pastas`
--

CREATE TABLE `pastas` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pastas`
--

INSERT INTO `pastas` (`id`, `name`, `url`, `description`, `price`) VALUES
(1, 'SALMON AGLIO OLIO', 'https://shiokmanrecipes.com/wp-content/uploads/2021/03/Shiokman-Recipes-2-1.png', 'Pasta Spaghetti, Cabai, Paprika Hijau, Bawang Putih dengan Salmon Panggang', 52000),
(2, 'CLASSIC FETTUCCINE', 'https://iambaker.net/wp-content/uploads/2015/04/alfredo-2-800x879.jpg', 'Pasta Fettuccine, Daging Ayam Asap, Saus Creamy dengan Chicken Strip dibalur Cream Cheese Mayo dan Beef Bits', 35000),
(3, 'CHEESE LAVA', 'https://i.pinimg.com/736x/ef/7a/05/ef7a05077502aacd9747d3c971d7bdf9.jpg', 'Pasta Fusilli, Pepperoni Sapi, Saus Keju Cheddar, Beef Bits dengan Saus Cheese Fondue', 38000),
(4, 'CREAMY TRUFFLE', 'https://www.createwithnestle.ph/sites/default/files/srh_recipes/04cac32dfe4fd75e56928b74df71544c.png', 'Pasta Penne, Sosis Beef Chorizo,Bayam, Saus Alfredo, dan Truffle Oil', 42000),
(5, 'SALMON MENTAIKO', 'https://media-cdn.tripadvisor.com/media/photo-s/14/25/1b/0b/salmon-ikura-mentaiko.jpg', 'Pasta Spaghetti, Ikan Salmon Fillet, Saus Mayo Mentai, dan Nori.', 56000),
(6, 'Red Sauce Pasta', 'https://img.freepik.com/free-photo/penne-pasta-tomato-sauce-with-chicken-tomatoes-wooden-table_2829-19739.jpg?semt=ais_hybrid&w=740', 'A classic Italian-inspired dish featuring al dente pasta smothered in a rich, tangy tomato-based sauce. Made with ripe tomatoes, garlic, onions, olive oil, and a blend of herbs like basil and oregano, the sauce strikes a perfect balance between sweet and savory. Optional additions like chili flakes, vegetables, or ground meat can kick up the flavor and texture.', 100000),
(7, 'Red Sauce Pasta', 'https://img.freepik.com/free-photo/penne-pasta-tomato-sauce-with-chicken-tomatoes-wooden-table_2829-19739.jpg?semt=ais_hybrid&w=740', 'A classic Italian-inspired dish featuring al dente pasta smothered in a rich, tangy tomato-based sauce. Made with ripe tomatoes, garlic, onions, olive oil, and a blend of herbs like basil and oregano, the sauce strikes a perfect balance between sweet and savory. Optional additions like chili flakes, vegetables, or ground meat can kick up the flavor and texture.', 100000);

-- --------------------------------------------------------

--
-- Table structure for table `pasta_instruction`
--

CREATE TABLE `pasta_instruction` (
  `pasta_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `instruction` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pasta_instruction`
--

INSERT INTO `pasta_instruction` (`pasta_id`, `step`, `instruction`) VALUES
(1, 1, 'Marinade the salmon fillet for about 10 minutes with mixed herbs and a few drops of lemon juice.'),
(1, 2, 'Mince garlic.'),
(1, 3, 'Glaze salmon with 1 tbsp olive oil and grill in oven for about 8 minutes or until salmon is no longer \"translucent\".'),
(1, 4, 'Cook angel hair pasta in a pot of boiling water for about 2 minutes (until al dente).'),
(1, 5, 'While pasta is cooking, heat the rest of the olive oil over a medium flame and add garlic and chilli flakes.'),
(1, 6, 'When garlic becomes fragrant, turn off the fire.'),
(1, 7, 'Drain pasta and mix it into the olive oil mixture.'),
(1, 8, 'Sprinkle salt and mix.'),
(1, 9, 'Pour pasta onto plates and top with grilled salmon.'),
(1, 10, 'Smear butter on top of salmon and let it melt.'),
(1, 11, 'Garnish with whole chillis and serve.'),
(2, 1, 'Cook Noodles in salted water according to package directions, approx 10-15 minutes.'),
(2, 2, 'Drain well and place in a container (chafing dish or skillet) large enough to allow enough room to toss slippery noodles without accident.'),
(2, 3, 'Place over low heat or sterno.'),
(2, 4, 'Add butter in several hunks to the noodles and stir gently until it melts and coats them well.'),
(2, 5, 'Add the cream and stir in a generous amount of black pepper.'),
(2, 6, '(Use a peppermill here if you have one).'),
(2, 7, 'Add the cheese, saving a small amount for later use on top of portions (Cheese is sometimes grated into the dish, but I like to have mine ready).'),
(2, 8, 'Stir as mixture thickens and clings to noodles.'),
(2, 9, 'Serve immediately.'),
(2, 10, 'Pass the remaining cheese.'),
(3, 1, 'Preheat oven to 350 degrees.'),
(3, 2, 'In a pan cook butter, salt, pepper and flour and stir until smooth.'),
(3, 3, 'Remove from heat add milk, return to heat and bring to a boil.'),
(3, 4, 'Boil 1 minute.'),
(3, 5, 'Remove from heat and add cheese, stir until melted.'),
(3, 6, 'Pour over elbows, sprinkle with breadcrumbs and bake at 350 degrees for 30 minutes.'),
(4, 1, 'Preheat the oven to 350°.'),
(4, 2, 'Spread the hazelnuts in a pie plate and bake for about 12 minutes, or until the nuts are fragrant and the skins blister. Let cool, then transfer the nuts to a kitchen towel and rub to remove the skins.'),
(4, 3, 'Transfer the nuts to a food processor and pulse until finely ground. Transfer the nuts to a bowl and wipe out the food processor.'),
(4, 4, 'In the food processor, combine the ricotta, goat cheese, mustard and truffle oil and process until smooth and creamy, scraping down the sides of the bowl.'),
(4, 5, 'Transfer the dip to a serving bowl and stir in the hazelnuts.'),
(4, 6, 'Season with salt and white pepper, add a little more truffle oil, if needed, and serve.'),
(5, 1, 'In a large bowl, mix the tobiko, mayonnaise and cream gently until well combined. Set aside.'),
(5, 2, 'Boil a pot of water and cook the pasta according to the instructions on the packet. Drain and run it under cold water to make sure they don’t stick together. Set is aside.'),
(5, 3, 'In a heavy based pan or pot, add the butter and allow it to melt completely before adding the chopped onions and garlic. Fry till fragrant, with caution not to burn it or it will taste bitter.'),
(5, 4, 'Add the salmon marinara and mix it well to make sure it is coated with the buttery onion and garlic mixture. Add the fish stock and allow it simmer for 15 minutes or until the salmon is completely cooked through'),
(5, 5, 'Use a strainer to take the salmon out and place them in the creamy tobiko mixture, reserving the liquid in the pot.  '),
(5, 6, 'To serve, place some pasta and salmon on a plate, and add some mentaiko sauce. Top the pasta with a soft boil egg and sprinkle with some shredded nori. Cut through the egg and allow the egg yolk to flavour and thicken the sauce. ENJOY!'),
(4, 7, 'Makan lah');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL,
  `jenis` enum('DP','LUNAS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti_pembayaran` blob NOT NULL,
  `tanggal_pembayaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_additional`
--

CREATE TABLE `pemesanan_additional` (
  `id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL,
  `additional_id` int(11) NOT NULL,
  `mulai` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_fasilitas`
--

CREATE TABLE `pemesanan_fasilitas` (
  `id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL,
  `fasilitas_id` int(11) NOT NULL,
  `jumlah_orang` int(100) DEFAULT NULL,
  `mulai` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemesanan_fasilitas`
--

INSERT INTO `pemesanan_fasilitas` (`id`, `reservasi_id`, `fasilitas_id`, `jumlah_orang`, `mulai`, `selesai`) VALUES
(1, 1, 5, 1, NULL, NULL),
(2, 1, 6, 1, NULL, NULL),
(3, 2, 7, 1, NULL, NULL),
(4, 2, 10, 1, NULL, NULL),
(5, 3, 12, 1, NULL, NULL),
(6, 4, 15, 1, NULL, NULL),
(7, 5, 18, 1, NULL, NULL),
(8, 6, 20, 1, NULL, NULL),
(9, 7, 25, 1, NULL, NULL),
(10, 8, 30, 1, NULL, NULL),
(12, 22, 352, 1, NULL, NULL),
(13, 22, 400, 1, NULL, NULL),
(31, 27, 351, 1, '2025-11-15 15:28:39', '2025-11-22 15:28:41'),
(32, 27, 451, 1, '2025-11-15 15:28:39', '2025-11-22 15:28:41'),
(33, 27, 415, 1, '2025-11-15 15:28:39', '2025-11-22 15:28:41'),
(34, 27, 367, 1, '2025-11-15 15:28:39', '2025-11-22 15:28:41');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_menu_makan`
--

CREATE TABLE `pemesanan_menu_makan` (
  `id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL,
  `menu_makan_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemesanan_menu_makan`
--

INSERT INTO `pemesanan_menu_makan` (`id`, `reservasi_id`, `menu_makan_id`, `jumlah`, `tanggal_waktu`) VALUES
(2, 22, 8, 1, '2025-09-09 03:41:22'),
(3, 22, 9, 1, '2025-09-09 03:41:39'),
(5, 25, 7, 1, '2025-09-09 13:12:59'),
(6, 26, 7, 1, '2025-09-22 11:53:27'),
(7, 27, 7, 12, '2025-10-08 08:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int(11) NOT NULL,
  `jenis_member` enum('Internal','Eksternal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pemesan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_kegiatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_check_in` timestamp NOT NULL DEFAULT current_timestamp(),
  `waktu_check_out` timestamp NOT NULL DEFAULT current_timestamp(),
  `jumlah_laki` int(11) NOT NULL DEFAULT 0,
  `jumlah_perempuan` int(11) NOT NULL DEFAULT 0,
  `informasi_tambahan` text COLLATE utf8mb4_unicode_ci DEFAULT '\'\'',
  `diskon` decimal(8,2) DEFAULT 0.00,
  `harga_akhir` decimal(12,2) DEFAULT 0.00,
  `status_reservasi` enum('ACC','NOT ACC') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NOT ACC',
  `status_pembayaran` enum('BARU','DP','LUNAS') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BARU',
  `file_reservation_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_bukti_dp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_bukti_lunas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_pembayaran` enum('DP','LUNAS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_pic_ioc` int(11) DEFAULT NULL,
  `id_pic_utc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id`, `jenis_member`, `nama_pemesan`, `no_telepon`, `email`, `judul_kegiatan`, `waktu_check_in`, `waktu_check_out`, `jumlah_laki`, `jumlah_perempuan`, `informasi_tambahan`, `diskon`, `harga_akhir`, `status_reservasi`, `status_pembayaran`, `file_reservation_form`, `file_bukti_dp`, `file_bukti_lunas`, `tipe_pembayaran`, `tanggal_dibuat`, `id_pic_ioc`, `id_pic_utc`) VALUES
(1, 'Internal', 'Andi Pratama', '081234567890', 'andi@example.com', 'Workshop Leadership', '2025-07-01 01:00:00', '2025-07-03 09:00:00', 10, 8, 'Butuh projector dan mic', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 5),
(2, 'Internal', 'Rina Wijaya', '081298745612', 'rina@example.com', 'Pelatihan Digital Marketing', '2025-07-05 02:00:00', '2025-07-07 05:00:00', 5, 12, 'Meja bulat, ruangan AC', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 6),
(3, 'Internal', 'Budi Haryanto', '082334556789', 'budi@example.com', 'Rapat Koordinasi Tahunan', '2025-07-10 06:00:00', '2025-07-12 04:00:00', 8, 5, 'Disediakan kopi dan snack', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 6),
(4, 'Internal', 'Maya Lestari', '081345672901', 'maya@example.com', 'Kegiatan Sosialisasi', '2025-07-15 03:00:00', '2025-07-16 07:00:00', 6, 9, 'Kursi lesehan dan LCD proyektor', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 8),
(5, 'Internal', 'Fajar Nugroho', '082145673210', 'fajar@example.com', 'Pelatihan Enterpreneurship', '2025-07-18 01:30:00', '2025-07-20 10:00:00', 12, 10, 'Tolong sediakan papan tulis besar', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 10),
(6, 'Internal', 'Dewi Sartika', '081876543219', 'dewi@example.com', 'Pendidikan Karakter', '2025-07-22 02:00:00', '2025-07-24 09:30:00', 7, 11, 'Butuh area outdoor untuk sesi ke-2', '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 6),
(7, 'Internal', 'Toni Ramadhan', '082356789321', 'toni@example.com', 'Rapat Internal', '2025-07-25 00:30:00', '2025-07-26 11:00:00', 4, 4, 'Ruangan kecil cukup', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 7),
(8, 'Internal', 'Nina Aprilia', '083289456123', 'nina@example.com', 'Pelatihan Public Speaking', '2025-07-27 01:00:00', '2025-07-29 08:00:00', 10, 6, 'Mikrofon tambahan diperlukan', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 8),
(9, 'Internal', 'Gilang Saputra', '081298345123', 'gilang@example.com', 'Seminar Nasional', '2025-08-01 02:00:00', '2025-08-03 10:00:00', 20, 25, 'Bendera dan banner akan dibawa sendiri', '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 10),
(10, 'Internal', 'Diah Puspita', '081212345678', 'diah@example.com', 'Diskusi Panel Mahasiswa', '2025-08-05 03:00:00', '2025-08-06 06:00:00', 9, 11, 'Tolong siapkan meja moderator', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-06-06 10:50:23', 12, 5),
(11, 'Internal', 'gabriel narendra', '08123456789', 'gabriel@yahoo.com', 'gabriel\'s sleepover', '2025-08-19 09:14:45', '2025-08-25 09:14:48', 3, 4, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:14:17', NULL, NULL),
(12, 'Internal', 'jane doe', '03111111111', 'jane@example.com', 'jane needs healing ', '2025-08-25 09:18:56', '2025-08-27 09:19:00', 2, 2, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:18:24', NULL, NULL),
(13, 'Internal', 'aaaaaa', '111', 'aaa@aa.com', 'aaaaa', '2025-08-19 09:24:38', '2025-08-20 09:24:42', 1, 1, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:24:23', NULL, NULL),
(14, 'Internal', 'bb', '11', 'vbb@b.cin', 'vv', '2025-08-19 09:26:14', '2025-08-20 09:26:17', 1, 1, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:25:55', NULL, NULL),
(15, 'Internal', 'a', '1', 'a@a.com', 'a', '2025-08-19 09:30:34', '2025-08-20 09:30:39', 1, 1, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:30:12', NULL, NULL),
(16, 'Internal', 'b', '1', 'b@b.com', 'b', '2025-08-19 09:35:06', '2025-08-20 09:35:09', 21, 30, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:34:45', NULL, NULL),
(17, 'Internal', 'ccccc', '23233', 'c@c.com', 'ccccc', '2025-08-19 09:39:50', '2025-08-25 09:39:52', 30, 30, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:39:30', NULL, NULL),
(18, 'Internal', 'gabriel', '123', 'gabriel@gmail.com', 'gabriel', '2025-08-21 09:46:20', '2025-09-05 09:46:23', 1, 1, NULL, '0.00', '0.00', 'NOT ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:46:01', NULL, NULL),
(19, 'Internal', 'a', '12', 'a@a.com', 'aa', '2025-08-20 09:51:09', '2025-08-20 09:51:13', 12, 12, NULL, '0.00', '0.00', 'NOT ACC', 'DP', NULL, NULL, NULL, NULL, '2025-08-18 02:50:48', NULL, NULL),
(20, 'Internal', 'joni', '123', 'joni@gmail.com', 'joni', '2025-08-19 09:57:41', '2025-08-27 09:57:44', 11, 11, '', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-08-18 02:57:21', 0, 5),
(21, 'Internal', 'Gabriel', '081111', 'aaa@aa.com', 'joni', '2025-09-16 11:59:13', '2025-09-30 11:59:15', 1, 1, 'qwqwqwqwq', '0.00', '0.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-09-08 04:58:53', 0, 6),
(24, 'Internal', 'joshua', '08123456789', 'jane@example.com', 'vv', '2025-09-16 11:58:48', '2025-09-18 11:58:51', 5, 5, '', '0.00', '1600000.00', 'ACC', 'BARU', NULL, NULL, NULL, NULL, '2025-09-09 11:58:34', 0, 6),
(25, 'Internal', 'f', '1', 'f@f.com', 'ff', '2025-09-16 13:09:21', '2025-09-23 13:09:52', 5, 5, 'aaaa', '0.00', '51000.00', 'ACC', 'BARU', 'reservasi/forms/Reservasi Form.pdf', 'reservasi/bukti-pembayaran/Reservasi Form.pdf', NULL, 'DP', '2025-09-09 13:09:05', 0, 6),
(26, 'Internal', 'aurell', '123', 'a@a.com', 'aurel', '2025-09-23 11:51:38', '2025-09-29 11:51:41', 1, 2, 'asasa', '0.00', '306000.00', 'ACC', 'DP', 'reservasi/forms/Reservasi Form.pdf', 'reservasi/bukti-pembayaran/Reservasi Form.pdf', NULL, 'DP', '2025-09-22 11:51:22', 0, 6),
(27, 'Internal', 'abel', '123456789', 'abel@gmail.com', 'Abel Needs Healing Bro', '2025-12-19 08:28:39', '2025-12-25 17:00:00', 3, 2, 'Mau yang paling bagus', '11.00', '9126060.00', 'ACC', 'DP', 'reservasi/forms/Reservasi Form.pdf', 'reservasi/bukti-pembayaran/Reservasi Form.pdf', 'reservasi/bukti-pembayaran/Reservasi Form.pdf', 'LUNAS', '2025-10-08 08:28:18', 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `nama`) VALUES
(1, 'Super Admin'),
(2, 'Admin UTC'),
(3, 'Lapangan'),
(4, 'Admin IOC');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_role` int(11) NOT NULL,
  `status` enum('Available','Not Available') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `name`, `email`, `password`, `id_role`, `status`, `remember_token`) VALUES
(2, 'admin', 'Admin User', 'adminioc@utc.com', '$2y$12$D20YUAzxzrjjBZLB/v8mju/woaMelr1do7Hg4B/f4uB1jTdx9Nk3K', 4, 'Available', NULL),
(3, 'adminbaru', 'Admin Baru', 'adminbaru@example.com', '$2y$12$gKVnJ5LqBIKhC/ZDjOkBbu8y.ytBNXySdtyr5Ahg54Szr2NGRFRny', 1, 'Available', NULL),
(4, 'lapangan', 'Lapangan', 'lap@utc.com', '$2y$12$D20YUAzxzrjjBZLB/v8mju/woaMelr1do7Hg4B/f4uB1jTdx9Nk3K', 3, 'Available', NULL),
(5, 'reservasi', 'Reservasi UTC', 'reservasi@utc.com', '$2y$12$rzMkgrAU2CzNeSKhUlsbHO3wf.K.glBxurs5.cFVFSFaMixmblq.u', 2, 'Available', NULL),
(6, 'angelineaureliaa', 'Aurel', 'angelineaurelia123@gmail.com', '$2y$12$D20YUAzxzrjjBZLB/v8mju/woaMelr1do7Hg4B/f4uB1jTdx9Nk3K', 2, 'Available', NULL),
(7, 'hjeeh', 'wjhefjwhef', 'kwewe@x.com', '$2y$12$BGly0H/2x3S9B9Xkbpi9N.tlJeo.GUqNvW7Npfe0qYWw0Lw5mXhiu', 2, 'Available', NULL),
(8, 'hwjeqweg', 'wjhefjwhef', 'hdjahdha@x.com', '$2y$12$/2S0tO9/D5v2svNOilOZMOE5fMe55AGimVadS1m/BTlEefcektT2C', 2, 'Available', '5CGAbk48EAQ5aE7kJZzqUYfsIMaoCYrBDyWAI9HZIlPU1PzXQn9opZj5wIN1'),
(9, 'dimas', 'Dimas Santoso', 'dimas@example.com', '$2y$12$OxreGFv0dvMSSnxFBCUKA.kZ.UAvYQWcpxknrjxXsYSHinV8ZKNbK', 1, 'Available', NULL),
(10, 'rina.m', 'Rina Melati', 'rina.melati@example.com', '$2y$12$WLjdHPF3xI7MRQeAXvaf5.EA5ka7IW4zh0FKJbIzbXfeneA.lGCWa', 2, 'Available', NULL),
(11, 'bagusp', 'Bagus Prasetya', 'bagusp@example.com', '$2y$12$U7ak/akMGB7.RJwL.vW.auE4iv/4I2336WMf/5FWXxvD0QRq1CrQ2', 3, 'Available', NULL),
(12, 'fitria.n', 'Fitria Noor', 'fitria.noor@example.com', '$2y$12$D20YUAzxzrjjBZLB/v8mju/woaMelr1do7Hg4B/f4uB1jTdx9Nk3K', 4, 'Available', NULL),
(13, 'lapangan', 'Lapangan', 'lapangan@utc.com', '$2y$12$D20YUAzxzrjjBZLB/v8mju/woaMelr1do7Hg4B/f4uB1jTdx9Nk3K', 3, 'Available', NULL),
(14, 'superadmin', 'Super Admin', 'superadmin@utc.com', '$2y$12$D20YUAzxzrjjBZLB/v8mju/woaMelr1do7Hg4B/f4uB1jTdx9Nk3K', 1, 'Available', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional`
--
ALTER TABLE `additional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diskusi_laporan`
--
ALTER TABLE `diskusi_laporan`
  ADD PRIMARY KEY (`user_id`,`laporan_id`),
  ADD KEY `fk_user_has_laporan_laporan1_idx` (`laporan_id`),
  ADD KEY `fk_user_has_laporan_user1_idx` (`user_id`);

--
-- Indexes for table `dokumen_reservasi`
--
ALTER TABLE `dokumen_reservasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_laporan_user1_idx` (`user_id`),
  ADD KEY `fk_laporan_area1_idx` (`area_id`);

--
-- Indexes for table `log_laporan`
--
ALTER TABLE `log_laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_makan`
--
ALTER TABLE `menu_makan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan_additional`
--
ALTER TABLE `pemesanan_additional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan_fasilitas`
--
ALTER TABLE `pemesanan_fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan_menu_makan`
--
ALTER TABLE `pemesanan_menu_makan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_role1_idx` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional`
--
ALTER TABLE `additional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dokumen_reservasi`
--
ALTER TABLE `dokumen_reservasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=481;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `log_laporan`
--
ALTER TABLE `log_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_makan`
--
ALTER TABLE `menu_makan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_additional`
--
ALTER TABLE `pemesanan_additional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_fasilitas`
--
ALTER TABLE `pemesanan_fasilitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `pemesanan_menu_makan`
--
ALTER TABLE `pemesanan_menu_makan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diskusi_laporan`
--
ALTER TABLE `diskusi_laporan`
  ADD CONSTRAINT `fk_user_has_laporan_laporan1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`id`),
  ADD CONSTRAINT `fk_user_has_laporan_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `fk_laporan_area1` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`),
  ADD CONSTRAINT `fk_laporan_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
