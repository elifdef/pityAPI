-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Сер 21 2025 р., 21:10
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `blog_project`
--

-- --------------------------------------------------------

--
-- Структура таблиці `blog`
--

CREATE TABLE `blog` (
  `hash_post` varchar(255) NOT NULL DEFAULT '',
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `datetime_sending` datetime NOT NULL DEFAULT current_timestamp(),
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `countries`
--

CREATE TABLE `countries` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `iso2` char(2) DEFAULT NULL,
  `emoji` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso2`, `emoji`) VALUES
(0, '', NULL, NULL),
(1, 'Afghanistan', 'AF', '🇦🇫'),
(2, 'Aland Islands', 'AX', '🇦🇽'),
(3, 'Albania', 'AL', '🇦🇱'),
(4, 'Algeria', 'DZ', '🇩🇿'),
(5, 'American Samoa', 'AS', '🇦🇸'),
(6, 'Andorra', 'AD', '🇦🇩'),
(7, 'Angola', 'AO', '🇦🇴'),
(8, 'Anguilla', 'AI', '🇦🇮'),
(9, 'Antarctica', 'AQ', '🇦🇶'),
(10, 'Antigua And Barbuda', 'AG', '🇦🇬'),
(11, 'Argentina', 'AR', '🇦🇷'),
(12, 'Armenia', 'AM', '🇦🇲'),
(13, 'Aruba', 'AW', '🇦🇼'),
(14, 'Australia', 'AU', '🇦🇺'),
(15, 'Austria', 'AT', '🇦🇹'),
(16, 'Azerbaijan', 'AZ', '🇦🇿'),
(17, 'The Bahamas', 'BS', '🇧🇸'),
(18, 'Bahrain', 'BH', '🇧🇭'),
(19, 'Bangladesh', 'BD', '🇧🇩'),
(20, 'Barbados', 'BB', '🇧🇧'),
(21, 'Belarus', 'BY', '🇧🇾'),
(22, 'Belgium', 'BE', '🇧🇪'),
(23, 'Belize', 'BZ', '🇧🇿'),
(24, 'Benin', 'BJ', '🇧🇯'),
(25, 'Bermuda', 'BM', '🇧🇲'),
(26, 'Bhutan', 'BT', '🇧🇹'),
(27, 'Bolivia', 'BO', '🇧🇴'),
(28, 'Bosnia and Herzegovina', 'BA', '🇧🇦'),
(29, 'Botswana', 'BW', '🇧🇼'),
(30, 'Bouvet Island', 'BV', '🇧🇻'),
(31, 'Brazil', 'BR', '🇧🇷'),
(32, 'British Indian Ocean Territory', 'IO', '🇮🇴'),
(33, 'Brunei', 'BN', '🇧🇳'),
(34, 'Bulgaria', 'BG', '🇧🇬'),
(35, 'Burkina Faso', 'BF', '🇧🇫'),
(36, 'Burundi', 'BI', '🇧🇮'),
(37, 'Cambodia', 'KH', '🇰🇭'),
(38, 'Cameroon', 'CM', '🇨🇲'),
(39, 'Canada', 'CA', '🇨🇦'),
(40, 'Cape Verde', 'CV', '🇨🇻'),
(41, 'Cayman Islands', 'KY', '🇰🇾'),
(42, 'Central African Republic', 'CF', '🇨🇫'),
(43, 'Chad', 'TD', '🇹🇩'),
(44, 'Chile', 'CL', '🇨🇱'),
(45, 'China', 'CN', '🇨🇳'),
(46, 'Christmas Island', 'CX', '🇨🇽'),
(47, 'Cocos (Keeling) Islands', 'CC', '🇨🇨'),
(48, 'Colombia', 'CO', '🇨🇴'),
(49, 'Comoros', 'KM', '🇰🇲'),
(50, 'Congo', 'CG', '🇨🇬'),
(51, 'Democratic Republic of the Congo', 'CD', '🇨🇩'),
(52, 'Cook Islands', 'CK', '🇨🇰'),
(53, 'Costa Rica', 'CR', '🇨🇷'),
(54, 'Cote D\'Ivoire (Ivory Coast)', 'CI', '🇨🇮'),
(55, 'Croatia', 'HR', '🇭🇷'),
(56, 'Cuba', 'CU', '🇨🇺'),
(57, 'Cyprus', 'CY', '🇨🇾'),
(58, 'Czech Republic', 'CZ', '🇨🇿'),
(59, 'Denmark', 'DK', '🇩🇰'),
(60, 'Djibouti', 'DJ', '🇩🇯'),
(61, 'Dominica', 'DM', '🇩🇲'),
(62, 'Dominican Republic', 'DO', '🇩🇴'),
(63, 'East Timor', 'TL', '🇹🇱'),
(64, 'Ecuador', 'EC', '🇪🇨'),
(65, 'Egypt', 'EG', '🇪🇬'),
(66, 'El Salvador', 'SV', '🇸🇻'),
(67, 'Equatorial Guinea', 'GQ', '🇬🇶'),
(68, 'Eritrea', 'ER', '🇪🇷'),
(69, 'Estonia', 'EE', '🇪🇪'),
(70, 'Ethiopia', 'ET', '🇪🇹'),
(71, 'Falkland Islands', 'FK', '🇫🇰'),
(72, 'Faroe Islands', 'FO', '🇫🇴'),
(73, 'Fiji Islands', 'FJ', '🇫🇯'),
(74, 'Finland', 'FI', '🇫🇮'),
(75, 'France', 'FR', '🇫🇷'),
(76, 'French Guiana', 'GF', '🇬🇫'),
(77, 'French Polynesia', 'PF', '🇵🇫'),
(78, 'French Southern Territories', 'TF', '🇹🇫'),
(79, 'Gabon', 'GA', '🇬🇦'),
(80, 'Gambia The', 'GM', '🇬🇲'),
(81, 'Georgia', 'GE', '🇬🇪'),
(82, 'Germany', 'DE', '🇩🇪'),
(83, 'Ghana', 'GH', '🇬🇭'),
(84, 'Gibraltar', 'GI', '🇬🇮'),
(85, 'Greece', 'GR', '🇬🇷'),
(86, 'Greenland', 'GL', '🇬🇱'),
(87, 'Grenada', 'GD', '🇬🇩'),
(88, 'Guadeloupe', 'GP', '🇬🇵'),
(89, 'Guam', 'GU', '🇬🇺'),
(90, 'Guatemala', 'GT', '🇬🇹'),
(91, 'Guernsey and Alderney', 'GG', '🇬🇬'),
(92, 'Guinea', 'GN', '🇬🇳'),
(93, 'Guinea-Bissau', 'GW', '🇬🇼'),
(94, 'Guyana', 'GY', '🇬🇾'),
(95, 'Haiti', 'HT', '🇭🇹'),
(96, 'Heard Island and McDonald Islands', 'HM', '🇭🇲'),
(97, 'Honduras', 'HN', '🇭🇳'),
(98, 'Hong Kong S.A.R.', 'HK', '🇭🇰'),
(99, 'Hungary', 'HU', '🇭🇺'),
(100, 'Iceland', 'IS', '🇮🇸'),
(101, 'India', 'IN', '🇮🇳'),
(102, 'Indonesia', 'ID', '🇮🇩'),
(103, 'Iran', 'IR', '🇮🇷'),
(104, 'Iraq', 'IQ', '🇮🇶'),
(105, 'Ireland', 'IE', '🇮🇪'),
(106, 'Israel', 'IL', '🇮🇱'),
(107, 'Italy', 'IT', '🇮🇹'),
(108, 'Jamaica', 'JM', '🇯🇲'),
(109, 'Japan', 'JP', '🇯🇵'),
(110, 'Jersey', 'JE', '🇯🇪'),
(111, 'Jordan', 'JO', '🇯🇴'),
(112, 'Kazakhstan', 'KZ', '🇰🇿'),
(113, 'Kenya', 'KE', '🇰🇪'),
(114, 'Kiribati', 'KI', '🇰🇮'),
(115, 'North Korea', 'KP', '🇰🇵'),
(116, 'South Korea', 'KR', '🇰🇷'),
(117, 'Kuwait', 'KW', '🇰🇼'),
(118, 'Kyrgyzstan', 'KG', '🇰🇬'),
(119, 'Laos', 'LA', '🇱🇦'),
(120, 'Latvia', 'LV', '🇱🇻'),
(121, 'Lebanon', 'LB', '🇱🇧'),
(122, 'Lesotho', 'LS', '🇱🇸'),
(123, 'Liberia', 'LR', '🇱🇷'),
(124, 'Libya', 'LY', '🇱🇾'),
(125, 'Liechtenstein', 'LI', '🇱🇮'),
(126, 'Lithuania', 'LT', '🇱🇹'),
(127, 'Luxembourg', 'LU', '🇱🇺'),
(128, 'Macau S.A.R.', 'MO', '🇲🇴'),
(129, 'North Macedonia', 'MK', '🇲🇰'),
(130, 'Madagascar', 'MG', '🇲🇬'),
(131, 'Malawi', 'MW', '🇲🇼'),
(132, 'Malaysia', 'MY', '🇲🇾'),
(133, 'Maldives', 'MV', '🇲🇻'),
(134, 'Mali', 'ML', '🇲🇱'),
(135, 'Malta', 'MT', '🇲🇹'),
(136, 'Man (Isle of)', 'IM', '🇮🇲'),
(137, 'Marshall Islands', 'MH', '🇲🇭'),
(138, 'Martinique', 'MQ', '🇲🇶'),
(139, 'Mauritania', 'MR', '🇲🇷'),
(140, 'Mauritius', 'MU', '🇲🇺'),
(141, 'Mayotte', 'YT', '🇾🇹'),
(142, 'Mexico', 'MX', '🇲🇽'),
(143, 'Micronesia', 'FM', '🇫🇲'),
(144, 'Moldova', 'MD', '🇲🇩'),
(145, 'Monaco', 'MC', '🇲🇨'),
(146, 'Mongolia', 'MN', '🇲🇳'),
(147, 'Montenegro', 'ME', '🇲🇪'),
(148, 'Montserrat', 'MS', '🇲🇸'),
(149, 'Morocco', 'MA', '🇲🇦'),
(150, 'Mozambique', 'MZ', '🇲🇿'),
(151, 'Myanmar', 'MM', '🇲🇲'),
(152, 'Namibia', 'NA', '🇳🇦'),
(153, 'Nauru', 'NR', '🇳🇷'),
(154, 'Nepal', 'NP', '🇳🇵'),
(155, 'Bonaire, Sint Eustatius and Saba', 'BQ', '🇧🇶'),
(156, 'Netherlands', 'NL', '🇳🇱'),
(157, 'New Caledonia', 'NC', '🇳🇨'),
(158, 'New Zealand', 'NZ', '🇳🇿'),
(159, 'Nicaragua', 'NI', '🇳🇮'),
(160, 'Niger', 'NE', '🇳🇪'),
(161, 'Nigeria', 'NG', '🇳🇬'),
(162, 'Niue', 'NU', '🇳🇺'),
(163, 'Norfolk Island', 'NF', '🇳🇫'),
(164, 'Northern Mariana Islands', 'MP', '🇲🇵'),
(165, 'Norway', 'NO', '🇳🇴'),
(166, 'Oman', 'OM', '🇴🇲'),
(167, 'Pakistan', 'PK', '🇵🇰'),
(168, 'Palau', 'PW', '🇵🇼'),
(169, 'Palestinian Territory Occupied', 'PS', '🇵🇸'),
(170, 'Panama', 'PA', '🇵🇦'),
(171, 'Papua new Guinea', 'PG', '🇵🇬'),
(172, 'Paraguay', 'PY', '🇵🇾'),
(173, 'Peru', 'PE', '🇵🇪'),
(174, 'Philippines', 'PH', '🇵🇭'),
(175, 'Pitcairn Island', 'PN', '🇵🇳'),
(176, 'Poland', 'PL', '🇵🇱'),
(177, 'Portugal', 'PT', '🇵🇹'),
(178, 'Puerto Rico', 'PR', '🇵🇷'),
(179, 'Qatar', 'QA', '🇶🇦'),
(180, 'Reunion', 'RE', '🇷🇪'),
(181, 'Romania', 'RO', '🇷🇴'),
(182, 'Russia', 'RU', '🇷🇺'),
(183, 'Rwanda', 'RW', '🇷🇼'),
(184, 'Saint Helena', 'SH', '🇸🇭'),
(185, 'Saint Kitts And Nevis', 'KN', '🇰🇳'),
(186, 'Saint Lucia', 'LC', '🇱🇨'),
(187, 'Saint Pierre and Miquelon', 'PM', '🇵🇲'),
(188, 'Saint Vincent And The Grenadines', 'VC', '🇻🇨'),
(189, 'Saint-Barthelemy', 'BL', '🇧🇱'),
(190, 'Saint-Martin (French part)', 'MF', '🇲🇫'),
(191, 'Samoa', 'WS', '🇼🇸'),
(192, 'San Marino', 'SM', '🇸🇲'),
(193, 'Sao Tome and Principe', 'ST', '🇸🇹'),
(194, 'Saudi Arabia', 'SA', '🇸🇦'),
(195, 'Senegal', 'SN', '🇸🇳'),
(196, 'Serbia', 'RS', '🇷🇸'),
(197, 'Seychelles', 'SC', '🇸🇨'),
(198, 'Sierra Leone', 'SL', '🇸🇱'),
(199, 'Singapore', 'SG', '🇸🇬'),
(200, 'Slovakia', 'SK', '🇸🇰'),
(201, 'Slovenia', 'SI', '🇸🇮'),
(202, 'Solomon Islands', 'SB', '🇸🇧'),
(203, 'Somalia', 'SO', '🇸🇴'),
(204, 'South Africa', 'ZA', '🇿🇦'),
(205, 'South Georgia', 'GS', '🇬🇸'),
(206, 'South Sudan', 'SS', '🇸🇸'),
(207, 'Spain', 'ES', '🇪🇸'),
(208, 'Sri Lanka', 'LK', '🇱🇰'),
(209, 'Sudan', 'SD', '🇸🇩'),
(210, 'Suriname', 'SR', '🇸🇷'),
(211, 'Svalbard And Jan Mayen Islands', 'SJ', '🇸🇯'),
(212, 'Swaziland', 'SZ', '🇸🇿'),
(213, 'Sweden', 'SE', '🇸🇪'),
(214, 'Switzerland', 'CH', '🇨🇭'),
(215, 'Syria', 'SY', '🇸🇾'),
(216, 'Taiwan', 'TW', '🇹🇼'),
(217, 'Tajikistan', 'TJ', '🇹🇯'),
(218, 'Tanzania', 'TZ', '🇹🇿'),
(219, 'Thailand', 'TH', '🇹🇭'),
(220, 'Togo', 'TG', '🇹🇬'),
(221, 'Tokelau', 'TK', '🇹🇰'),
(222, 'Tonga', 'TO', '🇹🇴'),
(223, 'Trinidad And Tobago', 'TT', '🇹🇹'),
(224, 'Tunisia', 'TN', '🇹🇳'),
(225, 'Turkey', 'TR', '🇹🇷'),
(226, 'Turkmenistan', 'TM', '🇹🇲'),
(227, 'Turks And Caicos Islands', 'TC', '🇹🇨'),
(228, 'Tuvalu', 'TV', '🇹🇻'),
(229, 'Uganda', 'UG', '🇺🇬'),
(230, 'Ukraine', 'UA', '🇺🇦'),
(231, 'United Arab Emirates', 'AE', '🇦🇪'),
(232, 'United Kingdom', 'GB', '🇬🇧'),
(233, 'United States', 'US', '🇺🇸'),
(234, 'United States Minor Outlying Islands', 'UM', '🇺🇲'),
(235, 'Uruguay', 'UY', '🇺🇾'),
(236, 'Uzbekistan', 'UZ', '🇺🇿'),
(237, 'Vanuatu', 'VU', '🇻🇺'),
(238, 'Vatican City State (Holy See)', 'VA', '🇻🇦'),
(239, 'Venezuela', 'VE', '🇻🇪'),
(240, 'Vietnam', 'VN', '🇻🇳'),
(241, 'Virgin Islands (British)', 'VG', '🇻🇬'),
(242, 'Virgin Islands (US)', 'VI', '🇻🇮'),
(243, 'Wallis And Futuna Islands', 'WF', '🇼🇫'),
(244, 'Western Sahara', 'EH', '🇪🇭'),
(245, 'Yemen', 'YE', '🇾🇪'),
(246, 'Zambia', 'ZM', '🇿🇲'),
(247, 'Zimbabwe', 'ZW', '🇿🇼'),
(248, 'Kosovo', 'XK', '🇽🇰'),
(249, 'Curaçao', 'CW', '🇨🇼'),
(250, 'Sint Maarten (Dutch part)', 'SX', '🇸🇽');

-- --------------------------------------------------------

--
-- Структура таблиці `customization`
--

CREATE TABLE `customization` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `username_color` tinytext NOT NULL,
  `background` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role_level` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `roles`
--

INSERT INTO `roles` (`id`, `name`, `role_level`) VALUES
(0, 'User', 0),
(1, 'Creator', 255);

-- --------------------------------------------------------

--
-- Структура таблиці `sessions`
--

CREATE TABLE `sessions` (
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `client_ip` varchar(255) NOT NULL,
  `client_os` varchar(50) NOT NULL,
  `client_browser` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(256) NOT NULL,
  `token` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `private_blog` tinyint(1) NOT NULL DEFAULT 0,
  `private_profile` tinyint(1) NOT NULL DEFAULT 0,
  `country_id` mediumint(8) UNSIGNED DEFAULT 0,
  `birthdate` date NOT NULL,
  `IP` varchar(18) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `online_status` tinyint(1) NOT NULL,
  `lastlogoutdate` date DEFAULT NULL,
  `lastlogouttime` time DEFAULT NULL,
  `role_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`hash_post`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Індекси таблиці `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Індекси таблиці `customization`
--
ALTER TABLE `customization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Індекси таблиці `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `countries`
--
ALTER TABLE `countries`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT для таблиці `customization`
--
ALTER TABLE `customization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION;

--
-- Обмеження зовнішнього ключа таблиці `customization`
--
ALTER TABLE `customization`
  ADD CONSTRAINT `customization_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
