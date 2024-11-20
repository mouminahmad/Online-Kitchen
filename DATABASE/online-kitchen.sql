-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 10:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online-kitchen`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `username`, `password`) VALUES
(12, 'Administrator', 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `featured` varchar(10) NOT NULL,
  `active` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `image_name`, `featured`, `active`) VALUES
(15, 'Pakistani Food', 'Food_Category_324.png', 'Yes', 'Yes'),
(16, 'BBQ and Grill', 'Food_Category_650.png', 'Yes', 'Yes'),
(17, 'Desi Breakfast', 'Food_Category_621.png', 'Yes', 'Yes'),
(18, 'Snacks & Street Food', 'Food_Category_843.png', 'Yes', 'Yes'),
(19, 'Continental', 'Food_Category_587.png', 'Yes', 'Yes'),
(20, 'Desserts', 'Food_Category_48.png', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `isPaid` tinyint(1) DEFAULT 0,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  `voucher_status` varchar(20) DEFAULT 'Unverified',
  `shipping_name` varchar(100) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `shipping_phone` varchar(15) NOT NULL,
  `voucher_number` varchar(255) NOT NULL DEFAULT 'N/A',
  `total_cooking_time` int(11) DEFAULT 0,
  `shipping_charges` decimal(10,2) DEFAULT 0.00,
  `delivery_time` int(11) DEFAULT NULL,
  `voucher_file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`order_id`, `user_id`, `total_price`, `isPaid`, `order_date`, `status`, `voucher_status`, `shipping_name`, `shipping_address`, `shipping_phone`, `voucher_number`, `total_cooking_time`, `shipping_charges`, `delivery_time`, `voucher_file_path`) VALUES
