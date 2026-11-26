-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 26, 2026 at 06:50 AM
-- Server version: 10.11.19-MariaDB
-- PHP Version: 8.3.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `address_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `category` enum('Family','Work','Friends') NOT NULL DEFAULT 'Friends',
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `gender`, `category`, `phone`, `email`, `address`, `photo`, `version`, `updated_at`) VALUES
(2, 'Kanhaiya Bansal', 'Male', 'Friends', 'kLsc7vfCBDKjMJW3gxG0yC8dfHaM1MDB+hz5qO1XCgw=', 'IkJMbpmYIq3uGFTCFkEuwHfWYoK9URPxyqc+mc/e6kYVRCMlqogVA6a5hvc22UKD', 'HAgaxeTTtpMDAmPnOWzi0RpoH2omcnFiV30HgNpy9iAivTN4wIFbm7cyCCeyqEyB', NULL, 2, '2026-11-25 09:32:11'),
(3, 'Sujeet SIingh', 'Male', 'Friends', 'WpTurCcrGw8rVnqf09tSN0XsprE3QqSxVtVGHMOF4gY=', 'jbqaWiH5q6fgHc0D0jyHXLqaZpKEL+bnEgz5ZhuCjYZz1Ijl3JP9EoM+g3ijgFJV', 'pXZeL+TJxy1xs3tlj4wQ3Ai6w30cMPDuwVeR1wgLIwMRGpIQN8Y6d9c/Ob6+XJKx', NULL, 1, '2026-11-25 10:00:14'),
(4, 'Aarav Sharma', 'Male', 'Friends', '1klc3mAuH//243oc6A3njgDO0KodzTII1pEvAEy8a1o=', 'snV24l/KqCDeTh1gteUUZGPzgXPH6ii2U9Y0ngMfjhWZMvrI7HZ1xTPTM99utYYg', '3lePs6z4ZRh6b04JYhs8HxLzrwpQBXUY8Gb5MUQoGPoZfBW7w5XhrF/Hyn3pMfHP', NULL, 1, '2026-11-25 10:07:14'),
(5, 'Ananya Verma', 'Female', 'Family', 'EGcYokaVU5YX7dZtRhR0YjjmqN3Md5LJym/l/lY//rE=', 'Mgk/6vlRqlBv/ASUee7QTqastXBRkqLsQqMJh26FsYvjw26iF0RuyS0RdEb6jy06', 'vZRaKORMV/N+Q4n7pVfPw88q7EuNGBIaCrVa3A1bPgCV7bl0OlT67dtI4VF41EyN', NULL, 2, '2026-11-25 20:34:02'),
(6, 'Rohan Gupta', 'Male', 'Friends', 'G1jANgHsrBZpt8HkPsJjbys8IG0xZENZLt7gdcfj0ps=', 'fUjG4yL72/Mfkkg70p9XZds/ZXkoN116eg3uG/Rfl02Mt7LTiH9/vXXlbsS6sFku', 'R5DWfrDA5o6Dk6ljDQByOb/y/Hpf4R8KX0/FlhtQDwmte7Kyko5hbFrZ+eJtoR2x', NULL, 1, '2026-11-25 10:07:14'),
(7, 'Priya Tiwari', 'Female', 'Work', '6Bi2HIBCwc2zvsq9sTomiBjQQclalOiiEwTTLOl+hWs=', 'RHV4Y8MCRcxCliV2POYV098Q6lD6FVHhEEoDdIipUzkcgRWI7G5Z0atpXpT/ttyR', 'S/7zm0549eBtmRJrECapAsm3tUC5hHhBGr3iW8OJHXk+muYBJKlR4jBrthaHy0Yk', NULL, 2, '2026-11-25 20:33:44'),
(8, 'Vikram Singh', 'Male', 'Friends', 'zp3c8sjl3mKoKGjLjYWFNRr6aNjM8Mk0nm6nCU18huE=', 'h6WyixNGv3hz09ZRZmN4k4EIQA48OhzKywW5jGnVnCpYFTIAmomXLGOSRsP/ZsxM', '2RqdvK+XHoEtUzkVRmmdyfHYiqz/KcdFAU9Z04roYM2JV0NS1dObLHnPP0n00Ikl', NULL, 1, '2026-11-25 10:07:14'),
(9, 'Sneha Joshi', 'Female', 'Work', 'uZiyv/vflwfN71VPN1bLdfbFCsGomn4m6wEpHZYky0E=', 'gaSI1KDSPDi3SzCYPnRvGHFYJ5wMpr1sUS6b2jb5hedwMhB/v9U8x1fx7wkq0uCJ', '8C8z5mk+geDHnowYegTRtg3K1hU4sQvIVwT6WYf2GkVO5anZpbwgJtO0dZpi4bNN', NULL, 2, '2026-11-25 20:33:31'),
(10, 'Aditya Mishra', 'Male', 'Friends', 'Ru1tgK28BvXDwnYJn6FpspaWEj1Puh+RI/MexTZkeWE=', '6m/4Y7ysKIQOehhcFFfH8sqEWPpNC5QkumCNGEB8xwsfJQqnTfh0v4HiHsP7DjqO', 'etprIGkd/kfVO1eHPeqwpqLpPMkpr5LZvVYSnwVSRJ6WuvC1hF0q4bdXWj+74nkq', NULL, 1, '2026-11-25 10:07:14'),
(11, 'Kavya Dubey', 'Female', 'Friends', 'ltxgkL5E02+n5RgsuXWBbotZal+AyRC/9eIuEo8S8Dw=', 'kIHhZrtuKJ4Vq6uHDRh0Fyve5971eDdvzUP7VcUZiGg45RFzbjllmpAMGl3sr5vf', 'm7JhN3Lhf3kVyeAgxvovHFZceeW5gyO2SFZOxjyI7QinhrEDO9fMTS8R7wPc8jRk', NULL, 3, '2026-11-25 20:32:39'),
(12, 'Siddharth Jain', 'Male', 'Work', 'snbxCXoyTGa/xPgTWSOHlUMEKXtd3QipaZirVx11EYM=', 'uDvXCFE5S+g8r7DKqxvhakF1Fbiz5N9Dr0R+XKb74vJBue5IVI5ajjdBC6TutVZm', 'SkMAVSk3qWBAhykeyRiDE/CFC/B9Zt3GxebRL7MAtooCoa62F3TwRq6xNul45dXx', NULL, 2, '2026-11-25 20:33:19'),
(13, 'Neha Agarwal', 'Female', 'Family', '++t9tWdaUXMvWzgFNYyRAxBrmr+8k/j4uPpnjVdw53A=', 'MZcFtSMd9WNxKWk1Pd7p8ExWgvUchoezyJLNIT6RgsPxwC+pDkiY8Pg1PzHT4K85', 'MKufpyxoaQp44MyXhljwnqWXThwNaQPbrFOSjqziIl56Bz7XPkY4Gs1sJiLc/gpE', NULL, 2, '2026-11-25 20:33:11'),
(14, 'Karan Saxena', 'Male', 'Friends', 'lq9AuFLuJ5egE18+wWg2MgaqXGEjp6PUeTYhAGZlvjc=', 'bmfGvuOO4LCH73Ne8cKPkKX/foqlAwMUIDalx1QKnTiMbOxgTK/ZbcfO39YVA2Y3', 'P17Qk+bKAG17XaBRP4q1y0cSxtYHaaoTgmgC4Bc1b6bWlaqfBKeIjRSyiOM+9sz8', NULL, 1, '2026-11-25 10:07:14'),
(15, 'Pooja Chouhan', 'Female', 'Friends', 't7sBtcmqOJ8ADMKztrOBorMnpjzRpCshIgArtcPwS1w=', '8fVs09qzoF8+V5MEnzLu9ply1R/6e551Ueyg60qmC/smpXgj3ADtuhNvYnh/tFws', 'gSZrOgQ6TUwpaVkifIG2NHk4WwFB5QrNrcrWTQvEV50mH/WhioBTEbPsRw3rNrQw', NULL, 2, '2026-11-25 20:33:00'),
(16, 'Rahul Yadav', 'Male', 'Friends', 'dt/D07Y2aQHJnXcFa9hd5/FbkpNvp/DbzNe5D4dXPc4=', 'SFw8gJdCtS0nl/TLDumlSfqo4/nOOdzU5SiqM5xVW9lC4PHFSn69HL/G7Vk9yt2z', 'lFSn/Yv4U0+z9aN4deFlUPhN44SnATrTPLdbAontXGfHuN5ghFYbBBjFhEVCJwkp', NULL, 1, '2026-11-25 10:07:14'),
(17, 'Divya Rathore', 'Female', 'Friends', 'HkSMZR6Zej84s0+Sg/6MmaTUxpV214sKfd5ZqQ1PpBY=', '+w29fiXa5laOzc+pbySbo7anCwQn2JJXbCtSxgSGs22LsMSNs7c8rsXWJHnFhMCM', 'wlnw8Yiv/xRGOOlionQ/povu7QsKT0EaI604A5guLi9zYrVwVbmBrkXuDknJl+t4', NULL, 2, '2026-11-25 20:32:52'),
(18, 'Harshvardhan Tomar', 'Male', 'Friends', 'UUAHkknEW8du6CQorqmSuJ+zdAFD/iX1Dno7YJA8Kyc=', 'Qcj3TxCgrV60/5NjbsVwd2nejkoJLii/vh9u/++2Wg3O3Zzz+22UES9rUPGYjdf/', '2Gs9IhQadh7m4gLoZ/Z3DXlpbR6HSLypF1mXlqmyCmNsq2e9H3vaF1Wwt/G/OQdx', NULL, 1, '2026-11-25 10:07:14'),
(19, 'Nitin Mishra', 'Male', 'Friends', 'mhIIUpM7OvqC4eOTcGR3eaZBxRGtGazwR5JzXUH0ytw=', 'Y+cTSrOW/5HwRnqAwi6bo78KQ9cQJOTVNb+meP1mhHLHD+8m17VZIOWG5SoCR8iI', '5NYUGjKVEBVNyZUL6hP+KrMDw6dtMotVgWCSDbU8f+g=', NULL, 2, '2026-11-25 10:14:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
