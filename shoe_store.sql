-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2020 at 04:14 AM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoe_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `back_invoices`
--

CREATE TABLE `back_invoices` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `number_of_products` int(11) NOT NULL,
  `total` double NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `back_invoice_items`
--

CREATE TABLE `back_invoice_items` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `back_code` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `product_code` int(11) NOT NULL,
  `number_of_items` int(11) NOT NULL,
  `details_id` varchar(255) NOT NULL,
  `back_type` int(11) DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `back_products`
--

CREATE TABLE `back_products` (
  `id` int(11) NOT NULL,
  `details_id` int(11) NOT NULL,
  `reason` int(11) NOT NULL,
  `description` text,
  `back` double NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `sales_item_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'احذية حريمي', 1, '2020-07-24 03:54:44', 1, '2020-07-24 03:55:37', NULL, NULL),
(2, 'شنط حريمي', 1, '2020-07-24 03:54:48', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `title`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'اسود', 1, '2020-07-24 03:32:50', NULL, NULL, NULL, NULL),
(2, 'ابيض', 1, '2020-07-24 03:32:54', 4, '2020-08-12 01:08:42', NULL, NULL),
(3, 'اخضر', 1, '2020-07-24 03:32:56', NULL, NULL, NULL, NULL),
(4, 'بني', 1, '2020-07-24 03:32:59', 1, '2020-07-24 03:33:15', NULL, NULL),
(5, 'اصفر', 1, '2020-07-24 03:33:01', NULL, NULL, NULL, NULL),
(6, 'ازرق', 1, '2020-07-24 03:33:06', NULL, NULL, NULL, NULL),
(7, 'موف', 1, '2020-07-29 20:45:57', NULL, NULL, NULL, NULL),
(8, '2برتقالي', 1, '2020-07-29 20:46:01', 1, '2020-07-29 20:46:07', 1, '2020-07-29 20:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 == Expense || 2 == Expense For Employee',
  `user_id` int(11) DEFAULT NULL,
  `shop_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `total` double NOT NULL,
  `description` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `permissions` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `title`, `permissions`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'المشرف العام', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'عامل', 'a:22:{i:0;s:11:\"list-colors\";i:1;s:9:\"add-color\";i:2;s:10:\"list-sizes\";i:3;s:15:\"list-categories\";i:4;s:13:\"list-products\";i:5;s:20:\"list-product-details\";i:6;s:20:\"view-product-details\";i:7;s:24:\"list-transfered-products\";i:8;s:22:\"add-transfered-product\";i:9;s:23:\"edit-transfered-product\";i:10;s:13:\"list-expenses\";i:11;s:11:\"add-expense\";i:12;s:19:\"list-sales-invoices\";i:13;s:17:\"add-sales-invoice\";i:14;s:19:\"print-sales-invoice\";i:15;s:25:\"switch-sales-invoice-item\";i:16;s:23:\"back-sales-invoice-item\";i:17;s:9:\"list-safe\";i:18;s:17:\"list-salesInvoice\";i:19;s:19:\"list-returnProducts\";i:20;s:21:\"list-switchedProducts\";i:21;s:19:\"list-expenseReports\";}', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'مشرف لفرع معين', 'a:47:{i:0;s:10:\"list-users\";i:1;s:9:\"edit-user\";i:2;s:8:\"add-user\";i:3;s:11:\"list-colors\";i:4;s:10:\"edit-color\";i:5;s:9:\"add-color\";i:6;s:10:\"list-sizes\";i:7;s:9:\"edit-size\";i:8;s:8:\"add-size\";i:9;s:15:\"list-categories\";i:10;s:14:\"add-shop-image\";i:11;s:17:\"delete-shop-image\";i:12;s:13:\"list-products\";i:13;s:12:\"edit-product\";i:14;s:19:\"edit-product-status\";i:15;s:11:\"add-product\";i:16;s:16:\"new-code-product\";i:17;s:14:\"delete-product\";i:18;s:17:\"add-product-image\";i:19;s:20:\"delete-product-image\";i:20;s:19:\"add-product-details\";i:21;s:20:\"list-product-details\";i:22;s:20:\"view-product-details\";i:23;s:24:\"list-transfered-products\";i:24;s:22:\"add-transfered-product\";i:25;s:23:\"edit-transfered-product\";i:26;s:14:\"list-suppliers\";i:27;s:12:\"add-supplier\";i:28;s:13:\"list-expenses\";i:29;s:12:\"edit-expense\";i:30;s:11:\"add-expense\";i:31;s:14:\"delete-expense\";i:32;s:13:\"list-salaries\";i:33;s:11:\"edit-salary\";i:34;s:19:\"list-sales-invoices\";i:35;s:18:\"edit-sales-invoice\";i:36;s:17:\"add-sales-invoice\";i:37;s:19:\"print-sales-invoice\";i:38;s:20:\"delete-sales-invoice\";i:39;s:25:\"switch-sales-invoice-item\";i:40;s:23:\"back-sales-invoice-item\";i:41;s:9:\"list-safe\";i:42;s:17:\"list-salesInvoice\";i:43;s:19:\"list-returnProducts\";i:44;s:21:\"list-switchedProducts\";i:45;s:19:\"list-expenseReports\";i:46;s:19:\"list-workersReports\";}', 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` int(11) NOT NULL,
  `factory_code` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `sell_price` double NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` int(11) NOT NULL,
  `supply_code` int(11) DEFAULT NULL,
  `shop_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 == Available || 1 == Sold || 2 == Restored || 3 == Restored For Problem || 4 == Back To Factory || 5 == Transfered to another shop',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `display_name` varchar(300) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL,
  `group_id` varchar(255) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `extra_rules` text,
  `address` varchar(255) NOT NULL,
  `gender` int(11) DEFAULT NULL COMMENT '1 > Male | 2 > Female',
  `start_date` date DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `first_name`, `last_name`, `display_name`, `phone`, `image`, `group_id`, `shop_id`, `extra_rules`, `address`, `gender`, `start_date`, `salary`, `username`) VALUES
(1, 1, 'Ahmed', 'Nabil', 'Ahmed Nabil', '1069273925', NULL, '1', NULL, NULL, 'San Francisco, California, USA', 1, NULL, NULL, NULL),
(3, 4, 'مشرف', 'ملوك', 'مشرف ملوك', '0123123123412', NULL, '3', 1, NULL, 'Melook Address', NULL, NULL, NULL, NULL),
(4, 5, 'احمد', 'نبيل', 'احمد نبيل', '10692739252', NULL, '2', 1, NULL, 'test2', NULL, '2020-06-01', 600, NULL),
(5, 6, 'مشرف', 'موضة', 'مشرف موضة', '1235125123', NULL, '3', 2, NULL, 'qeqweqwe', NULL, NULL, 0, NULL),
(6, 7, 'مشرف', 'ميليسا', 'مشرف ميليسا', NULL, NULL, '3', 3, NULL, '', NULL, NULL, 0, NULL),
(7, 8, 'عامل 1', 'ملوك', 'عامل 1 ملوك', NULL, NULL, '2', 1, NULL, '', NULL, '2020-07-01', 1500, NULL),
(8, 9, 'عامل 2', 'ملوك', 'عامل 2 ملوك', NULL, NULL, '2', 1, NULL, '', NULL, '2020-08-01', 1500, NULL),
(9, 10, 'عامل 1', 'موضة', 'عامل 1 موضة', NULL, NULL, '2', 2, NULL, '', NULL, '2020-08-01', 1500, NULL),
(10, 11, 'عامل 2', 'موضة', 'عامل 2 موضة', NULL, NULL, '2', 2, NULL, '', NULL, '2020-08-01', 1500, NULL),
(11, 12, 'عامل 1', 'ميليسا', 'عامل 1 ميليسا', NULL, NULL, '2', 3, NULL, '', NULL, '2020-08-01', 1500, NULL),
(12, 13, 'عامل 2', 'ميليسا', 'عامل 2 ميليسا', NULL, NULL, '2', 3, NULL, '', NULL, '2020-08-01', 1500, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `paid_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '1 == Not Paid || 2 == Paid',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice`
--

CREATE TABLE `sales_invoice` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `number_of_items` int(11) NOT NULL,
  `must_paid` double NOT NULL,
  `paid` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice_item`
--

CREATE TABLE `sales_invoice_item` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `details_id` varchar(255) NOT NULL,
  `product_code` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `paid` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `title`, `phone`, `address`, `image`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Mlook', '123123123', 'عنوان 1', 'mlook.jpeg', 1, '2020-07-24 04:36:03', 1, '2020-07-24 04:44:43', NULL, NULL),
(2, 'Moda', '1234125234', 'عنوان اخر موضة', 'moda.jpeg', 1, '2020-07-24 20:13:14', NULL, NULL, NULL, NULL),
(3, 'Melissa', '151352511', 'عنوان ميليسا', 'melissa.jpeg', 1, '2020-07-24 20:13:33', 1, '2020-07-29 20:26:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `title`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, '36', 1, '2020-07-24 03:36:37', 1, '2020-07-24 03:43:19', NULL, NULL),
(2, '37', 1, '2020-07-24 03:40:24', 1, '2020-07-24 03:43:26', NULL, NULL),
(3, '38', 1, '2020-07-24 03:40:26', 1, '2020-07-24 03:43:04', NULL, NULL),
(4, '39', 1, '2020-07-26 01:33:36', NULL, NULL, NULL, NULL),
(5, '40', 1, '2020-07-26 01:33:38', NULL, NULL, NULL, NULL),
(6, '41', 1, '2020-07-26 01:33:39', NULL, NULL, NULL, NULL),
(7, '42', 1, '2020-07-26 01:33:41', NULL, NULL, NULL, NULL),
(8, '43', 1, '2020-07-26 01:33:44', NULL, NULL, NULL, NULL),
(9, '44', 1, '2020-07-26 01:33:46', 1, '2020-07-26 01:34:03', 1, '2020-07-29 20:46:28'),
(10, '45', 1, '2020-07-26 01:34:09', NULL, NULL, 1, '2020-07-29 20:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `category_id` varchar(255) NOT NULL,
  `balance` int(11) DEFAULT '0',
  `is_active` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `category_id`, `balance`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'مورد 1', '1245123123123', 'طنطا', '3,2,1', 1500, 1, 1, '2020-07-24 22:13:51', 1, '2020-07-24 22:40:56', NULL, NULL),
(2, 'مورد 2', '12451231231', 'طنطا', '3,2,1', 0, 1, 1, '2020-07-24 22:13:51', 1, '2020-07-24 22:40:56', NULL, NULL),
(3, 'مورد 3', NULL, NULL, '1,2', NULL, 1, 1, '2020-08-10 22:15:52', NULL, NULL, NULL, NULL),
(6, 'مورد 4', NULL, NULL, '1', NULL, 1, 1, '2020-08-10 22:18:53', NULL, NULL, NULL, NULL),
(7, 'مورد 5', NULL, NULL, '2', NULL, 1, 1, '2020-08-10 22:19:17', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_budget`
--

CREATE TABLE `supplier_budget` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `total` double NOT NULL,
  `supply_code` int(11) DEFAULT NULL,
  `description` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supply_invoices`
--

CREATE TABLE `supply_invoices` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `number_of_products` int(11) NOT NULL,
  `total` double NOT NULL,
  `paid` int(11) NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supply_invoice_items`
--

CREATE TABLE `supply_invoice_items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` double NOT NULL,
  `supply_code` int(11) NOT NULL,
  `product_code` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `switch_operations`
--

CREATE TABLE `switch_operations` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_sales_item` int(11) NOT NULL,
  `new_sales_items` varchar(255) NOT NULL,
  `old_details_id` int(11) NOT NULL,
  `new_details_ids` varchar(255) NOT NULL,
  `paid` double DEFAULT NULL,
  `back` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transfered_products`
--

CREATE TABLE `transfered_products` (
  `id` int(11) NOT NULL,
  `details_id` int(11) NOT NULL,
  `product_code` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 == Transfered || 1 == Sold || 2 == Back',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `created_by` int(5) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(5) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(5) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `last_login`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Ahmed Nabil', 'admin@admin.com', '$2y$10$caE8gyd1bFxmNjMwRKeqpu.1ug7H/K4NepG5PCHPBl9n8emfWn8zu', 'dlSvpXTgH422sHOxopaCorRwfCd6TJaUkSCBvM5362lcPBgkdwHDOd9yNtnG', '2020-08-12 03:12:17', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'مشرف ملوك', 'melook@admin.com', '$2y$10$R8U2nZ0l8t03d.pJl06XReYKFJ6UyqTO1geNSOY76Ju/P4awjObIy', NULL, '2020-08-12 03:12:22', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'احمد نبيل', 'emp1@admin.com', '$2y$10$Zl7jQw0kT55qS75S8wdBgewr6uB94LTy6ZmdzD3qkdK1Xzy0BnQge', NULL, '2020-08-10 08:39:27', 1, NULL, NULL, NULL, NULL, 1, '2020-08-10 22:05:45'),
(6, 'مشرف موضة', 'moda@admin.com', '$2y$10$0vUA.dXuzgsl13epqd6iz.pFYWWaJyqpvBTN8N5hSyQyaGdfzh4nm', NULL, '2020-08-10 03:15:25', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'مشرف ميليسا', 'melissa@admin.com', '$2y$10$W/DX4HkZLEZm0RO3toggru0Tu.z0T5aFBZDoAnc3ogafP50kbjg6W', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'عامل 1 ملوك', 'worker1@admin.com', '$2y$10$Y0gWXOBLIS7lV7pVq7XmQuOYO8k3fpVTMrHSE6XGkoRWh.CmqMEsO', NULL, '2020-08-12 03:05:01', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'عامل 2 ملوك', 'worker2@admin.com', '$2y$10$emynbg.Jh0RTHrAj2eEvZeFRtCFxY1nQrcQbYjunJVZlCeYc8uZwu', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'عامل 1 موضة', 'worker3@admin.com', '$2y$10$E09jEBgIcsmfajdOvuid/uFirQ1q2k.Q/BWmEB4FuESTDezD3Mvke', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'عامل 2 موضة', 'worker4@admin.com', '$2y$10$GqBWAZhzBmTUz//p2b1r0uPH2D6OuLh0MLdXuMyn2w/JQB.ewJapu', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'عامل 1 ميليسا', 'worker5@admin.com', '$2y$10$GTa47M8IYceAqINbPcAVc.uC5FTtfTxzDHAzjgIzfC.EX/75vGfnW', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'عامل 2 ميليسا', 'worker6@admin.com', '$2y$10$y5Auayoq2Nk6ANyvpLaoK.9z1t9tOcY9W3w6iPuLrNK5ZHRwxG/r2', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variables`
--

CREATE TABLE `variables` (
  `id` int(11) NOT NULL,
  `var_key` varchar(255) NOT NULL,
  `var_value` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `variables`
--

INSERT INTO `variables` (`id`, `var_key`, `var_value`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(20, 'PAGINATION', '25', '2020-02-01 00:00:00', 1, NULL, NULL, NULL, NULL),
(21, 'Q', '12', '2020-07-24 02:59:09', 1, '2020-07-24 02:59:44', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `back_invoices`
--
ALTER TABLE `back_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_invoice_items`
--
ALTER TABLE `back_invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_products`
--
ALTER TABLE `back_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_invoice_item`
--
ALTER TABLE `sales_invoice_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_budget`
--
ALTER TABLE `supplier_budget`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_invoices`
--
ALTER TABLE `supply_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_invoice_items`
--
ALTER TABLE `supply_invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `switch_operations`
--
ALTER TABLE `switch_operations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfered_products`
--
ALTER TABLE `transfered_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variables`
--
ALTER TABLE `variables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `back_invoices`
--
ALTER TABLE `back_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `back_invoice_items`
--
ALTER TABLE `back_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `back_products`
--
ALTER TABLE `back_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_invoice_item`
--
ALTER TABLE `sales_invoice_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `supplier_budget`
--
ALTER TABLE `supplier_budget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supply_invoices`
--
ALTER TABLE `supply_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supply_invoice_items`
--
ALTER TABLE `supply_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `switch_operations`
--
ALTER TABLE `switch_operations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfered_products`
--
ALTER TABLE `transfered_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `variables`
--
ALTER TABLE `variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