(52, 25, 730.00, 0, '2024-11-19 18:43:34', 'Delivered', 'Unverified', 'Moumin Ahmad', 'Pakistan Lahore Dha Phase 5', '03047137578', 'OK-0611BF', 35, 250.00, 10, NULL),
(53, 27, 700.00, 0, '2024-11-20 07:21:08', 'Delivered', 'Unverified', 'sehar shakeeel', 'sialkot pakistan', '03000961766', 'N/A', 25, 0.00, NULL, NULL),
(54, 27, 700.00, 0, '2024-11-20 07:25:02', 'Delivered', 'Unverified', 'sehar shakeeel', 'sialkot pakistan', '03000961766', 'N/A', 25, 0.00, NULL, NULL),
(55, 27, 700.00, 0, '2024-11-20 07:28:53', 'Delivered', 'Verified', 'sehar shakeeel', 'sialkot Pakistan', '0300457758', 'OK-FBFD21', 25, 250.00, 350, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `checkout_items`
--

CREATE TABLE `checkout_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_cooking_time` int(11) DEFAULT 0,
  `product_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkout_items`
--

INSERT INTO `checkout_items` (`item_id`, `order_id`, `product_id`, `quantity`, `price`, `total_cooking_time`, `product_name`) VALUES
(88, 52, 63, 1, 350.00, 15, 'Biryani'),
(89, 52, 64, 1, 380.00, 20, 'Karahi'),
(90, 55, 67, 1, 350.00, 10, 'Paya'),
(91, 55, 63, 1, 350.00, 15, 'Biryani');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `featured` varchar(10) NOT NULL,
  `active` varchar(10) NOT NULL,
  `cooking_time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `title`, `description`, `price`, `image_name`, `category_id`, `featured`, `active`, `cooking_time`) VALUES
(63, 'Biryani', 'Aromatic rice cooked with spicy marinated meat and flavorful masala', 350.00, 'Food-Name-1629.png', 15, 'Yes', 'Yes', '15'),
(64, 'Karahi', 'A rich tomato-based curry cooked with tender meat and spices.', 380.00, 'Food-Name-4056.png', 15, 'Yes', 'Yes', '20'),
(65, 'Haleem', 'A slow-cooked blend of lentils, wheat, and meat with savory spices.', 250.00, 'Food-Name-7676.png', 15, 'Yes', 'Yes', '12'),
(66, 'Nihari', 'A slow-cooked stew with tender beef or mutton in a rich, flavorful gravy.', 400.00, 'Food-Name-7047.png', 15, 'No', 'Yes', '15'),
(67, 'Paya', 'A traditional soup-like dish made from goat or cow trotters with spicy gravy.', 350.00, 'Food-Name-1724.png', 15, 'No', 'Yes', '10'),
(68, 'Chapli Kabab', 'Spicy minced meat patties with aromatic herbs and spices, shallow-fried to perfection.', 100.00, 'Food-Name-5893.png', 15, 'No', 'Yes', '5'),
(69, 'Seekh Kabab', 'Minced meat mixed with spices, skewered, and grilled to juicy perfection.', 150.00, 'Food-Name-8328.png', 16, 'Yes', 'Yes', '5'),
(70, 'Chicken Tikka', 'Spiced and marinated chicken pieces grilled over an open flame.', 180.00, 'Food-Name-5940.png', 16, 'Yes', 'Yes', '6'),
(71, 'Malai Boti', 'Creamy, tender chicken cubes marinated in rich spices and cooked on skewers.', 230.00, 'Food-Name-7151.png', 16, 'Yes', 'Yes', '8'),
(72, 'Beef Boti', 'Spicy and smoky marinated beef chunks grilled to perfection.', 200.00, 'Food-Name-3846.png', 16, 'No', 'Yes', '12'),
(73, 'Fish Tikka', 'Tender fish fillets marinated with spices and grilled for a smoky flavor.', 300.00, 'Food-Name-987.png', 16, 'No', 'Yes', '15'),
(74, 'Lamb Chops', 'Juicy lamb chops marinated with a blend of spices and char-grilled.', 450.00, 'Food-Name-2328.png', 16, 'Yes', 'Yes', '15'),
(75, 'Halwa Puri', 'Soft, fluffy puris served with sweet halwa and spicy chickpea curry (chana).', 70.00, 'Food-Name-6938.png', 17, 'Yes', 'Yes', '5'),
(76, 'Paratha', 'Flaky, buttery flatbread, perfect with tea or a side of eggs.', 50.00, 'Food-Name-4328.png', 17, 'No', 'Yes', '5'),
(77, 'Aloo Bhujia', 'Flavorful chickpea curry seasoned with traditional spices, served with naan or paratha.', 70.00, 'Food-Name-3151.png', 17, 'No', 'Yes', '7'),
(78, 'Chana Masala', 'Flavorful chickpea curry seasoned with traditional spices, served with naan or paratha.', 100.00, 'Food-Name-9887.png', 17, 'No', 'Yes', '5'),
(79, 'Lassi', 'Refreshing yogurt-based drink, sweet or salty, ideal for starting the day.', 120.00, 'Food-Name-2449.png', 17, 'No', 'Yes', '5'),
(80, 'Samosa', 'Crispy triangular pastry filled with spicy potato or minced meat stuffing.', 70.00, 'Food-Name-5696.png', 18, 'Yes', 'Yes', '3'),
(81, 'Pakora', 'Deep-fried fritters made with gram flour and vegetables or meat.', 120.00, 'Food-Name-5576.png', 18, 'No', 'Yes', '5'),
(82, 'Chaat', 'A tangy and spicy mix of chickpeas, potatoes, yogurt, and chutneys.', 120.00, 'Food-Name-7020.png', 18, 'No', 'Yes', '5'),
(83, 'Golgappa (Pani Puri)', 'Crispy hollow puris filled with spicy tamarind water and savory stuffing.', 150.00, 'Food-Name-5785.png', 18, 'No', 'Yes', '5'),
(84, 'Dahi Bhalla', 'Soft lentil dumplings soaked in yogurt, topped with chutneys and spices.', 120.00, 'Food-Name-9918.png', 18, 'No', 'Yes', '5'),
(85, 'Aloo Tikki', 'Spicy mashed potato patties, shallow-fried to a golden crisp.', 50.00, 'Food-Name-1112.png', 18, 'No', 'Yes', '5'),
(86, 'Grilled Chicken Steak', 'Juicy grilled chicken served with herb butter and sautéed vegetables.', 700.00, 'Food-Name-1917.png', 19, 'No', 'Yes', '20'),
(87, 'Pizza', 'A cheesy delight with a crispy crust topped with vegetables, meat, and marinara sauce.', 1080.00, 'Food-Name-5577.png', 19, 'No', 'Yes', '15'),
(88, 'Club Sandwich', 'Multi-layered sandwich with chicken, eggs, lettuce, and mayonnaise.', 250.00, 'Food-Name-3658.png', 19, 'No', 'Yes', '5'),
(89, 'French Fries', 'Crispy, golden potato fries seasoned with salt and spices.', 200.00, 'Food-Name-3469.png', 19, 'No', 'Yes', '5'),
(90, 'Beef Burger', 'Juicy beef patty layered with lettuce, cheese, and sauces in a fresh bun.', 350.00, 'Food-Name-5567.png', 19, 'No', 'Yes', '7'),
(91, 'Kheer', 'Creamy rice pudding cooked with milk, sugar, and cardamom, garnished with nuts.', 300.00, 'Food-Name-3659.png', 20, 'Yes', 'Yes', '5'),
(92, 'Gulab Jamun', 'Soft, spongy milk-based dumplings soaked in a sweet sugar syrup.', 200.00, 'Food-Name-9252.png', 20, 'No', 'Yes', '5'),
(93, 'Jalebi', 'Crispy, golden spirals of fried batter soaked in sugar syrup.', 250.00, 'Food-Name-1544.png', 20, 'No', 'Yes', '5'),
(94, 'Fruit Custard', 'A creamy dessert made with custard and a mix of fresh fruits.', 350.00, 'Food-Name-8016.png', 20, 'Yes', 'Yes', '5');

-- --------------------------------------------------------

--
-- Table structure for table `food_requests`
--

CREATE TABLE `food_requests` (
  `id` int(11) NOT NULL,
  `dish_name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `user_feedback` text DEFAULT NULL,
  `status` enum('Pending','Reviewed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_requests`
--

INSERT INTO `food_requests` (`id`, `dish_name`, `picture`, `description`, `user_feedback`, `status`, `created_at`, `user_id`) VALUES
(10, 'biryani', 'dish_673cecd8c3f8f3.47816617.jpg', 'spicey biryany ', NULL, 'Pending', '2024-11-19 19:54:00', 25);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_contact` bigint(25) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `customer_name`, `customer_email`, `customer_contact`, `customer_address`, `created_at`) VALUES
(25, 'offical.moumin', '$2y$10$CkNZTQ75zpjBz8841SRQEe53or5cO3iEReDtQjBCYCqXsMAkmOvpO', 'Moumin Ahmad new', 'mominmughal0305@gmail.com', 3047137578, 'chaprar road mehdipur tehsil and destrict sialkot', '2024-11-04 15:47:51'),
(27, 'sehar', '$2y$10$L3pKd4Cg29U4cIN2UWpJvO5sjlkiGn2Wzhx3ZNzNtqYBnP6tsQpom', 'sehar Shakeel', 'sehar@gmail.com', 3047137578, 'chaprar road mehdipur tehsil and destrict sialkot', '2024-11-16 12:16:13'),
(28, 'talha', '$2y$10$d8pPcfZQx/WPO4v/HeFTe.7HViSU2heDcVcGEN22c1ZPvDuJ1Qs6u', 'Talha Amjad', 'talha@gmail.com', 3003440404, 'chaprar road mehdipur tehsil and destrict sialkot', '2024-11-16 12:29:28'),
(29, 'fahad', '$2y$10$zSUkQuuYYS7qvnMd1H7BauyU.Nhqk6OuBSGVUJkQbnlH8Y1Q41P9W', 'Fahad Amjad', 'fahad@gmail.com', 35503338383, 'pakistan lahore', '2024-11-16 12:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_uploads`
--

CREATE TABLE `voucher_uploads` (
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `voucher_image` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `checkout_items`
--
ALTER TABLE `checkout_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `checkout_items_ibfk_1` (`order_id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_requests`
--
ALTER TABLE `food_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `voucher_uploads`
--
ALTER TABLE `voucher_uploads`
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `checkout_items`
--
ALTER TABLE `checkout_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `food_requests`
--
ALTER TABLE `food_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `voucher_uploads`
--
ALTER TABLE `voucher_uploads`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkout_items`
--
ALTER TABLE `checkout_items`
  ADD CONSTRAINT `checkout_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `checkout` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_uploads`
--
ALTER TABLE `voucher_uploads`
  ADD CONSTRAINT `voucher_uploads_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `checkout` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
