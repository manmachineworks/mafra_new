
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `preorder_prepare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `preorders`
--

CREATE TABLE `preorders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_owner_id` int(11) DEFAULT NULL,
  `product_owner` varchar(100) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `order_code` varchar(255) NOT NULL,
  `subtotal` float NOT NULL,
  `grand_total` float DEFAULT NULL,
  `tax` float NOT NULL,
  `shipping_cost` float DEFAULT NULL,
  `shipping_discount` float DEFAULT NULL,
  `is_coupon_applied` tinyint(4) NOT NULL DEFAULT 0,
  `coupon_discount` float DEFAULT NULL,
  `product_discount` float DEFAULT 0,
  `prepayment` float DEFAULT NULL,
  `pickup_point_id` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` int(11) DEFAULT 0,
  `status` varchar(255) DEFAULT NULL,
  `refund_note` longtext DEFAULT NULL,
  `seller_refund_note` longtext DEFAULT NULL,
  `request_note` longtext DEFAULT NULL,
  `delivery_note` longtext DEFAULT NULL,
  `confirm_note` longtext DEFAULT NULL,
  `prepayment_note` longtext DEFAULT NULL,
  `final_oder_note` text DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `final_payment_proof` varchar(255) DEFAULT NULL,
  `final_payment_reference_no` varchar(255) DEFAULT NULL,
  `final_payment_confirm_note` varchar(255) DEFAULT NULL,
  `shipping_proof` varchar(255) DEFAULT NULL,
  `shipping_note` varchar(255) DEFAULT NULL,
  `refund_proof` varchar(255) DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  `request_preorder_status` int(1) NOT NULL DEFAULT 0 COMMENT '1=request\r\n2=accepted\r\n3=rejected',
  `request_preorder_time` timestamp NULL DEFAULT NULL,
  `prepayment_confirm_status` int(1) NOT NULL DEFAULT 0 COMMENT '1=request\r\n2=accepted\r\n3=rejected',
  `prepayment_confirmation_time` timestamp NULL DEFAULT NULL,
  `final_order_status` int(1) NOT NULL DEFAULT 0 COMMENT '1=request\r\n2=accepted\r\n3=rejected',
  `final_order_time` timestamp NULL DEFAULT NULL,
  `shipping_status` int(1) NOT NULL DEFAULT 0 COMMENT '1=request\r\n2=accepted\r\n3=rejected',
  `shipping_time` timestamp NULL DEFAULT NULL,
  `delivery_status` int(1) NOT NULL DEFAULT 0 COMMENT '1=request\r\n2=accepted\r\n3=rejected',
  `delivery_time` timestamp NULL DEFAULT NULL,
  `refund_status` int(1) NOT NULL DEFAULT 0 COMMENT '1=request\r\n2=accepted\r\n3=rejected',
  `is_viewed` tinyint(4) DEFAULT 0,
  `refund_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_cashondeliveries`
--

CREATE TABLE `preorder_cashondeliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `prepayment_needed` tinyint(4) DEFAULT NULL,
  `show_cod_note` tinyint(4) DEFAULT NULL,
  `note_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_conversation_messages`
--

CREATE TABLE `preorder_conversation_messages` (
  `id` int(11) NOT NULL,
  `preorder_conversation_thread_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `receiver_viewed` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_conversation_threads`
--

CREATE TABLE `preorder_conversation_threads` (
  `id` int(11) NOT NULL,
  `preorder_product_id` bigint(20) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_coupons`
--

CREATE TABLE `preorder_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `show_seller_coupon` tinyint(4) NOT NULL DEFAULT 0,
  `is_advance_coupon` tinyint(4) NOT NULL DEFAULT 0,
  `coupon_code` varchar(255) DEFAULT NULL,
  `coupon_start_date` int(11) DEFAULT NULL,
  `coupon_end_date` int(11) DEFAULT NULL,
  `coupon_amount` double DEFAULT NULL,
  `coupon_type` varchar(255) DEFAULT NULL,
  `coupon_benefits` varchar(255) DEFAULT NULL,
  `coupon_instructions` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_discounts`
--

CREATE TABLE `preorder_discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `after_preorder_discount_type` varchar(255) DEFAULT NULL,
  `after_preorder_discount_amount` double DEFAULT NULL,
  `direct_purchase_discount_type` varchar(255) DEFAULT NULL,
  `direct_purchase_discount_amount` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_discount_periods`
