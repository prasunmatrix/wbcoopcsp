-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2020 at 01:18 PM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel60_csp_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `csp_users`
--

DROP TABLE IF EXISTS `csp_users`;
CREATE TABLE `csp_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_parrent_id` int(11) NOT NULL,
  `user_type` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0=>HQ, 1=>Bank, 2=>Zone, 3=>Range,  4=>Pacs',
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_change_request_id` int(11) DEFAULT NULL,
  `password_updated` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=>No, 1=>Yes',
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastlogintime` int(11) DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 => Inactive, 1=>Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `csp_users`
--

INSERT INTO `csp_users` (`id`, `user_parrent_id`, `user_type`, `full_name`, `email`, `phone_no`, `password`, `password_change_request_id`, `password_updated`, `remember_token`, `lastlogintime`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, '', 'CspPortal', 'admin@cspportal.com', '54645645645', '$2y$10$AjPT2O2C3TkI5z.o9kcJsOyESLDbALxFVMNtz7hm47cyPIyCqbwEi', NULL, '1', 'YIvvlL7hbRrjdBiSpjBXM11paMMvjQFiUyfhO3QKmYUUgfk3tCVzu5DPaylL', 1601016763, '1', '2019-05-30 18:30:26', '2020-09-25 12:22:43', NULL),
(263, 0, '0', 'State Bank Of India', 'samiran@pixelsolutionz.com', '2020201520', '$2y$10$jHUmLwH5WQP7w6ncArx/UewDWblmOdE3i6.f10nwoXHgemNADaeQ.', NULL, '0', NULL, NULL, '1', '2020-09-23 14:50:38', '2020-09-23 20:20:38', NULL),
(264, 0, '0', 'State Bank Of India', 'samiransaha@matrixnmedia1.com', '1236585203', '$2y$10$SJtCYFw4kKRVVURWaNBg5OD.5oxri0vEKXbVkDDzSe8aGbEpvnZQm', NULL, '0', NULL, NULL, '1', '2020-09-23 15:32:12', '2020-09-23 21:02:12', NULL),
(265, 0, '1', 'United Bank Of India', 'samiransaha@matrixnlmedia.com', '1236326580', '$2y$10$xGxUgoIhMh20Tu0hZGWMSe.TQQnIBdy1gc8i7QpjK4ofcZTN5H.gi', NULL, '0', NULL, NULL, '1', '2020-09-23 15:35:56', '2020-09-23 21:05:56', NULL),
(266, 0, '1', 'United Bank Of IndiaSS', 'samiransaha@fmatrixnmedia.com', '1235685202', '$2y$10$8VuVk.AWgzmT8pbRRZtE4OSOn.seEoHmHxAw.gR4C4npRUQu9eyFG', NULL, '0', NULL, NULL, '0', '2020-09-23 15:38:27', '2020-09-24 01:54:59', NULL),
(267, 0, '1', 'State Bank Of India', 'samiransadsha@matrixnmedia.com', '2020325850', '$2y$10$dw.16DHDEJIdMqvY5.sIbeCmaJeB578pRDDYfZWX6Mj87yv1Sp7C6', NULL, '0', NULL, NULL, '1', '2020-09-23 15:42:37', '2020-09-23 21:12:37', NULL),
(268, 0, '1', 'United Bank Of India', 'wrt2samiran1@gmail.com', '2020258852', '$2y$10$pNmsl6lrafu.t6QyUfcmXeFcK2AtUCx0dMns9BlhPYwM0Iw3WPaFK', NULL, '0', NULL, NULL, '1', '2020-09-23 15:44:39', '2020-09-23 21:14:39', NULL),
(270, 0, '1', 'Central Bank', 'central@abcd.com', '1258785201', '$2y$10$2jWBvDYUSmBojsjyOkyWqu3s.X/UabQiDMMSAtpiRsDgn6lmPLGPO', NULL, '0', NULL, NULL, '1', '2020-09-23 21:11:32', '2020-09-24 02:41:32', NULL),
(271, 0, '1', 'Oversis bank', 'oversis@abcd.com', '2020202050', '$2y$10$zz/Q1vNNy1anrX5ku1zBjeCFfxx6O5HZB7YTkq2md/w1VNnNZ.Ska', NULL, '0', NULL, NULL, '1', '2020-09-24 05:29:06', '2020-09-24 12:45:53', NULL),
(272, 0, '1', 'Bank Of India', 'boi2@test.com', '52652525', '$2y$10$KQD3dJ/706mMBi/hHyWm7.cdC4Kqjdh4R38Fnz.41eABloRwHNoPy', NULL, '0', NULL, NULL, '1', '2020-09-24 05:49:59', '2020-09-24 11:19:59', NULL),
(273, 0, '1', 'Kotak mahindra Group', 'kotak@saa.com', '2502585201', '$2y$10$6bmc.KC7q9QTs/Ea2XgPqeg5ax7NKSiSeptil5MvaIL/YBYxGMTou', NULL, '0', NULL, NULL, '1', '2020-09-24 08:59:10', '2020-09-24 14:39:29', NULL),
(275, 0, '2', 'Kolkata Shyambazar', 'shyambazar@abcd.com', '1235852032', '$2y$10$Uz/A7uh2.j/IZblojJQOReeHSCq5C/G/jtvriNmNX4AiWl7mwuXYW', NULL, '0', NULL, NULL, '0', '2020-09-24 09:31:51', '2020-09-24 18:13:11', NULL),
(276, 0, '2', 'Barrackpore', 'demo1@abcd.com', '1234568520', '$2y$10$91YuygUeDB74m8fz8wD4DeekXQ6qAEnRqQ1bj111u..cdsnkAJATC', NULL, '0', NULL, NULL, '1', '2020-09-24 12:46:52', '2020-09-24 18:16:52', NULL),
(277, 0, '1', 'Kanara bank', 'kanara1@abcd.com', '2025852030', '$2y$10$9nCe23jr33QNCkK4M4iDN.68Y4JmPqHxsIQ5drxDzPELhrCpQ4wTu', NULL, '0', NULL, NULL, '1', '2020-09-24 13:04:47', '2020-09-24 18:34:47', NULL),
(278, 0, '1', 'Oversis bank', 'test@oversis.com', '2025878520', '$2y$10$xYBmXDKjRKvC42JebGvgy.ycYDez/B6FEXLHJtAeusJd/4KcqannS', NULL, '0', NULL, NULL, '1', '2020-09-24 13:47:24', '2020-09-24 19:17:24', NULL),
(279, 0, '1', 'State Bank Of India', 'samiran_0011@rediffmail.com', '1235852035', '$2y$10$lXhMbmS3tUSuMlthPsGEPeBWnCtsDRPJ5v5H0GlmKDRMp/57gYVl6', NULL, '0', NULL, NULL, '1', '2020-09-24 14:02:25', '2020-09-24 19:32:25', NULL),
(280, 0, '1', 'State Bank Of India', 'samiran_001111@rediffmail.com', '1235852035', '$2y$10$VwCqgHmrO4YOwfCsLKt8veZpNO8I5alflV8ZiSkgeomUJhKBdLTNG', NULL, '0', NULL, NULL, '1', '2020-09-24 14:03:05', '2020-09-24 19:33:05', NULL),
(284, 0, '1', 'Oversis bank', 'wrt2samiran1@gmail.com', '121212222', '$2y$10$sPN1PHe239mv.VCtuGopCuAMGjAWAuJZbloe6xpIrCvV0P0xRGKra', NULL, '0', NULL, NULL, '1', '2020-09-24 14:22:47', '2020-09-24 19:52:47', NULL),
(285, 0, '2', 'Howrah', 'samiransaha1@matrixnmedia.com', '1235857480', '$2y$10$hZdzZW1rfzUWzYka.3V8Neephpd8zuZgU94bht1X.StcZXS4ujQC.', NULL, '0', NULL, NULL, '1', '2020-09-24 14:26:10', '2020-09-24 19:56:10', NULL),
(287, 0, '2', '24 Pgs. North', 'wrt2samiran2@gmail.com', '1234568520', '$2y$10$3KE6y076lkE5cmw/UhrzIesaq0A5Lx9WpVWR/Dxbt.AOwangeTbhm', NULL, '0', NULL, NULL, '1', '2020-09-24 14:42:06', '2020-09-24 20:12:06', NULL),
(288, 0, '1', 'Kotak mahindra Group', 'samiransaha2@matrixnmedia.com', '1258785203', '$2y$10$rggBnhnfqh34MuGSziWlbugFBhHs/HpOWnf/sMfjwrzTaOPAffIxe', NULL, '0', NULL, NULL, '1', '2020-09-24 14:43:17', '2020-09-24 20:13:17', NULL),
(289, 0, '1', 'Punjab National Bank', 'wrt2samiran@gmail.com', '2000552100', '$2y$10$2.9bzG3S.d1dBOMh6UZdbe01A4GwU2R4dRtcMJGDTGYDTf6DPT5lC', NULL, '0', NULL, NULL, '1', '2020-09-24 14:49:10', '2020-09-24 20:19:10', NULL),
(290, 288, '2', 'Nadia', 'test@abcd.com', '2025857652', '$2y$10$L.UHptsMEevnH2NrAIJ0Du4F3VjByjHirK1ASkpfucbFqY7V1JKOi', NULL, '0', NULL, NULL, '1', '2020-09-25 06:57:19', '2020-09-25 12:27:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `csp_user_details`
--

DROP TABLE IF EXISTS `csp_user_details`;
CREATE TABLE `csp_user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `range_id` int(11) DEFAULT NULL,
  `ifsc_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `block_id` int(11) DEFAULT NULL,
  `socity_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socity_registration_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_registration_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `software_using` int(11) DEFAULT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information_correct_verified` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 => No, 1=>Yes',
  `unique_id_noted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 => No, 1=>Yes',
  `pacs_using_software` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 => No, 1=>Yes',
  `pacs_uploaded_format` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 => No, 1=>Yes',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `csp_user_details`
--

INSERT INTO `csp_user_details` (`id`, `user_id`, `profile_image`, `bank_id`, `zone_id`, `range_id`, `ifsc_code`, `address`, `district_id`, `block_id`, `socity_type`, `socity_registration_no`, `district_registration_no`, `software_using`, `unique_id`, `information_correct_verified`, `unique_id_noted`, `pacs_using_software`, `pacs_uploaded_format`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 263, 'member_1600872638.jpg', NULL, NULL, NULL, 'SBIN0202', '2020201520', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 14:50:38', NULL, NULL),
(18, 0, 'member_1600875132.jpg', NULL, NULL, NULL, 'GTJK12', '1236585203', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 15:32:12', NULL, NULL),
(19, 265, 'member_1600875356.png', NULL, NULL, NULL, 'UBI125', '1236326580', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 15:35:56', NULL, NULL),
(20, 266, 'member_1600891171.jpg', NULL, NULL, NULL, 'UBI12001', 'Kolkata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 15:38:27', NULL, NULL),
(21, 267, 'member_1600895205.jpg', NULL, NULL, NULL, 'SBIN0208', 'Rajarhat, Kol-112', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 15:42:37', NULL, NULL),
(22, 268, 'member_1600875879.jpg', NULL, NULL, NULL, 'DSJK20235', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 15:44:39', NULL, NULL),
(23, 270, 'member_1600931642.png', NULL, NULL, NULL, 'CEN021UYT', 'Bongaon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-23 21:11:32', NULL, NULL),
(24, 271, 'member_1600952455.png', NULL, NULL, NULL, 'OVSC5202', '25, Black burn road', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 05:29:06', NULL, NULL),
(25, 272, 'member_1600926776.png', NULL, NULL, NULL, 'BOI125Y', '15, Prince Anawarsha Road', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 05:49:59', NULL, NULL),
(26, 273, 'member_1600937950.png', NULL, NULL, NULL, 'KOT451RA', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 08:59:10', NULL, NULL),
(28, 275, 'member_1600939911.png', 271, NULL, NULL, NULL, 'Kolkata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 09:31:51', NULL, NULL),
(29, 276, 'member_1600951612.jpg', 273, NULL, NULL, NULL, 'Barrackpore', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 12:46:52', NULL, NULL),
(30, 277, 'member_1600952687.png', NULL, NULL, NULL, 'KNR5265UY', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 13:04:47', NULL, NULL),
(31, 278, 'member_1600955244.png', NULL, NULL, NULL, 'OVGS45', 'Kolkata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 13:47:24', NULL, NULL),
(32, 279, 'member_1600956146.png', NULL, NULL, NULL, 'SBIN5820', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:02:26', NULL, NULL),
(33, 280, NULL, NULL, NULL, NULL, 'SBIN58200', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:03:05', NULL, NULL),
(34, 281, NULL, NULL, NULL, NULL, 'sdsd', 'cscsc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:18:29', NULL, NULL),
(35, 282, NULL, NULL, NULL, NULL, 'sdsdsdsd', 'cscsc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:19:40', NULL, NULL),
(36, 283, NULL, NULL, NULL, NULL, 'sdsds1212dsd', 'cscsc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:20:00', NULL, NULL),
(37, 284, 'member_1600957367.png', NULL, NULL, NULL, 'OVS25788', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:22:47', NULL, NULL),
(38, 285, 'member_1600957570.png', 278, NULL, NULL, NULL, 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:26:10', NULL, NULL),
(39, 286, 'member_1600958480.png', 277, NULL, NULL, NULL, 'Naihati', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:41:20', NULL, NULL),
(40, 287, 'member_1600958526.png', 267, NULL, NULL, NULL, 'Naihati', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:42:06', NULL, NULL),
(41, 288, 'member_1600958597.png', NULL, NULL, NULL, 'KOTA5222JH', 'Chakdaha, West Bengal, India', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:43:17', NULL, NULL),
(42, 289, 'member_1600958950.png', NULL, NULL, NULL, 'PNB252Kj', 'Kolkata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-24 14:49:10', NULL, NULL),
(43, 290, 'member_1601017039.png', 288, NULL, NULL, NULL, 'Nadia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '2020-09-25 06:57:19', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `csp_users`
--
ALTER TABLE `csp_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `csp_user_details`
--
ALTER TABLE `csp_user_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `csp_users`
--
ALTER TABLE `csp_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;
--
-- AUTO_INCREMENT for table `csp_user_details`
--
ALTER TABLE `csp_user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
