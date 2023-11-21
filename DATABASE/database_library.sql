-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2023 at 03:24 PM
-- Server version: 5.7.41-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aroayrma_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE `Admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `Admins`
--

INSERT INTO `Admins` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `BookBorrow`
--

CREATE TABLE `BookBorrow` (
  `id` int(11) NOT NULL,
  `reader_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `returned` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `BookBorrow`
--

INSERT INTO `BookBorrow` (`id`, `reader_id`, `book_id`, `borrow_date`, `due_date`, `returned`) VALUES
(22, 14, 2, '2023-11-08', '2023-11-15', 1),
(23, 14, 30, '2023-11-08', '2023-11-15', 1),
(24, 14, 2, '2023-11-17', '2023-11-15', 1),
(25, 14, 30, '2023-11-17', '2023-11-23', 1),
(26, 2, 2, '2023-11-17', '2023-11-22', 1),
(28, 2, 2, '2023-11-17', '2023-11-23', 0),
(29, 2, 2, '2023-11-17', '2023-11-23', 0),
(30, 14, 16, '2023-11-18', '0000-00-00', 0),
(31, 2, 16, '2023-11-18', '0000-00-00', 0),
(32, 14, 8, '2023-11-18', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `BookCategories`
--

CREATE TABLE `BookCategories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `BookCategories`
--

INSERT INTO `BookCategories` (`id`, `name`) VALUES
(1, 'Fiction'),
(2, 'Non-fiction'),
(3, 'Mystery'),
(4, 'Science Fiction'),
(5, 'Fantasy'),
(6, 'Romance'),
(7, 'Biography'),
(8, 'History'),
(9, 'Self-Help'),
(10, 'Psychology'),
(11, 'Travel'),
(12, 'Cookbooks'),
(13, 'Thriller'),
(14, 'Horror'),
(15, 'Science'),
(16, 'Philosophy'),
(17, 'Business'),
(18, 'Poetry'),
(19, 'Religion'),
(20, 'Art and Photography');

-- --------------------------------------------------------

--
-- Table structure for table `BookReturn`
--

CREATE TABLE `BookReturn` (
  `id` int(11) NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `fine` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `BookReturn`
--

INSERT INTO `BookReturn` (`id`, `borrow_id`, `return_date`, `fine`) VALUES
(12, 23, '2023-11-08', 0),
(13, 24, '2023-11-17', 10000),
(14, 25, '2023-11-17', 0),
(15, 22, '2023-11-17', 10000),
(16, 26, '2023-11-17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Books`
--

CREATE TABLE `Books` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `author` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `avail_copy` int(11) DEFAULT NULL,
  `img` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `Books`
--

INSERT INTO `Books` (`id`, `name`, `author`, `category_id`, `isbn`, `avail_copy`, `img`) VALUES
(1, 'To Kill a Mockingbird', 'Harper Lee', 1, '978-0061120084', 10, '1.jpg'),
(2, '1984', 'George Orwell', 1, '978-0451524935', 0, '2.jpg'),
(3, 'The Great Gatsby', 'F. Scott Fitzgerald', 1, '978-0743273565', 10, '3.jpg'),
(4, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 2, '978-0062316097', 10, '4.jpg'),
(5, 'The Immortal Life of Henrietta Lacks', 'Rebecca Skloot', 2, '978-1400052189', 10, '5.jpg'),
(6, 'The Girl with the Dragon Tattoo', 'Stieg Larsson', 3, '978-0307269751', 10, '6.jpg'),
(7, 'Gone Girl', 'Gillian Flynn', 3, '978-0307588371', 10, '7.jpg'),
(8, 'Dune', 'Frank Herbert', 4, '978-0441172719', 9, '8.jpg'),
(9, 'Ender\'s Game', 'Orson Scott Card', 4, '978-0812550702', 10, '9.jpg'),
(10, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 5, '978-0590353427', 10, '10.jpg'),
(11, 'The Hobbit', 'J.R.R. Tolkien', 5, '978-0345534835', 10, '11.jpg'),
(12, 'Pride and Prejudice', 'Jane Austen', 6, '978-0141439518', 10, '12.jpg'),
(13, 'Outlander', 'Diana Gabaldon', 6, '978-0385319959', 10, '13.jpg'),
(14, 'Steve Jobs', 'Walter Isaacson', 7, '978-1451648546', 10, '14.jpg'),
(15, 'The Diary of a Young Girl', 'Anne Frank', 7, '978-0553577129', 10, '15.jpg'),
(16, 'A People\'s History of the United States', 'Howard Zinn', 8, '978-0061965586', 8, '16.jpg'),
(17, 'The Guns of August', 'Barbara W. Tuchman', 8, '978-0345476098', 10, '17.jpg'),
(18, 'The Power of Habit', 'Charles Duhigg', 9, '978-0812981605', 10, '18.jpg'),
(19, 'The Subtle Art of Not Giving a F*ck', 'Mark Manson', 9, '978-0062457714', 10, '19.jpg'),
(20, 'Thinking, Fast and Slow', 'Daniel Kahneman', 10, '978-0374533557', 10, '20.jpg'),
(21, 'Man\'s Search for Meaning', 'Viktor E. Frankl', 10, '978-0807014271', 10, '21.jpg'),
(22, 'Into the Wild', 'Jon Krakauer', 11, '978-0385486804', 10, '22.jpg'),
(23, 'In a Sunburned Country', 'Bill Bryson', 11, '978-0767903868', 10, '23.jpg'),
(24, 'Joy of Cooking', 'Irma S. Rombauer', 12, '978-1501169717', 10, '24.jpg'),
(26, 'The Da Vinci Code', 'Dan Brown', 13, '978-0307474278', 10, '26.jpg'),
(27, 'The Girl on the Train', 'Paula Hawkins', 13, '978-1594634024', 10, '27.jpg'),
(28, 'The Shining', 'Stephen King', 14, '978-0307743657', 10, '28.jpg'),
(29, 'It', 'Stephen King', 14, '978-1501175466', 10, '29.jpg'),
(30, 'A Brief History of Time', 'Stephen Hawking', 15, '978-0553380163', 9, '30.jpg'),
(31, 'The Selfish Gene', 'Richard Dawkins', 15, '978-0198788607', 10, '31.jpg'),
(32, 'Meditations', 'Marcus Aurelius', 16, '978-0143036272', 10, '32.jpg'),
(33, 'Thus Spoke Zarathustra', 'Friedrich Nietzsche', 16, '978-1503292748', 10, '33.jpg'),
(34, 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 17, '978-1612680194', 10, '34.jpg'),
(35, 'Good to Great', 'Jim Collins', 17, '978-0066620992', 10, '35.jpg'),
(36, 'Selected Poems', 'Emily Dickinson', 18, '978-0486284669', 10, '36.jpg'),
(37, 'Leaves of Grass', 'Walt Whitman', 18, '978-0486419476', 10, '37.jpg'),
(38, 'The Bible', 'Various Authors', 19, '978-0310446479', 10, '38.jpg'),
(39, 'The Quran', 'Various Authors', 19, '978-0199535958', 10, '39.jpg'),
(40, 'The Art Book', 'Phaidon Press', 20, '978-0714867965', 10, '40.jpg'),
(41, 'Ansel Adams: 400 Photographs', 'Ansel Adams', 20, '978-0316117722', 10, '41.jpg'),
(43, 'The Pioneer Woman Cooks', 'Ree Drummond', 12, '978-0061658198', 10, '1695825909_65143ff527ab4_25.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Employees`
--

CREATE TABLE `Employees` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `Employees`
--

INSERT INTO `Employees` (`id`, `name`, `email`, `password`, `phone`) VALUES
(1, 'Vũ Lê Huy', 'huyle@gmail.com', '123456', '0966341727'),
(2, 'ANPhan', 'anphan@gmail.com', '123456', '0385256158');

-- --------------------------------------------------------

--
-- Table structure for table `Readers`
--

CREATE TABLE `Readers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `Readers`
--

INSERT INTO `Readers` (`id`, `name`, `email`, `phone`) VALUES
(2, 'Vũ Lê Huy', 'huyhuy@gmail.com', '0961656172'),
(14, 'Trần Khánh Huy 2', 'huyle@gmail.com', '0987235676');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admins`
--
ALTER TABLE `Admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `BookBorrow`
--
ALTER TABLE `BookBorrow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reader_id` (`reader_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `BookCategories`
--
ALTER TABLE `BookCategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `BookReturn`
--
ALTER TABLE `BookReturn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_id` (`borrow_id`);

--
-- Indexes for table `Books`
--
ALTER TABLE `Books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `Employees`
--
ALTER TABLE `Employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Readers`
--
ALTER TABLE `Readers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admins`
--
ALTER TABLE `Admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `BookBorrow`
--
ALTER TABLE `BookBorrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `BookCategories`
--
ALTER TABLE `BookCategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `BookReturn`
--
ALTER TABLE `BookReturn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Books`
--
ALTER TABLE `Books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `Employees`
--
ALTER TABLE `Employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Readers`
--
ALTER TABLE `Readers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `BookBorrow`
--
ALTER TABLE `BookBorrow`
  ADD CONSTRAINT `BookBorrow_ibfk_1` FOREIGN KEY (`reader_id`) REFERENCES `Readers` (`id`),
  ADD CONSTRAINT `BookBorrow_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `Books` (`id`);

--
-- Constraints for table `BookReturn`
--
ALTER TABLE `BookReturn`
  ADD CONSTRAINT `BookReturn_ibfk_1` FOREIGN KEY (`borrow_id`) REFERENCES `BookBorrow` (`id`);

--
-- Constraints for table `Books`
--
ALTER TABLE `Books`
  ADD CONSTRAINT `Books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `BookCategories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