--

CREATE TABLE `preorder_discount_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `preorder_period_min_range` double DEFAULT NULL,
  `preorder_period_max_range` double DEFAULT NULL,
  `preorder_period_discount_type` varchar(255) DEFAULT NULL,
  `preorder_period_discount_amount` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_prepayments`
--

CREATE TABLE `preorder_prepayments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `prepayment_type` varchar(255) DEFAULT NULL,
  `prepayment_amount` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_products`
--

CREATE TABLE `preorder_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_slug` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `weight` double DEFAULT 0,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `tags` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `video_provider` varchar(255) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `pdf_specification` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `price_type` varchar(255) DEFAULT NULL,
  `unit_price` double NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(4) NOT NULL DEFAULT 1,
  `is_approved` tinyint(4) NOT NULL DEFAULT 1,
  `is_featured` tinyint(4) NOT NULL DEFAULT 0,
  `is_available` tinyint(4) DEFAULT NULL,
  `is_show_on_homepage` tinyint(4) NOT NULL DEFAULT 0,
  `campaign` varchar(255) DEFAULT NULL,
  `club_point` int(11) DEFAULT 0,
  `tax_type` varchar(255) DEFAULT NULL,
  `tax_amount` int(11) NOT NULL DEFAULT 0,
  `more_products` varchar(255) DEFAULT NULL,
  `frequently_bought_type` varchar(255) DEFAULT NULL,
  `frequently_bought_product` varchar(255) DEFAULT NULL,
  `frequently_bought_category` varchar(255) DEFAULT NULL,
  `discount_start_date` int(11) DEFAULT NULL,
  `discount_end_date` int(11) DEFAULT NULL,
  `available_date` date DEFAULT NULL,
  `availability_end_date` int(11) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `add_wholesale_price` tinyint(4) NOT NULL DEFAULT 0,
  `show_lead_time` tinyint(4) NOT NULL DEFAULT 0,
  `is_prepayment` tinyint(4) NOT NULL DEFAULT 0,
  `preorder_prepayment_id` int(11) DEFAULT NULL,
  `preorder_sample_order_id` int(11) DEFAULT NULL,
  `preorder_coupon_id` int(11) DEFAULT NULL,
  `preorder_refund_id` int(11) DEFAULT NULL,
  `preorder_cashondelivery_id` int(11) DEFAULT NULL,
  `preorder_discount_id` int(11) DEFAULT NULL,
  `preorder_shipping_id` int(11) DEFAULT NULL,
  `preorder_product_tax_id` int(11) DEFAULT NULL,
  `preorder_stock_id` int(11) DEFAULT NULL,
  `is_sample_order` tinyint(4) NOT NULL DEFAULT 0,
  `is_coupon` tinyint(4) NOT NULL DEFAULT 0,
  `is_Advance_discount` tinyint(4) NOT NULL DEFAULT 0,
  `is_refundable` tinyint(4) NOT NULL DEFAULT 0,
  `is_cod` tinyint(4) NOT NULL DEFAULT 0,
  `is_stock_visibility` tinyint(4) NOT NULL DEFAULT 0,
  `rating` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_product_categories`
--

CREATE TABLE `preorder_product_categories` (
  `preorder_product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_product_queries`
--

CREATE TABLE `preorder_product_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `question` longtext NOT NULL,
  `reply` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_product_reviews`
--

CREATE TABLE `preorder_product_reviews` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'real',
  `preorder_product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `custom_reviewer_name` varchar(100) DEFAULT NULL,
  `custom_reviewer_image` varchar(100) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `comment` mediumtext NOT NULL,
  `photos` varchar(191) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `viewed` int(1) NOT NULL DEFAULT 0,
  `created_at_is_custom` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_product_taxes`
--

CREATE TABLE `preorder_product_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `tax_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_product_translations`
--

