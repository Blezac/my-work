-- Royal Ruby Exotic Place - Database Schema
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `royalruby_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `royalruby_db`;

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('superadmin','admin','editor') NOT NULL DEFAULT 'admin',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_name` VARCHAR(100) NOT NULL UNIQUE,
  `key_value` TEXT,
  `group_name` VARCHAR(50) NOT NULL DEFAULT 'general',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_key` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key_name`,`key_value`,`group_name`) VALUES
('site_name','Royal Ruby Exotic Place','general'),
('site_tagline','Elevating Brands. Maximizing Revenue. Delivering Premium Experiences.','general'),
('site_email','info@royalruby.com','general'),
('site_phone','+234 813 111 0871','general'),
('site_address','Gwarinpa, Abuja, Nigeria','general'),
('site_description','Royal Ruby Exotic Place is a premium Marketing and Business Consulting Firm specializing in brand elevation, revenue growth, and luxury hospitality experiences.','seo'),
('site_keywords','marketing consulting, business development, brand positioning, hospitality, Nigeria','seo'),
('hero_headline','Elevating Brands. Maximizing Revenue. Delivering Premium Experiences.','homepage'),
('hero_subheadline','We transform businesses through strategic marketing, elite consulting, and luxury hospitality solutions.','homepage'),
('about_intro','Royal Ruby Exotic Place stands at the intersection of strategic brilliance and luxury excellence. We partner with ambitious businesses to craft compelling brands, accelerate revenue, and deliver unforgettable premium experiences.','about'),
('facebook_url','#','social'),
('instagram_url','#','social'),
('linkedin_url','#','social'),
('twitter_url','#','social');

CREATE TABLE `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `icon` VARCHAR(80) NOT NULL DEFAULT 'bi-briefcase',
  `short_desc` VARCHAR(300) NOT NULL,
  `full_desc` TEXT NOT NULL,
  `benefits` TEXT COMMENT 'JSON array',
  `image` VARCHAR(255) DEFAULT NULL,
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`title`,`slug`,`icon`,`short_desc`,`full_desc`,`benefits`,`sort_order`,`is_featured`) VALUES
('Marketing & Client Acquisition','marketing-client-acquisition','bi-megaphone-fill','Precision-targeted marketing strategies that attract, convert, and retain high-value clients for sustainable growth.','Our Marketing & Client Acquisition service is built on deep market intelligence and data-driven strategy. We craft bespoke campaigns that resonate with your ideal client profile, deploy multi-channel acquisition funnels, and optimize conversion paths to maximize return on investment.','["Bespoke multi-channel campaigns","High-conversion acquisition funnels","Data analytics & performance tracking","Brand visibility & thought leadership","CRM strategy & implementation","ROI-focused budget optimization"]',1,1),
('Business Development','business-development','bi-graph-up-arrow','Strategic business development frameworks that open new markets, forge powerful partnerships, and drive sustainable expansion.','Royal Ruby Business Development practice helps organizations identify and capitalize on growth opportunities. We analyze market dynamics, competitive landscapes, and partnership potential to create actionable roadmaps.','["Market expansion & entry strategy","Strategic partnership development","Competitive landscape analysis","Revenue stream diversification","Investor relations support","Scalable growth roadmaps"]',2,1),
('Revenue Growth Strategies','revenue-growth-strategies','bi-currency-dollar','Comprehensive revenue optimization strategies that identify untapped opportunities and systematically grow your bottom line.','We engineer revenue growth through holistic examination of your business model, pricing architecture, customer lifetime value, and sales infrastructure. Our specialists implement proven growth levers that deliver measurable results.','["Pricing strategy & optimization","Sales process engineering","Customer lifetime value maximization","Upsell & cross-sell frameworks","Revenue leak identification","Performance-based growth metrics"]',3,1),
('Brand Positioning & Promotion','brand-positioning-promotion','bi-stars','Powerful brand positioning that establishes market authority, commands premium pricing, and creates lasting competitive advantage.','Royal Ruby Brand Positioning service crafts a compelling brand identity that communicates your unique value, resonates emotionally with your audience, and positions you as the authority in your market.','["Brand identity & visual design","Market positioning & differentiation","Messaging & storytelling frameworks","Promotional campaign strategy","Public relations & media placement","Brand equity measurement"]',4,1),
('Hospitality Consulting','hospitality-consulting','bi-gem','Premium hospitality consulting that transforms ordinary interactions into extraordinary experiences clients remember and return for.','Our Hospitality Consulting practice draws on deep expertise in luxury service design, event management, and premium experience curation. We help clients engineer protocols, staff training, and signature moments.','["Luxury service protocol design","Staff training & culture development","Event planning & management","Premium experience curation","Guest journey mapping","Hospitality brand standards"]',5,1);

CREATE TABLE `blog_posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL DEFAULT 1,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(210) NOT NULL UNIQUE,
  `excerpt` VARCHAR(400) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `category` VARCHAR(80) NOT NULL DEFAULT 'Business',
  `tags` VARCHAR(255) DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_desc` VARCHAR(300) DEFAULT NULL,
  `status` ENUM('published','draft','archived') NOT NULL DEFAULT 'draft',
  `views` INT UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` DATETIME DEFAULT NULL,
  `published_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_posts` (`title`,`slug`,`excerpt`,`content`,`category`,`status`,`published_at`) VALUES
