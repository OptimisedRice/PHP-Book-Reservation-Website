-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2022 at 05:41 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `ISBN` varchar(12) NOT NULL,
  `BookTitle` varchar(50) NOT NULL,
  `Author` varchar(30) NOT NULL,
  `Edition` int(2) NOT NULL,
  `Year` int(4) NOT NULL,
  `CategoryID` int(3) NOT NULL,
  `Reserved` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`ISBN`, `BookTitle`, `Author`, `Edition`, `Year`, `CategoryID`, `Reserved`) VALUES
('093-403992', 'Computers in Business', 'Alicia Oneill', 3, 1997, 3, 'Y'),
('23472-8729', 'Exploring Peru', 'Stephanie Birchi', 4, 2005, 5, 'Y'),
('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, 2, 'N'),
('23u8-923849', 'A guide to nutrition', 'John Thorpe', 2, 1997, 1, 'N'),
('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, 7, 'Y'),
('82n8-308', 'computers for idiots', 'Susan O\'Neill', 5, 1998, 4, 'N'),
('9823-23984', 'My life in picture', 'Kevin Graham', 8, 2004, 1, 'N'),
('9823-2403-0', 'DaVinci Code', 'Dan Brown', 1, 2003, 8, 'N'),
('9823-98345', 'How to cook Italian food', 'Jamie Oliver', 2, 2005, 7, 'N'),
('9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, 2, 'N'),
('98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, 1, 'N'),
('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, 8, 'N'),
('993-004-00', 'My life in bits', 'John Smith', 1, 2001, 1, 'N'),
('9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, 1, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(3) NOT NULL,
  `CategoryDescription` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryDescription`) VALUES
(1, 'Health'),
(2, 'Business'),
(3, 'Biography'),
(4, 'Technology'),
(5, 'Travel'),
(6, 'Self-Help'),
(7, 'Cookery'),
(8, 'Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `ISBN` varchar(12) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `ReservedDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ISBN`, `Username`, `ReservedDate`) VALUES
('093-403992', 'alanjmckenna', '2021-12-10'),
('23472-8729', 'alanjmckenna', '2021-12-10'),
('2983-3494', 'alanjmckenna', '2021-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Username` varchar(30) NOT NULL,
  `Password` varchar(6) NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `AddressLine1` varchar(30) NOT NULL,
  `AddressLine2` varchar(30) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Telephone` int(10) NOT NULL,
  `Mobile` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Username`, `Password`, `FirstName`, `Surname`, `AddressLine1`, `AddressLine2`, `City`, `Telephone`, `Mobile`) VALUES
('alanjmckenna', 't1234s', 'Alan', 'Mckenna', '38 Cranley Road', 'Fairview', 'Dublin', 9998377, 856625567),
('alexmackey', '123456', 'Alex', 'Mackey', '5 Commons East', 'Drynam', 'Swords', 2147483647, 873391033),
('joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'Donnybrook', 'Dublin', 8887889, 876654456),
('kierans', 'kieran', 'kieran', 'gregg', '1 yellow walls', '', 'malahide', 865445678, 987890789),
('tommy100', '123456', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', 9983747, 876738782);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ISBN`),
  ADD UNIQUE KEY `ISBN` (`ISBN`),
  ADD KEY `CatID_FK` (`CategoryID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ISBN`),
  ADD KEY `User_FK` (`Username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Username`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `CatID_FK` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `ISBN_FK` FOREIGN KEY (`ISBN`) REFERENCES `books` (`ISBN`),
  ADD CONSTRAINT `User_FK` FOREIGN KEY (`Username`) REFERENCES `users` (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