CREATE TABLE `preorder_product_translations` (
  `id` bigint(20) NOT NULL,
  `preorder_product_id` bigint(20) NOT NULL,
  `product_name` varchar(200) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_refunds`
--

CREATE TABLE `preorder_refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `show_refund_note` tinyint(4) DEFAULT NULL,
  `note_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_sample_orders`
--

CREATE TABLE `preorder_sample_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `sample_price_type` varchar(255) DEFAULT NULL,
  `sample_price` double DEFAULT NULL,
  `delivery_day` int(11) DEFAULT NULL,
  `is_prepayment_nedded` tinyint(4) NOT NULL DEFAULT 0,
  `sample_order_prepayment_amount` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_shippings`
--

CREATE TABLE `preorder_shippings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `shipping_type` varchar(255) DEFAULT NULL,
  `shipping_time` int(11) DEFAULT NULL,
  `min_shipping_days` int(11) DEFAULT NULL,
  `max_shipping_days` int(11) DEFAULT NULL,
  `show_shipping_time` tinyint(4) DEFAULT NULL,
  `is_free_shipping` tinyint(4) DEFAULT 0,
  `is_flat_rate` tinyint(4) DEFAULT 0,
  `show_shipping_note` tinyint(4) DEFAULT NULL,
  `note_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_stocks`
--

CREATE TABLE `preorder_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `stock_visibility_state` varchar(255) DEFAULT NULL,
  `current_stock` int(11) DEFAULT NULL,
  `low_stock_stock` int(11) DEFAULT NULL,
  `is_low_stock_warning` tinyint(4) DEFAULT NULL,
  `is_custom_order_show` tinyint(4) DEFAULT NULL,
  `preorder_quantity` int(11) DEFAULT NULL,
  `final_order_quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preorder_wholesale_prices`
--

CREATE TABLE `preorder_wholesale_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preorder_product_id` int(11) NOT NULL,
  `wholesale_min_qty` int(11) DEFAULT NULL,
  `wholesale_max_qty` int(11) DEFAULT NULL,
  `wholesale_price` double DEFAULT NULL,
  `wholesale_lead_time` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `preorders`
--
ALTER TABLE `preorders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_cashondeliveries`
--
ALTER TABLE `preorder_cashondeliveries`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_cashondeliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_conversation_messages`
--
ALTER TABLE `preorder_conversation_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_conversation_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_conversation_threads`
--
ALTER TABLE `preorder_conversation_threads`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_conversation_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_coupons`
--
ALTER TABLE `preorder_coupons`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_discounts`
--
ALTER TABLE `preorder_discounts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_discount_periods`
--
ALTER TABLE `preorder_discount_periods`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_discount_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_prepayments`
--
ALTER TABLE `preorder_prepayments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_prepayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_products`
--
ALTER TABLE `preorder_products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_product_queries`
--
ALTER TABLE `preorder_product_queries`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_product_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_product_reviews`
--
ALTER TABLE `preorder_product_reviews`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_product_taxes`
--
ALTER TABLE `preorder_product_taxes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_product_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_product_translations`
--
ALTER TABLE `preorder_product_translations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_product_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_refunds`
--
ALTER TABLE `preorder_refunds`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_sample_orders`
--
ALTER TABLE `preorder_sample_orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_sample_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_shippings`
--
ALTER TABLE `preorder_shippings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_shippings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
--
-- Indexes for table `preorder_stocks`
--
ALTER TABLE `preorder_stocks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  

--
-- Indexes for table `preorder_wholesale_prices`
--
ALTER TABLE `preorder_wholesale_prices`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_wholesale_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  


-- Preorder Seller Commission
CREATE TABLE `preorder_commission_histories` (
  `id` int(11) NOT NULL,
  `preorder_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `admin_commission` double(25,2) NOT NULL,
  `seller_earning` double(25,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `preorder_commission_histories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `preorder_commission_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- FAQ
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` longtext NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- FAQ Translations
CREATE TABLE `faq_translations` (
  `id` int(11) NOT NULL,
  `faq_id` bigint(20) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` longtext NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `faq_translations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `faq_translations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `shops` 
ADD `preorder_product_upload_limit` INT NOT NULL DEFAULT '0' AFTER `product_upload_limit`,
ADD `preorder_request_instruction` LONGTEXT NULL AFTER `custom_followers`,
ADD `image_for_payment_qrcode` LONGTEXT NULL AFTER `preorder_request_instruction`,
ADD `pre_payment_instruction` LONGTEXT NULL AFTER `image_for_payment_qrcode`;

INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) VALUES
(null, 'image_for_faq_advertisement', null, NULL, '2024-10-30 05:18:28', '2024-10-30 05:18:28'),
(null, 'pre_payment_instruction', '', NULL, '2024-10-30 05:18:28', '2024-10-30 05:21:43');


-- Preorder notification Templates
INSERT INTO `notification_types` (`id`, `user_type`, `type`, `name`, `image`, `default_text`, `status`, `addon`, `created_at`, `updated_at`) VALUES
(null, 'admin', 'preorder_request_admin', 'Preorder Request', NULL, 'A new preorder [[order_code]] request has been received.\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_request_seller', 'Preorder Request', NULL, 'A new preorder [[order_code]] request has been received.\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_request_customer', 'Preorder Request', NULL, 'Your preorder [[order_code]] request has been placed.', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_request_accepted_admin', 'Preorder Request Accept', NULL, 'Preorder [[order_code]] request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_request_accepted_seller', 'Preorder Request Accept', NULL, 'Preorder [[order_code]] request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_request_accepted_customer', 'Preorder Request Accept', NULL, 'Your preorder [[order_code]] request has been accepted\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_request_denied_admin', 'Preorder Request Denied', NULL, 'Preorder [[order_code]] request has been denied\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_request_denied_seller', 'Preorder Request Denied', NULL, 'Preorder [[order_code]] request has been denied\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_request_denied_customer', 'Preorder Request Denied', NULL, 'Your Preorder [[order_code]] request has been denied\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_prepayment_request_admin', 'Preorder Prepayment Request', NULL, 'Preorder [[order_code]] prepayment request has been received.\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_prepayment_request_seller', 'Preorder Prepayment Request', NULL, 'Preorder [[order_code]] prepayment request has been received\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_prepayment_request_customer', 'Preorder Prepayment Request', NULL, 'Your preorder [[order_code]] prepayment request has been sent.', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_prepayment_request_accepted_admin', 'Preorder Prepayment Request Accept\r\n', NULL, 'Preorder [[order_code]] prepayment request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_prepayment_request_accepted_seller', 'Preorder Prepayment Request Accept\r\n', NULL, 'Preorder [[order_code]] prepayment request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_prepayment_request_accepted_customer', 'Preorder Prepayment Request Accept\r\n', NULL, 'Your preorder [[order_code]] prepayment request has been accepted.\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_prepayment_request_denied_admin', 'Preorder Prepayment Request Denied', NULL, 'Preorder [[order_code]] prepayment request has been denied', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_prepayment_request_denied_seller', 'Preorder Prepayment Request Denied', NULL, 'Preorder [[order_code]] prepayment request has been denied', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_prepayment_request_denied_customer', 'Preorder Prepayment Request Denied', NULL, 'Your preorder [[order_code]] prepayment request has been denied', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_prepayment_reminder_customer', 'Preorder Reminder for Prepayment', NULL, 'Prepayment for your preorder [[order_code]] is still not paid for. Kindly complete your payment\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_final_request_admin', 'Preorder Final Request', NULL, 'Preorder [[order_code]] final request request has been received\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_final_request_seller', 'Preorder Final Request', NULL, 'Preorder [[order_code]] final request request has been received\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_final_request_customer', 'Preorder Final Request', NULL, 'Preorder [[order_code]] final request request has been placed', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_final_request_accepted_admin', 'Final Preorder Accepted', NULL, 'Preorder [[order_code]] final request request has been accepted\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_final_request_accepted_seller', 'Final Preorder Accepted', NULL, 'Preorder [[order_code]] final request request has been accepted\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_final_request_accepted_customer', 'Final Preorder Accepted', NULL, 'Your preorder [[order_code]] final request request has been accepted\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_final_request_denied_admin', 'Final Preorder Denied', NULL, 'Preorder [[order_code]] final request request has been denied\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_final_request_denied_seller', 'Final Preorder Denied', NULL, 'Preorder [[order_code]] final request request has been denied\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_final_request_denied_customer', 'Final Preorder Denied', NULL, 'Your preorder [[order_code]] final request request has been denied\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_final_order_reminder_customer', 'Reminder for Final Preorder \r\n', NULL, 'The final order for your preorder [[order_code]] has not yet been completed. Please complete your final order at your earliest convenience.\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_in_shipping_admin', 'Preorder mark as shipping', NULL, 'Preorder [[order_code]] product is in shipping.\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_in_shipping_seller', 'Preorder mark as shipping', NULL, 'Preorder [[order_code]] product is in shipping.\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_in_shipping_customer', 'Preorder mark as shipping', NULL, 'Your preorder [[order_code]] product is in shipping.', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_shipping_cancelled_admin', 'Preorder Product shipping cancelled\r\n', NULL, 'Preorder [[order_code]] shipping has been cancelled', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_shipping_cancelled_seller', 'Preorder Product shipping cancelled\r\n', NULL, 'Preorder [[order_code]] shipping has been cancelled', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_shipping_cancelled_customer', 'Preorder Product shipping cancelled\r\n', NULL, 'Your preorder [[order_code]] shipping has been cancelled', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_delivered_admin', 'Preorder Product mark as Delivered\r\n', NULL, 'Preorder [[order_code]] has been delivered\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_delivered_seller', 'Preorder Product mark as Delivered\r\n', NULL, 'Preorder [[order_code]] has been delivered\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_delivered_customer', 'Preorder Product mark as Delivered\r\n', NULL, 'Your preorder [[order_code]] has been delivered', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_delivery_cancelled_admin', 'Preorder Product Delivery Cancelled', NULL, 'Preorder [[order_code]] delivery has been cancelled', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_delivery_cancelled_seller', 'Preorder Product Delivery Cancelled', NULL, 'Preorder [[order_code]] delivery has been cancelled', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_delivery_cancelled_customer', 'Preorder Product Delivery Cancelled', NULL, 'Your preorder [[order_code]] delivery has been cancelled\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_refund_request_admin', 'Preorder Product Refund Request', NULL, 'A preorder [[order_code]] refund request has been received\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_refund_request_seller', 'Preorder Product Refund Request', NULL, 'A preorder [[order_code]] refund request has been received\r\n', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_refund_request_customer', 'Preorder Product Refund Request', NULL, 'Your preorder [[order_code]] refund request has been placed', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_refund_accepted_admin', 'Preorder Product Refund Request Accept', NULL, 'Preorder [[order_code]] refund request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_refund_accepted_seller', 'Preorder Product Refund Request Accept', NULL, 'Preorder [[order_code]] refund request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_refund_accepted_customer', 'Preorder Product Refund Request Accept', NULL, 'Your Preorder [[order_code]] refund request has been accepted', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'admin', 'preorder_product_refund_denied_admin', 'Preorder Product Refund Request Denied', NULL, 'Preorder [[order_code]] refund request has been denied', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'seller', 'preorder_product_refund_denied_seller', 'Preorder Product Refund Request Denied', NULL, 'Preorder [[order_code]] refund request has been denied', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15'),
(null, 'customer', 'preorder_product_refund_denied_customer', 'Preorder Product Refund Request Denied', NULL, 'Your preorder [[order_code]] refund request has been denied', 0, 'preorder', '2024-11-07 11:53:15', '2024-11-07 11:53:15');


-- Staff Permissions
INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) 
VALUES
(NULL, 'preorder_dashboard', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_preorder_products', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_preorder_product', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'edit_preorder_product', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delete_preorder_product', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'update_preorder_product_status', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_preorders', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_inhouse_preorders', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_seller_preorders', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_delayed_prepayment_preorders', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delayed_prepayment_preorder_notification_send', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_final_preorders', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'final_preorder_notification_send', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_preorder_details', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'update_preorder_status', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delete_preorder', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'download_preorder_invoice', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_customer_preorder_history', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_preorder_seller_commission_history', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'preorder_settings', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_preorder_product_conversations', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_detail_preorder_product_conversation', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'reply_preorder_product_conversation', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delete_preorder_product_conversation', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_preorder_product_queries', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'reply_preorder_product_queries', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_preorder_product_reviews', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'update_preorder_product_review_status', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_faqs', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_faq', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'edit_faq', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delete_faq', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'update_faq_status', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_all_preorder_notification_types', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'edit_preorder_notification_type', 'preorder', 'web', current_timestamp(), current_timestamp()),
(NULL, 'update_preorder_notification_status', 'preorder', 'web', current_timestamp(), current_timestamp());



