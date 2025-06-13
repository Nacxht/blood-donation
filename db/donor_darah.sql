CREATE DATABASE IF NOT EXISTS `donor_darah`;
USE `donor_darah`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` enum('admin','manager') NOT NULL DEFAULT 'manager',
  `username` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `gender` enum('male','female') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

INSERT INTO `admins` (`id`, `role`, `username`, `email`, `password`, `fullname`, `phone`, `gender`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin', 'admin@gmail.com', '$2a$10$yDVQ8aDZ4hzUevqWPhC3pu35uetKuRNStXDqyXP6F5T1jieNLgcqC', 'admin lorem ipsum', '088217639998', 'male', '2025-06-12 00:56:20', '2025-06-12 00:56:20');

CREATE TABLE IF NOT EXISTS `blood_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(2) NOT NULL,
  `rhesus` enum('-','+') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `blood_types` (`id`, `type`, `rhesus`, `created_at`, `updated_at`) VALUES
	(2, 'a', '+', '2025-06-11 17:26:19', '2025-06-11 17:26:19'),
	(3, 'a', '-', '2025-06-11 17:26:43', '2025-06-11 17:26:43'),
	(4, 'b', '+', '2025-06-11 17:27:10', '2025-06-11 17:27:10'),
	(5, 'b', '-', '2025-06-11 17:27:16', '2025-06-11 17:27:16'),
	(6, 'ab', '+', '2025-06-11 17:27:23', '2025-06-11 17:27:23'),
	(7, 'ab', '-', '2025-06-11 17:27:26', '2025-06-11 17:27:26'),
	(8, 'o', '+', '2025-06-11 17:27:31', '2025-06-11 17:27:31'),
	(9, 'o', '-', '2025-06-11 17:27:38', '2025-06-11 17:27:38');

CREATE TABLE IF NOT EXISTS `donation_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` text,
  `blood_type_id` int NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `donation_requests` (`id`, `event_name`, `location`, `date`, `time`, `description`, `blood_type_id`, `status`, `created_at`, `updated_at`) VALUES
	(2, 'info kenakalan', 'rumah yoga', '2025-06-28', '21:57:00', 'pendarahan di kaki', 7, 'active', '2025-06-13 10:57:13', '2025-06-13 10:57:13'),
	(3, 'sekolah mengadakan donor darah', 'SMAN 6 KEDIRI', '2025-07-14', '01:43:00', 'DI tunggu sekarang', 7, 'active', '2025-06-13 18:44:31', '2025-06-13 18:44:31');

CREATE TABLE IF NOT EXISTS `education_articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  `admin_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `article` (`admin_id`),
  CONSTRAINT `article` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
);

INSERT INTO `education_articles` (`id`, `title`, `content`, `thumbnail`, `admin_id`, `created_at`, `updated_at`) VALUES
	(1, 'tes artikel 1', 'aassdadsd', 'https://picsum.photos/1280/720', 1, '2025-06-13 19:01:51', '2025-06-13 19:01:51'),
	(2, 'tes', 'awkriwehr', 'https://res.cloudinary.com/dk0z4ums3/image/upload/v1643509091/attached_image/berbagai-manfaat-donor-darah-untuk-kesehatan-0-alodokter.jpg', 1, '2025-06-13 19:21:25', '2025-06-13 19:21:25'),
	(3, 'shhwiw', 'iduqweu', 'https://res.cloudinary.com/dk0z4ums3/image/upload/v1643509091/attached_image/berbagai-manfaat-donor-darah-untuk-kesehatan-0-alodokter.jpg', 1, '2025-06-13 19:22:30', '2025-06-13 19:22:30');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `age` int NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `address` text NOT NULL,
  `blood_type_id` int NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `donation_history` enum('y','n') NOT NULL,
  `last_donation` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `blood_type` (`blood_type_id`),
  CONSTRAINT `blood_type` FOREIGN KEY (`blood_type_id`) REFERENCES `blood_types` (`id`)
);

INSERT INTO `users` (`id`, `username`, `email`, `password`, `fullname`, `status`, `age`, `phone`, `address`, `blood_type_id`, `birth_date`, `gender`, `donation_history`, `last_donation`, `created_at`, `updated_at`) VALUES
	(12, 'asdadasdasdsad', 'zombiecrafter069@gmail.com', '$2y$10$F/TgqhZVEB0RfBUBAhxv.OzNDuj3qczryXcPkD1HJij01wrFAIHPi', 'edited', 'active', 23, '088217639998', 'dadasda', 2, '2025-06-03', 'male', 'y', '2025-06-11', '2025-06-12 19:24:14', '2025-06-12 19:24:14'),
	(13, 'bangedy', 'edi075412@gmail.com', '$2y$10$VSeJunQ3bYZyKfVRqi8OLeod4CS.vDGT4lJF9ClPJ2LC20qqOg2YW', 'tuii', 'active', 32, '088217639998', 'jln.', 2, '2025-06-03', 'male', 'n', NULL, '2025-06-13 16:15:04', '2025-06-13 16:15:04'),
	(16, 'sadasdaasd', 'ksdskjdk@gmail.com', '$2y$10$IZoo6e2WcbkYWqbxPCUoUeHnXaPJmJlp160CrLz5VGPsKwid1/JtW', 'jkadsjkajha', 'active', 23, '088217639998', 'sadadas', 2, '2025-06-09', 'male', 'y', '2025-06-12', '2025-06-13 16:35:26', '2025-06-13 16:35:26'),
	(18, 'adasdasds', 'asda@gmail.com', '$2y$10$LP4YindOuFje4o/hMHLzb.eU6xzHnEsx9EZuYpGzU9BPgSChwbyDm', 'sdsdsds', 'active', 33, '088217639998', 'asdads', 8, '2025-06-22', 'female', 'y', NULL, '2025-06-13 16:41:42', '2025-06-13 16:41:42'),
	(19, 'sddsdssd', 'kdkjjsjkdk@gmail.com', '$2y$10$NujQjyaKRhwKeNsOoyvakOKN7sfdzMnYuN6.CHeYdjBVwksqqMmC2', 'skjdksdjkjd', 'active', 55, '088217639998', 'ksdkskdjskjdk', 2, '2025-06-08', 'female', 'y', NULL, '2025-06-13 16:57:48', '2025-06-13 16:57:48'),
	(22, 'userusername', 'user@gmail.com', '$2y$10$lYt9nxn1F/PGJmNYUEYd.eomx6zXrytZ8qVR9F3ShwviraQtInhY.', 'user', 'active', 22, '088217639998', 'dadasda', 7, '2025-06-10', 'male', 'y', '2025-06-09', '2025-06-13 19:40:05', '2025-06-13 19:40:05'),
	(23, '444', '444@444', '$2y$10$qoAFme45KpMCDqMh6VM78eqSj1UWylVc4cL7NF/HHXiBjVlolnluW', 'asdadasd', 'active', 22, '088217639998', 'asdasd', 4, '2025-06-23', 'male', 'y', '2025-06-10', '2025-06-13 19:41:03', '2025-06-13 19:41:03'),
	(26, 'baba1', 'baba@gmail.com', '$2y$10$VdurrVvME7Op7AuBeZPUyesPLAN.sGvFp3aPBvM/o6X0Dbi8Tcy/S', 'baba', 'active', 35, '082333866533', 'hajdjakd', 6, '2012-02-14', 'male', 'n', '2025-06-11', '2025-06-13 19:52:00', '2025-06-13 19:52:00');