('5 Revenue Growth Strategies Every Nigerian Business Needs in 2025','5-revenue-growth-strategies-2025','Discover the proven revenue acceleration frameworks transforming businesses across Nigeria.','<p>In today\'s competitive landscape, sustainable revenue growth requires more than ambition — it demands precision strategy, market intelligence, and disciplined execution.</p><p>At Royal Ruby Exotic Place, our consultants have identified five core growth levers that consistently deliver results for our clients across diverse industries.</p><h3>1. Customer Lifetime Value Optimization</h3><p>Most businesses obsess over acquisition while underinvesting in retention. Extending customer lifetime value by just 10% can increase profits by up to 30%.</p><h3>2. Pricing Architecture Redesign</h3><p>Pricing is the most powerful and underutilized growth lever available to most businesses. A strategic pricing review typically uncovers 15-25% revenue uplift opportunities.</p>','Business Growth','published',NOW()),
('The Art of Brand Positioning in a Luxury Market','art-brand-positioning-luxury','How premium brands command higher prices, build fierce loyalty, and create lasting competitive advantage.','<p>Luxury brand positioning is not about price — it is about perception, exclusivity, and emotional resonance. The world\'s most powerful brands have mastered the art of making their customers feel like members of an exclusive club.</p><p>Royal Ruby has helped dozens of Nigerian and international brands achieve this coveted positioning through strategic differentiation and compelling storytelling.</p>','Branding','published',NOW()),
('Hospitality Trends Shaping Premium Experiences in 2025','hospitality-trends-2025','From hyper-personalization to experiential luxury, explore the trends defining elite hospitality.','<p>The premium hospitality sector is undergoing a profound transformation. Clients no longer seek mere comfort — they seek meaning, memory, and exclusivity.</p><p>Our hospitality consulting practice has identified seven macro-trends that every premium hospitality operator must understand and respond to in 2025.</p>','Hospitality','published',NOW());

CREATE TABLE `testimonials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(100) NOT NULL,
  `client_title` VARCHAR(150) DEFAULT NULL,
  `company` VARCHAR(120) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `review` TEXT NOT NULL,
  `rating` TINYINT(1) NOT NULL DEFAULT 5,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` (`client_name`,`client_title`,`company`,`avatar`,`review`,`rating`) VALUES
('John Doe','CEO, Company A','Company A','https://example.com/avatar1.jpg','Great service! Highly recommended.',5);

CREATE TABLE `contacts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `subject` VARCHAR(200) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `is_replied` TINYINT(1) NOT NULL DEFAULT 0,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `category` ENUM('hospitality','events','office','team','general') NOT NULL DEFAULT 'general',
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `newsletter_subscribers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `name` VARCHAR(100) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `token` VARCHAR(64) DEFAULT NULL,
  `subscribed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `analytics_events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` VARCHAR(200) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(300) DEFAULT NULL,
  `referrer` VARCHAR(300) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_page` (`page`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;