INSERT INTO `pages` (`id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_at`, `updated_at`) VALUES (NULL, 'preorder_terms_condition_page', 'Terms and Conditions for Preorder', 'preorder-terms-conditions', '<h5><strong>Terms and Conditions for Preorder Purchases</strong></h5><p>These Terms and Conditions govern your use of the preorder feature on [Your Store Name] (\"we,\" \"us,\" or \"our\") for all purchases made via the eCommerce website ([Your Website URL]). By making a preorder purchase, you agree to abide by these terms. Please read them carefully.</p><h5>1. <strong>Preorder Availability</strong></h5><ul><li>The preorder option allows customers to purchase products before they are officially released or back in stock.</li><li>All preorder items will be clearly marked as such on our website, along with the expected release or shipping date.</li></ul><h5>2. <strong>Preorder Payment</strong></h5><ul><li>When you place a preorder, you agree to pay for the product in full (or a deposit if specified).</li><li>Preorder payments are processed immediately at the time of purchase.</li><li>Payments are securely processed using our supported payment methods. If there is an issue with the payment, we will contact you to resolve the matter.</li></ul><h5>3. <strong>Delivery Timeframes</strong></h5><ul><li>The delivery time for preorder items may vary depending on the release date and availability.</li><li>We will provide an estimated shipping date when you place your preorder. However, this date may change due to delays from suppliers, production issues, or other unforeseen circumstances.</li><li>In the event of delays, we will notify you promptly with updated information.</li></ul><h5>4. <strong>Stock and Product Availability</strong></h5><ul><li>Preorders are based on estimated stock availability from our suppliers. If there are unforeseen changes in availability, we will contact you as soon as possible to discuss alternative options, such as a refund, store credit, or waiting for a restock.</li><li>If a preorder item is canceled by the manufacturer or supplier, we will notify you immediately and offer a full refund.</li></ul><h5>5. <strong>Refunds and Cancellations</strong></h5><ul><li>Preorders can be canceled by you at any time before the item is shipped. If canceled, a full refund will be issued.</li><li>Refunds will not be provided once the preorder has been shipped, except where required by law or in the case of defective or damaged products.</li><li>If you choose to cancel a preorder, please contact our support team at [Customer Service Email].</li></ul><h5>6. <strong>Price Changes</strong></h5><ul><li>The price of preorder items is subject to change. If the price increases before the official release or shipment, we will honor the price at the time the preorder was placed.</li><li>If the price decreases after your preorder, you will not be charged the reduced price unless explicitly stated otherwise.</li></ul><h5>7. <strong>Changes to Preorder Terms</strong></h5><ul><li>We reserve the right to modify or update these Terms and Conditions at any time. Any changes will be posted on this page, and the date of the most recent update will be noted at the top of this page.</li><li>By continuing to place preorders after any changes, you agree to the updated Terms and Conditions.</li></ul><h5>8. <strong>Product Returns and Exchanges</strong></h5><ul><li>Preorder items are subject to our standard return and exchange policy. If you are unhappy with the product after delivery, please review our [Return and Exchange Policy] to understand your rights and options.</li><li>If your preorder item arrives damaged or defective, please contact us within [X] days to request a return or exchange.</li></ul><h5>9. <strong>Limitations of Liability</strong></h5><ul><li>While we strive to ensure that all information on the website is accurate, we do not guarantee that all preorder product details, prices, or availability are free from errors. In the case of any discrepancies, we will notify you and work with you to resolve the issue.</li><li>Our liability for any loss or damage resulting from a preorder purchase is limited to the amount paid for the preorder.</li></ul><h5>10. <strong>Governing Law and Dispute Resolution</strong></h5><ul><li>These Terms and Conditions are governed by the laws of [Your Country/State].</li><li>Any disputes arising from preorder purchases will be resolved according to the dispute resolution methods outlined in our [General Terms and Conditions] or [Privacy Policy].</li></ul><h5>11. <strong>Customer Support</strong></h5><ul><li>For questions or concerns regarding your preorder, please contact our support team at [Customer Support Email] or call [Customer Support Phone Number].</li></ul>', 'Terms and Conditions for Preorder ', NULL, NULL, NULL, '2024-12-10 14:11:26', '2024-12-10 14:15:15');
ALTER TABLE `preorders` ADD `cod_for_final_order` TINYINT NULL DEFAULT '0' AFTER `final_payment_confirm_note`;
ALTER TABLE `preorders` ADD `cod_for_prepayment` TINYINT NULL DEFAULT '0' AFTER `prepayment_note`;
INSERT INTO `brands` (`id`, `name`, `logo`, `top`, `slug`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES (NULL, '[No Brand]', NULL, '0', 'no_brand', NULL, NULL, current_timestamp(), current_timestamp());
ALTER TABLE `preorder_products` CHANGE `is_available` `is_available` TINYINT(4) NULL DEFAULT '0';

COMMIT;