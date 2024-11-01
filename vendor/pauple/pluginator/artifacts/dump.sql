# WordPress MySQL database migration
#
# Generated: Thursday 19. November 2020 10:07 UTC
# Hostname: localhost
# Database: `local`
# URL: //127.0.0.1:82
# Path: C:\\Users\\essek\\Local Sites\\freshtest\\app\\public
# Tables: wp_commentmeta, wp_comments, wp_links, wp_options, wp_postmeta, wp_posts, wp_term_relationships, wp_term_taxonomy, wp_termmeta, wp_terms, wp_usermeta, wp_users
# Table Prefix: wp_
# Post Types: revision, helpie_faq, page, post
# Protocol: http
# Multisite: false
# Subsite Export: false
# --------------------------------------------------------

/*!40101 SET NAMES utf8 */;

SET sql_mode='NO_AUTO_VALUE_ON_ZERO';



#
# Delete any existing table `wp_commentmeta`
#

DROP TABLE IF EXISTS `wp_commentmeta`;


#
# Table structure of table `wp_commentmeta`
#

CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_commentmeta`
#

#
# End of data contents of table `wp_commentmeta`
# --------------------------------------------------------



#
# Delete any existing table `wp_comments`
#

DROP TABLE IF EXISTS `wp_comments`;


#
# Table structure of table `wp_comments`
#

CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_comments`
#
INSERT INTO `wp_comments` ( `comment_ID`, `comment_post_ID`, `comment_author`, `comment_author_email`, `comment_author_url`, `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`, `comment_agent`, `comment_type`, `comment_parent`, `user_id`) VALUES
(1, 1, 'A WordPress Commenter', 'wapuu@wordpress.example', 'https://wordpress.org/', '', '2020-11-19 09:56:39', '2020-11-19 09:56:39', 'Hi, this is a comment.\nTo get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.\nCommenter avatars come from <a href="https://gravatar.com">Gravatar</a>.', 0, '1', '', 'comment', 0, 0) ;

#
# End of data contents of table `wp_comments`
# --------------------------------------------------------



#
# Delete any existing table `wp_links`
#

DROP TABLE IF EXISTS `wp_links`;


#
# Table structure of table `wp_links`
#

CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_links`
#

#
# End of data contents of table `wp_links`
# --------------------------------------------------------



#
# Delete any existing table `wp_options`
#

DROP TABLE IF EXISTS `wp_options`;


#
# Table structure of table `wp_options`
#

CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_options`
#
INSERT INTO `wp_options` ( `option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://127.0.0.1:82', 'yes'),
(2, 'home', 'http://127.0.0.1:82', 'yes'),
(3, 'blogname', 'Test Site', 'yes'),
(4, 'blogdescription', 'Just another WordPress site', 'yes'),
(5, 'users_can_register', '0', 'yes'),
(6, 'admin_email', 'admin@gmail.com', 'yes'),
(7, 'start_of_week', '1', 'yes'),
(8, 'use_balanceTags', '0', 'yes'),
(9, 'use_smilies', '1', 'yes'),
(10, 'require_name_email', '1', 'yes'),
(11, 'comments_notify', '1', 'yes'),
(12, 'posts_per_rss', '10', 'yes'),
(13, 'rss_use_excerpt', '0', 'yes'),
(14, 'mailserver_url', 'mail.example.com', 'yes'),
(15, 'mailserver_login', 'login@example.com', 'yes'),
(16, 'mailserver_pass', 'password', 'yes'),
(17, 'mailserver_port', '110', 'yes'),
(18, 'default_category', '1', 'yes'),
(19, 'default_comment_status', 'open', 'yes'),
(20, 'default_ping_status', 'open', 'yes'),
(21, 'default_pingback_flag', '0', 'yes'),
(22, 'posts_per_page', '10', 'yes'),
(23, 'date_format', 'F j, Y', 'yes'),
(24, 'time_format', 'g:i a', 'yes'),
(25, 'links_updated_date_format', 'F j, Y g:i a', 'yes'),
(26, 'comment_moderation', '0', 'yes'),
(27, 'moderation_notify', '1', 'yes'),
(28, 'permalink_structure', '/%year%/%monthnum%/%day%/%postname%/', 'yes'),
(29, 'rewrite_rules', 'a:129:{s:11:"^wp-json/?$";s:22:"index.php?rest_route=/";s:14:"^wp-json/(.*)?";s:33:"index.php?rest_route=/$matches[1]";s:21:"^index.php/wp-json/?$";s:22:"index.php?rest_route=/";s:24:"^index.php/wp-json/(.*)?";s:33:"index.php?rest_route=/$matches[1]";s:17:"^wp-sitemap\\.xml$";s:23:"index.php?sitemap=index";s:17:"^wp-sitemap\\.xsl$";s:36:"index.php?sitemap-stylesheet=sitemap";s:23:"^wp-sitemap-index\\.xsl$";s:34:"index.php?sitemap-stylesheet=index";s:48:"^wp-sitemap-([a-z]+?)-([a-z\\d_-]+?)-(\\d+?)\\.xml$";s:75:"index.php?sitemap=$matches[1]&sitemap-subtype=$matches[2]&paged=$matches[3]";s:34:"^wp-sitemap-([a-z]+?)-(\\d+?)\\.xml$";s:47:"index.php?sitemap=$matches[1]&paged=$matches[2]";s:13:"helpie_faq/?$";s:30:"index.php?post_type=helpie_faq";s:43:"helpie_faq/feed/(feed|rdf|rss|rss2|atom)/?$";s:47:"index.php?post_type=helpie_faq&feed=$matches[1]";s:38:"helpie_faq/(feed|rdf|rss|rss2|atom)/?$";s:47:"index.php?post_type=helpie_faq&feed=$matches[1]";s:30:"helpie_faq/page/([0-9]{1,})/?$";s:48:"index.php?post_type=helpie_faq&paged=$matches[1]";s:47:"category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$";s:52:"index.php?category_name=$matches[1]&feed=$matches[2]";s:42:"category/(.+?)/(feed|rdf|rss|rss2|atom)/?$";s:52:"index.php?category_name=$matches[1]&feed=$matches[2]";s:23:"category/(.+?)/embed/?$";s:46:"index.php?category_name=$matches[1]&embed=true";s:35:"category/(.+?)/page/?([0-9]{1,})/?$";s:53:"index.php?category_name=$matches[1]&paged=$matches[2]";s:17:"category/(.+?)/?$";s:35:"index.php?category_name=$matches[1]";s:44:"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:42:"index.php?tag=$matches[1]&feed=$matches[2]";s:39:"tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:42:"index.php?tag=$matches[1]&feed=$matches[2]";s:20:"tag/([^/]+)/embed/?$";s:36:"index.php?tag=$matches[1]&embed=true";s:32:"tag/([^/]+)/page/?([0-9]{1,})/?$";s:43:"index.php?tag=$matches[1]&paged=$matches[2]";s:14:"tag/([^/]+)/?$";s:25:"index.php?tag=$matches[1]";s:45:"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:50:"index.php?post_format=$matches[1]&feed=$matches[2]";s:40:"type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:50:"index.php?post_format=$matches[1]&feed=$matches[2]";s:21:"type/([^/]+)/embed/?$";s:44:"index.php?post_format=$matches[1]&embed=true";s:33:"type/([^/]+)/page/?([0-9]{1,})/?$";s:51:"index.php?post_format=$matches[1]&paged=$matches[2]";s:15:"type/([^/]+)/?$";s:33:"index.php?post_format=$matches[1]";s:38:"helpie_faq/[^/]+/attachment/([^/]+)/?$";s:32:"index.php?attachment=$matches[1]";s:48:"helpie_faq/[^/]+/attachment/([^/]+)/trackback/?$";s:37:"index.php?attachment=$matches[1]&tb=1";s:68:"helpie_faq/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:63:"helpie_faq/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:63:"helpie_faq/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$";s:50:"index.php?attachment=$matches[1]&cpage=$matches[2]";s:44:"helpie_faq/[^/]+/attachment/([^/]+)/embed/?$";s:43:"index.php?attachment=$matches[1]&embed=true";s:27:"helpie_faq/([^/]+)/embed/?$";s:43:"index.php?helpie_faq=$matches[1]&embed=true";s:31:"helpie_faq/([^/]+)/trackback/?$";s:37:"index.php?helpie_faq=$matches[1]&tb=1";s:51:"helpie_faq/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?helpie_faq=$matches[1]&feed=$matches[2]";s:46:"helpie_faq/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?helpie_faq=$matches[1]&feed=$matches[2]";s:39:"helpie_faq/([^/]+)/page/?([0-9]{1,})/?$";s:50:"index.php?helpie_faq=$matches[1]&paged=$matches[2]";s:46:"helpie_faq/([^/]+)/comment-page-([0-9]{1,})/?$";s:50:"index.php?helpie_faq=$matches[1]&cpage=$matches[2]";s:35:"helpie_faq/([^/]+)(?:/([0-9]+))?/?$";s:49:"index.php?helpie_faq=$matches[1]&page=$matches[2]";s:27:"helpie_faq/[^/]+/([^/]+)/?$";s:32:"index.php?attachment=$matches[1]";s:37:"helpie_faq/[^/]+/([^/]+)/trackback/?$";s:37:"index.php?attachment=$matches[1]&tb=1";s:57:"helpie_faq/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:52:"helpie_faq/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:52:"helpie_faq/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$";s:50:"index.php?attachment=$matches[1]&cpage=$matches[2]";s:33:"helpie_faq/[^/]+/([^/]+)/embed/?$";s:43:"index.php?attachment=$matches[1]&embed=true";s:60:"helpie_faq_category/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:58:"index.php?helpie_faq_category=$matches[1]&feed=$matches[2]";s:55:"helpie_faq_category/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:58:"index.php?helpie_faq_category=$matches[1]&feed=$matches[2]";s:36:"helpie_faq_category/([^/]+)/embed/?$";s:52:"index.php?helpie_faq_category=$matches[1]&embed=true";s:48:"helpie_faq_category/([^/]+)/page/?([0-9]{1,})/?$";s:59:"index.php?helpie_faq_category=$matches[1]&paged=$matches[2]";s:30:"helpie_faq_category/([^/]+)/?$";s:41:"index.php?helpie_faq_category=$matches[1]";s:57:"helpie_faq_group/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:55:"index.php?helpie_faq_group=$matches[1]&feed=$matches[2]";s:52:"helpie_faq_group/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:55:"index.php?helpie_faq_group=$matches[1]&feed=$matches[2]";s:33:"helpie_faq_group/([^/]+)/embed/?$";s:49:"index.php?helpie_faq_group=$matches[1]&embed=true";s:45:"helpie_faq_group/([^/]+)/page/?([0-9]{1,})/?$";s:56:"index.php?helpie_faq_group=$matches[1]&paged=$matches[2]";s:27:"helpie_faq_group/([^/]+)/?$";s:38:"index.php?helpie_faq_group=$matches[1]";s:12:"robots\\.txt$";s:18:"index.php?robots=1";s:13:"favicon\\.ico$";s:19:"index.php?favicon=1";s:48:".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$";s:18:"index.php?feed=old";s:20:".*wp-app\\.php(/.*)?$";s:19:"index.php?error=403";s:18:".*wp-register.php$";s:23:"index.php?register=true";s:32:"feed/(feed|rdf|rss|rss2|atom)/?$";s:27:"index.php?&feed=$matches[1]";s:27:"(feed|rdf|rss|rss2|atom)/?$";s:27:"index.php?&feed=$matches[1]";s:8:"embed/?$";s:21:"index.php?&embed=true";s:20:"page/?([0-9]{1,})/?$";s:28:"index.php?&paged=$matches[1]";s:41:"comments/feed/(feed|rdf|rss|rss2|atom)/?$";s:42:"index.php?&feed=$matches[1]&withcomments=1";s:36:"comments/(feed|rdf|rss|rss2|atom)/?$";s:42:"index.php?&feed=$matches[1]&withcomments=1";s:17:"comments/embed/?$";s:21:"index.php?&embed=true";s:44:"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:40:"index.php?s=$matches[1]&feed=$matches[2]";s:39:"search/(.+)/(feed|rdf|rss|rss2|atom)/?$";s:40:"index.php?s=$matches[1]&feed=$matches[2]";s:20:"search/(.+)/embed/?$";s:34:"index.php?s=$matches[1]&embed=true";s:32:"search/(.+)/page/?([0-9]{1,})/?$";s:41:"index.php?s=$matches[1]&paged=$matches[2]";s:14:"search/(.+)/?$";s:23:"index.php?s=$matches[1]";s:47:"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:50:"index.php?author_name=$matches[1]&feed=$matches[2]";s:42:"author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:50:"index.php?author_name=$matches[1]&feed=$matches[2]";s:23:"author/([^/]+)/embed/?$";s:44:"index.php?author_name=$matches[1]&embed=true";s:35:"author/([^/]+)/page/?([0-9]{1,})/?$";s:51:"index.php?author_name=$matches[1]&paged=$matches[2]";s:17:"author/([^/]+)/?$";s:33:"index.php?author_name=$matches[1]";s:69:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$";s:80:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]";s:64:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$";s:80:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]";s:45:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$";s:74:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true";s:57:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$";s:81:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]";s:39:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$";s:63:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]";s:56:"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$";s:64:"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]";s:51:"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$";s:64:"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]";s:32:"([0-9]{4})/([0-9]{1,2})/embed/?$";s:58:"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true";s:44:"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$";s:65:"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]";s:26:"([0-9]{4})/([0-9]{1,2})/?$";s:47:"index.php?year=$matches[1]&monthnum=$matches[2]";s:43:"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$";s:43:"index.php?year=$matches[1]&feed=$matches[2]";s:38:"([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$";s:43:"index.php?year=$matches[1]&feed=$matches[2]";s:19:"([0-9]{4})/embed/?$";s:37:"index.php?year=$matches[1]&embed=true";s:31:"([0-9]{4})/page/?([0-9]{1,})/?$";s:44:"index.php?year=$matches[1]&paged=$matches[2]";s:13:"([0-9]{4})/?$";s:26:"index.php?year=$matches[1]";s:58:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/?$";s:32:"index.php?attachment=$matches[1]";s:68:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/trackback/?$";s:37:"index.php?attachment=$matches[1]&tb=1";s:88:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:83:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:83:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$";s:50:"index.php?attachment=$matches[1]&cpage=$matches[2]";s:64:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/embed/?$";s:43:"index.php?attachment=$matches[1]&embed=true";s:53:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/embed/?$";s:91:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&embed=true";s:57:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/trackback/?$";s:85:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&tb=1";s:77:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:97:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]";s:72:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:97:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]";s:65:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/page/?([0-9]{1,})/?$";s:98:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&paged=$matches[5]";s:72:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/comment-page-([0-9]{1,})/?$";s:98:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&cpage=$matches[5]";s:61:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)(?:/([0-9]+))?/?$";s:97:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&page=$matches[5]";s:47:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/?$";s:32:"index.php?attachment=$matches[1]";s:57:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/trackback/?$";s:37:"index.php?attachment=$matches[1]&tb=1";s:77:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:72:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:72:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$";s:50:"index.php?attachment=$matches[1]&cpage=$matches[2]";s:53:"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/embed/?$";s:43:"index.php?attachment=$matches[1]&embed=true";s:64:"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/comment-page-([0-9]{1,})/?$";s:81:"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&cpage=$matches[4]";s:51:"([0-9]{4})/([0-9]{1,2})/comment-page-([0-9]{1,})/?$";s:65:"index.php?year=$matches[1]&monthnum=$matches[2]&cpage=$matches[3]";s:38:"([0-9]{4})/comment-page-([0-9]{1,})/?$";s:44:"index.php?year=$matches[1]&cpage=$matches[2]";s:27:".?.+?/attachment/([^/]+)/?$";s:32:"index.php?attachment=$matches[1]";s:37:".?.+?/attachment/([^/]+)/trackback/?$";s:37:"index.php?attachment=$matches[1]&tb=1";s:57:".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:52:".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$";s:49:"index.php?attachment=$matches[1]&feed=$matches[2]";s:52:".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$";s:50:"index.php?attachment=$matches[1]&cpage=$matches[2]";s:33:".?.+?/attachment/([^/]+)/embed/?$";s:43:"index.php?attachment=$matches[1]&embed=true";s:16:"(.?.+?)/embed/?$";s:41:"index.php?pagename=$matches[1]&embed=true";s:20:"(.?.+?)/trackback/?$";s:35:"index.php?pagename=$matches[1]&tb=1";s:40:"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$";s:47:"index.php?pagename=$matches[1]&feed=$matches[2]";s:35:"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$";s:47:"index.php?pagename=$matches[1]&feed=$matches[2]";s:28:"(.?.+?)/page/?([0-9]{1,})/?$";s:48:"index.php?pagename=$matches[1]&paged=$matches[2]";s:35:"(.?.+?)/comment-page-([0-9]{1,})/?$";s:48:"index.php?pagename=$matches[1]&cpage=$matches[2]";s:24:"(.?.+?)(?:/([0-9]+))?/?$";s:47:"index.php?pagename=$matches[1]&page=$matches[2]";}', 'yes'),
(30, 'hack_file', '0', 'yes'),
(31, 'blog_charset', 'UTF-8', 'yes'),
(32, 'moderation_keys', '', 'no'),
(33, 'active_plugins', 'a:2:{i:0;s:25:"helpie-faq/helpie-faq.php";i:1;s:31:"wp-migrate-db/wp-migrate-db.php";}', 'yes'),
(34, 'category_base', '', 'yes'),
(35, 'ping_sites', 'http://rpc.pingomatic.com/', 'yes'),
(36, 'comment_max_links', '2', 'yes'),
(37, 'gmt_offset', '0', 'yes'),
(38, 'default_email_category', '1', 'yes'),
(39, 'recently_edited', '', 'no'),
(40, 'template', 'twentytwenty', 'yes'),
(41, 'stylesheet', 'twentytwenty', 'yes'),
(42, 'comment_registration', '0', 'yes'),
(43, 'html_type', 'text/html', 'yes'),
(44, 'use_trackback', '0', 'yes'),
(45, 'default_role', 'subscriber', 'yes'),
(46, 'db_version', '48748', 'yes'),
(47, 'uploads_use_yearmonth_folders', '1', 'yes'),
(48, 'upload_path', '', 'yes'),
(49, 'blog_public', '0', 'yes'),
(50, 'default_link_category', '2', 'yes'),
(51, 'show_on_front', 'posts', 'yes'),
(52, 'tag_base', '', 'yes'),
(53, 'show_avatars', '1', 'yes'),
(54, 'avatar_rating', 'G', 'yes'),
(55, 'upload_url_path', '', 'yes'),
(56, 'thumbnail_size_w', '150', 'yes'),
(57, 'thumbnail_size_h', '150', 'yes'),
(58, 'thumbnail_crop', '1', 'yes'),
(59, 'medium_size_w', '300', 'yes'),
(60, 'medium_size_h', '300', 'yes'),
(61, 'avatar_default', 'mystery', 'yes'),
(62, 'large_size_w', '1024', 'yes'),
(63, 'large_size_h', '1024', 'yes'),
(64, 'image_default_link_type', 'none', 'yes'),
(65, 'image_default_size', '', 'yes'),
(66, 'image_default_align', '', 'yes'),
(67, 'close_comments_for_old_posts', '0', 'yes'),
(68, 'close_comments_days_old', '14', 'yes'),
(69, 'thread_comments', '1', 'yes'),
(70, 'thread_comments_depth', '5', 'yes'),
(71, 'page_comments', '0', 'yes'),
(72, 'comments_per_page', '50', 'yes'),
(73, 'default_comments_page', 'newest', 'yes'),
(74, 'comment_order', 'asc', 'yes'),
(75, 'sticky_posts', 'a:0:{}', 'yes'),
(76, 'widget_categories', 'a:2:{i:2;a:4:{s:5:"title";s:0:"";s:5:"count";i:0;s:12:"hierarchical";i:0;s:8:"dropdown";i:0;}s:12:"_multiwidget";i:1;}', 'yes'),
(77, 'widget_text', 'a:0:{}', 'yes'),
(78, 'widget_rss', 'a:0:{}', 'yes'),
(79, 'uninstall_plugins', 'a:0:{}', 'no'),
(80, 'timezone_string', '', 'yes'),
(81, 'page_for_posts', '0', 'yes'),
(82, 'page_on_front', '0', 'yes'),
(83, 'default_post_format', '0', 'yes'),
(84, 'link_manager_enabled', '0', 'yes'),
(85, 'finished_splitting_shared_terms', '1', 'yes'),
(86, 'site_icon', '0', 'yes'),
(87, 'medium_large_size_w', '768', 'yes'),
(88, 'medium_large_size_h', '0', 'yes'),
(89, 'wp_page_for_privacy_policy', '3', 'yes'),
(90, 'show_comments_cookies_opt_in', '1', 'yes'),
(91, 'admin_email_lifespan', '1621331799', 'yes'),
(92, 'disallowed_keys', '', 'no'),
(93, 'comment_previously_approved', '1', 'yes'),
(94, 'auto_plugin_theme_update_emails', 'a:0:{}', 'no'),
(95, 'initial_db_version', '48748', 'yes'),
(96, 'wp_user_roles', 'a:5:{s:13:"administrator";a:2:{s:4:"name";s:13:"Administrator";s:12:"capabilities";a:61:{s:13:"switch_themes";b:1;s:11:"edit_themes";b:1;s:16:"activate_plugins";b:1;s:12:"edit_plugins";b:1;s:10:"edit_users";b:1;s:10:"edit_files";b:1;s:14:"manage_options";b:1;s:17:"moderate_comments";b:1;s:17:"manage_categories";b:1;s:12:"manage_links";b:1;s:12:"upload_files";b:1;s:6:"import";b:1;s:15:"unfiltered_html";b:1;s:10:"edit_posts";b:1;s:17:"edit_others_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:10:"edit_pages";b:1;s:4:"read";b:1;s:8:"level_10";b:1;s:7:"level_9";b:1;s:7:"level_8";b:1;s:7:"level_7";b:1;s:7:"level_6";b:1;s:7:"level_5";b:1;s:7:"level_4";b:1;s:7:"level_3";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:17:"edit_others_pages";b:1;s:20:"edit_published_pages";b:1;s:13:"publish_pages";b:1;s:12:"delete_pages";b:1;s:19:"delete_others_pages";b:1;s:22:"delete_published_pages";b:1;s:12:"delete_posts";b:1;s:19:"delete_others_posts";b:1;s:22:"delete_published_posts";b:1;s:20:"delete_private_posts";b:1;s:18:"edit_private_posts";b:1;s:18:"read_private_posts";b:1;s:20:"delete_private_pages";b:1;s:18:"edit_private_pages";b:1;s:18:"read_private_pages";b:1;s:12:"delete_users";b:1;s:12:"create_users";b:1;s:17:"unfiltered_upload";b:1;s:14:"edit_dashboard";b:1;s:14:"update_plugins";b:1;s:14:"delete_plugins";b:1;s:15:"install_plugins";b:1;s:13:"update_themes";b:1;s:14:"install_themes";b:1;s:11:"update_core";b:1;s:10:"list_users";b:1;s:12:"remove_users";b:1;s:13:"promote_users";b:1;s:18:"edit_theme_options";b:1;s:13:"delete_themes";b:1;s:6:"export";b:1;}}s:6:"editor";a:2:{s:4:"name";s:6:"Editor";s:12:"capabilities";a:34:{s:17:"moderate_comments";b:1;s:17:"manage_categories";b:1;s:12:"manage_links";b:1;s:12:"upload_files";b:1;s:15:"unfiltered_html";b:1;s:10:"edit_posts";b:1;s:17:"edit_others_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:10:"edit_pages";b:1;s:4:"read";b:1;s:7:"level_7";b:1;s:7:"level_6";b:1;s:7:"level_5";b:1;s:7:"level_4";b:1;s:7:"level_3";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:17:"edit_others_pages";b:1;s:20:"edit_published_pages";b:1;s:13:"publish_pages";b:1;s:12:"delete_pages";b:1;s:19:"delete_others_pages";b:1;s:22:"delete_published_pages";b:1;s:12:"delete_posts";b:1;s:19:"delete_others_posts";b:1;s:22:"delete_published_posts";b:1;s:20:"delete_private_posts";b:1;s:18:"edit_private_posts";b:1;s:18:"read_private_posts";b:1;s:20:"delete_private_pages";b:1;s:18:"edit_private_pages";b:1;s:18:"read_private_pages";b:1;}}s:6:"author";a:2:{s:4:"name";s:6:"Author";s:12:"capabilities";a:10:{s:12:"upload_files";b:1;s:10:"edit_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:4:"read";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:12:"delete_posts";b:1;s:22:"delete_published_posts";b:1;}}s:11:"contributor";a:2:{s:4:"name";s:11:"Contributor";s:12:"capabilities";a:5:{s:10:"edit_posts";b:1;s:4:"read";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:12:"delete_posts";b:1;}}s:10:"subscriber";a:2:{s:4:"name";s:10:"Subscriber";s:12:"capabilities";a:2:{s:4:"read";b:1;s:7:"level_0";b:1;}}}', 'yes'),
(97, 'fresh_site', '0', 'yes'),
(98, 'widget_search', 'a:2:{i:2;a:1:{s:5:"title";s:0:"";}s:12:"_multiwidget";i:1;}', 'yes'),
(99, 'widget_recent-posts', 'a:2:{i:2;a:2:{s:5:"title";s:0:"";s:6:"number";i:5;}s:12:"_multiwidget";i:1;}', 'yes'),
(100, 'widget_recent-comments', 'a:2:{i:2;a:2:{s:5:"title";s:0:"";s:6:"number";i:5;}s:12:"_multiwidget";i:1;}', 'yes') ;
INSERT INTO `wp_options` ( `option_id`, `option_name`, `option_value`, `autoload`) VALUES
(101, 'widget_archives', 'a:2:{i:2;a:3:{s:5:"title";s:0:"";s:5:"count";i:0;s:8:"dropdown";i:0;}s:12:"_multiwidget";i:1;}', 'yes'),
(102, 'widget_meta', 'a:2:{i:2;a:1:{s:5:"title";s:0:"";}s:12:"_multiwidget";i:1;}', 'yes'),
(103, 'sidebars_widgets', 'a:4:{s:19:"wp_inactive_widgets";a:0:{}s:9:"sidebar-1";a:3:{i:0;s:8:"search-2";i:1;s:14:"recent-posts-2";i:2;s:17:"recent-comments-2";}s:9:"sidebar-2";a:3:{i:0;s:10:"archives-2";i:1;s:12:"categories-2";i:2;s:6:"meta-2";}s:13:"array_version";i:3;}', 'yes'),
(104, 'cron', 'a:8:{i:1605783400;a:1:{s:34:"wp_privacy_delete_old_export_files";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:6:"hourly";s:4:"args";a:0:{}s:8:"interval";i:3600;}}}i:1605787211;a:1:{s:26:"upgrader_scheduled_cleanup";a:1:{s:32:"c9059feef497c200e69cb9956a81f005";a:2:{s:8:"schedule";b:0;s:4:"args";a:1:{i:0;i:5;}}}}i:1605809949;a:1:{s:23:"fs_data_sync_helpie_faq";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:5:"daily";s:4:"args";a:0:{}s:8:"interval";i:86400;}}}i:1605823000;a:3:{s:16:"wp_version_check";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:10:"twicedaily";s:4:"args";a:0:{}s:8:"interval";i:43200;}}s:17:"wp_update_plugins";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:10:"twicedaily";s:4:"args";a:0:{}s:8:"interval";i:43200;}}s:16:"wp_update_themes";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:10:"twicedaily";s:4:"args";a:0:{}s:8:"interval";i:43200;}}}i:1605866199;a:2:{s:30:"wp_site_health_scheduled_check";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:6:"weekly";s:4:"args";a:0:{}s:8:"interval";i:604800;}}s:32:"recovery_mode_clean_expired_keys";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:5:"daily";s:4:"args";a:0:{}s:8:"interval";i:86400;}}}i:1605866328;a:2:{s:19:"wp_scheduled_delete";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:5:"daily";s:4:"args";a:0:{}s:8:"interval";i:86400;}}s:25:"delete_expired_transients";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:5:"daily";s:4:"args";a:0:{}s:8:"interval";i:86400;}}}i:1605866333;a:1:{s:30:"wp_scheduled_auto_draft_delete";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:5:"daily";s:4:"args";a:0:{}s:8:"interval";i:86400;}}}s:7:"version";i:2;}', 'yes'),
(105, 'widget_pages', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(106, 'widget_calendar', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(107, 'widget_media_audio', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(108, 'widget_media_image', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(109, 'widget_media_gallery', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(110, 'widget_media_video', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(111, 'widget_tag_cloud', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(112, 'widget_nav_menu', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(113, 'widget_custom_html', 'a:1:{s:12:"_multiwidget";i:1;}', 'yes'),
(115, 'theme_mods_twentytwenty', 'a:1:{s:18:"custom_css_post_id";i:-1;}', 'yes'),
(116, 'recovery_keys', 'a:0:{}', 'yes'),
(128, 'recently_activated', 'a:0:{}', 'yes'),
(132, 'can_compress_scripts', '1', 'no'),
(138, 'finished_updating_comment_type', '1', 'yes'),
(143, 'fs_active_plugins', 'O:8:"stdClass":3:{s:7:"plugins";a:1:{s:23:"helpie-faq/lib/freemius";O:8:"stdClass":4:{s:7:"version";s:5:"2.4.1";s:4:"type";s:6:"plugin";s:9:"timestamp";i:1605780054;s:11:"plugin_path";s:25:"helpie-faq/helpie-faq.php";}}s:7:"abspath";s:48:"C:\\Users\\essek\\Local Sites\\freshtest\\app\\public/";s:6:"newest";O:8:"stdClass":5:{s:11:"plugin_path";s:25:"helpie-faq/helpie-faq.php";s:8:"sdk_path";s:23:"helpie-faq/lib/freemius";s:7:"version";s:5:"2.4.1";s:13:"in_activation";b:0;s:9:"timestamp";i:1605780054;}}', 'yes'),
(144, 'fs_debug_mode', '', 'yes'),
(145, 'fs_accounts', 'a:14:{s:21:"id_slug_type_path_map";a:1:{i:2442;a:3:{s:4:"slug";s:10:"helpie_faq";s:4:"type";s:6:"plugin";s:4:"path";s:25:"helpie-faq/helpie-faq.php";}}s:11:"plugin_data";a:1:{s:10:"helpie_faq";a:24:{s:16:"plugin_main_file";O:8:"stdClass":1:{s:4:"path";s:25:"helpie-faq/helpie-faq.php";}s:20:"is_network_activated";b:0;s:17:"install_timestamp";i:1605780054;s:17:"was_plugin_loaded";b:1;s:21:"is_plugin_new_install";b:1;s:16:"sdk_last_version";N;s:11:"sdk_version";s:5:"2.4.1";s:16:"sdk_upgrade_mode";b:1;s:18:"sdk_downgrade_mode";b:0;s:19:"plugin_last_version";N;s:14:"plugin_version";s:3:"1.4";s:19:"plugin_upgrade_mode";b:1;s:21:"plugin_downgrade_mode";b:0;s:17:"connectivity_test";a:6:{s:12:"is_connected";b:1;s:4:"host";s:15:"freshtest.local";s:9:"server_ip";s:3:"::1";s:9:"is_active";b:1;s:9:"timestamp";i:1605780054;s:7:"version";s:3:"1.4";}s:15:"prev_is_premium";b:1;s:30:"is_extensions_tracking_allowed";b:1;s:14:"has_trial_plan";b:1;s:22:"install_sync_timestamp";i:1605780360;s:19:"keepalive_timestamp";i:1605780360;s:16:"last_license_key";s:32:"4a52631ac7d36a5f5b505cb0046003d7";s:20:"last_license_user_id";N;s:15:"is_whitelabeled";b:0;s:20:"activation_timestamp";i:1605780355;s:9:"sync_cron";O:8:"stdClass":5:{s:7:"version";s:3:"1.4";s:7:"blog_id";i:0;s:11:"sdk_version";s:5:"2.4.1";s:9:"timestamp";i:1605780364;s:2:"on";b:1;}}}s:13:"file_slug_map";a:1:{s:25:"helpie-faq/helpie-faq.php";s:10:"helpie_faq";}s:7:"plugins";a:1:{s:10:"helpie_faq";O:9:"FS_Plugin":24:{s:16:"parent_plugin_id";N;s:5:"title";s:10:"Helpie FAQ";s:4:"slug";s:10:"helpie_faq";s:12:"premium_slug";s:18:"helpie_faq-premium";s:4:"type";s:6:"plugin";s:20:"affiliate_moderation";s:8:"selected";s:19:"is_wp_org_compliant";b:1;s:22:"premium_releases_count";N;s:4:"file";s:25:"helpie-faq/helpie-faq.php";s:7:"version";s:3:"1.4";s:11:"auto_update";N;s:4:"info";N;s:10:"is_premium";b:1;s:14:"premium_suffix";s:9:"(Premium)";s:7:"is_live";b:1;s:9:"bundle_id";N;s:17:"bundle_public_key";N;s:10:"public_key";s:32:"pk_65a62b52f2165799f7e9a3e1c9cd9";s:10:"secret_key";s:32:"sk_Uf5fiD=}hkDH3[4cm]zeH7Nb.67xz";s:2:"id";s:4:"2442";s:7:"updated";N;s:7:"created";N;s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}}s:9:"unique_id";s:32:"aaa191082cad64f3471c572eb43d892d";s:5:"plans";a:1:{s:10:"helpie_faq";a:2:{i:0;O:14:"FS_Plugin_Plan":22:{s:9:"plugin_id";s:8:"MjQ0Mg==";s:4:"name";s:8:"ZnJlZQ==";s:5:"title";s:8:"RnJlZQ==";s:11:"description";N;s:17:"is_free_localhost";s:4:"MQ==";s:17:"is_block_features";s:4:"MQ==";s:12:"license_type";s:4:"MA==";s:16:"is_https_support";s:0:"";s:12:"trial_period";N;s:23:"is_require_subscription";s:0:"";s:10:"support_kb";N;s:13:"support_forum";N;s:13:"support_email";N;s:13:"support_phone";N;s:13:"support_skype";N;s:18:"is_success_manager";s:0:"";s:11:"is_featured";s:0:"";s:2:"id";s:8:"Mzc1Nw==";s:7:"updated";N;s:7:"created";s:28:"MjAxOC0wOC0xNSAxMDowNTo1Ng==";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:1;O:14:"FS_Plugin_Plan":22:{s:9:"plugin_id";s:8:"MjQ0Mg==";s:4:"name";s:4:"cHJv";s:5:"title";s:4:"UHJv";s:11:"description";N;s:17:"is_free_localhost";s:0:"";s:17:"is_block_features";s:0:"";s:12:"license_type";s:4:"MA==";s:16:"is_https_support";s:0:"";s:12:"trial_period";s:4:"Nw==";s:23:"is_require_subscription";s:4:"MQ==";s:10:"support_kb";N;s:13:"support_forum";s:40:"aHR0cHM6Ly9oZWxwaWV3cC5jb20vc3VwcG9ydC8=";s:13:"support_email";s:24:"c3VwcG9ydEBwYXVwbGUuY29t";s:13:"support_phone";N;s:13:"support_skype";N;s:18:"is_success_manager";s:0:"";s:11:"is_featured";s:4:"MQ==";s:2:"id";s:8:"Mzc2Nw==";s:7:"updated";s:28:"MjAxOS0wOS0yMyAxMToxMjoyMg==";s:7:"created";s:28:"MjAxOC0wOC0xNiAxNToxNTowMg==";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}}}s:14:"active_plugins";O:8:"stdClass":3:{s:9:"timestamp";i:1605780360;s:3:"md5";s:32:"f81cc27099ca0d1fe364869b5cececa8";s:7:"plugins";a:2:{s:25:"helpie-faq/helpie-faq.php";a:5:{s:4:"slug";s:10:"helpie_faq";s:7:"version";s:3:"1.4";s:5:"title";s:10:"Helpie FAQ";s:9:"is_active";b:1;s:14:"is_uninstalled";b:0;}s:31:"wp-migrate-db/wp-migrate-db.php";a:5:{s:4:"slug";s:13:"wp-migrate-db";s:7:"version";s:6:"1.0.16";s:5:"title";s:13:"WP Migrate DB";s:9:"is_active";b:1;s:14:"is_uninstalled";b:0;}}}s:11:"all_plugins";O:8:"stdClass":3:{s:9:"timestamp";i:1605780360;s:3:"md5";s:32:"a5470be2f141bb24ed740007d369fe61";s:7:"plugins";a:2:{s:25:"helpie-faq/helpie-faq.php";a:5:{s:4:"slug";s:10:"helpie_faq";s:7:"version";s:3:"1.4";s:5:"title";s:10:"Helpie FAQ";s:9:"is_active";b:0;s:14:"is_uninstalled";b:0;}s:31:"wp-migrate-db/wp-migrate-db.php";a:5:{s:4:"slug";s:13:"wp-migrate-db";s:7:"version";s:6:"1.0.16";s:5:"title";s:13:"WP Migrate DB";s:9:"is_active";b:0;s:14:"is_uninstalled";b:0;}}}s:10:"all_themes";O:8:"stdClass":3:{s:9:"timestamp";i:1605780360;s:3:"md5";s:32:"51a30f098c9f8bfe8a6d09183194cf1c";s:6:"themes";a:3:{s:14:"twentynineteen";a:5:{s:4:"slug";s:14:"twentynineteen";s:7:"version";s:3:"1.7";s:5:"title";s:15:"Twenty Nineteen";s:9:"is_active";b:0;s:14:"is_uninstalled";b:0;}s:15:"twentyseventeen";a:5:{s:4:"slug";s:15:"twentyseventeen";s:7:"version";s:3:"2.4";s:5:"title";s:16:"Twenty Seventeen";s:9:"is_active";b:0;s:14:"is_uninstalled";b:0;}s:12:"twentytwenty";a:5:{s:4:"slug";s:12:"twentytwenty";s:7:"version";s:3:"1.5";s:5:"title";s:13:"Twenty Twenty";s:9:"is_active";b:1;s:14:"is_uninstalled";b:0;}}}s:5:"sites";a:1:{s:10:"helpie_faq";O:7:"FS_Site":26:{s:7:"site_id";s:8:"67631891";s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:5:"title";s:9:"Test Site";s:3:"url";s:19:"http://127.0.0.1:82";s:7:"version";s:3:"1.4";s:8:"language";s:5:"en-US";s:7:"charset";s:5:"UTF-8";s:16:"platform_version";s:5:"5.5.3";s:11:"sdk_version";s:5:"2.4.1";s:28:"programming_language_version";s:5:"7.3.5";s:7:"plan_id";s:4:"3767";s:10:"license_id";s:6:"475674";s:13:"trial_plan_id";N;s:10:"trial_ends";N;s:10:"is_premium";b:1;s:15:"is_disconnected";b:0;s:9:"is_active";b:1;s:14:"is_uninstalled";b:0;s:10:"public_key";s:32:"pk_d41016f57a0dada5fe490d67baad4";s:10:"secret_key";s:32:"sk_czKe:&3%E&;d>b*L*~nvN:q25]t$y";s:2:"id";s:7:"6006430";s:7:"updated";s:19:"2020-11-19 10:05:56";s:7:"created";s:19:"2020-11-19 10:05:56";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}}s:5:"users";a:1:{i:1418060;O:7:"FS_User":14:{s:5:"email";s:21:"stephenr816@gmail.com";s:5:"first";s:7:"Stephen";s:4:"last";s:3:"Raj";s:11:"is_verified";b:1;s:7:"is_beta";N;s:11:"customer_id";N;s:5:"gross";i:0;s:10:"public_key";s:32:"pk_944d72a673940e247123aaf7567d5";s:10:"secret_key";s:32:"sk_uWVdgd3U28%?F+}pYZR!DA#(?AliH";s:2:"id";s:7:"1418060";s:7:"updated";s:19:"2020-10-07 10:32:09";s:7:"created";s:19:"2018-09-05 04:22:17";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}}s:23:"user_id_license_ids_map";a:1:{i:2442;a:1:{i:1418060;a:6:{i:0;i:475674;i:1;i:369718;i:2;i:347123;i:3;i:339126;i:4;i:71993;i:5;i:62494;}}}s:12:"all_licenses";a:1:{i:2442;a:6:{i:0;O:17:"FS_Plugin_License":22:{s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:16:"parent_plan_name";N;s:17:"parent_plan_title";N;s:17:"parent_license_id";N;s:8:"products";N;s:10:"pricing_id";s:4:"2909";s:5:"quota";i:4;s:9:"activated";i:0;s:15:"activated_local";i:2;s:10:"expiration";N;s:10:"secret_key";s:32:"sk_6BDg0X5-z8z<S9(fCk{:(XIyDddUC";s:15:"is_whitelabeled";b:0;s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:2:"id";s:6:"475674";s:7:"updated";s:19:"2020-11-19 10:05:56";s:7:"created";s:19:"2020-11-02 07:13:08";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:1;O:17:"FS_Plugin_License":22:{s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:16:"parent_plan_name";N;s:17:"parent_plan_title";N;s:17:"parent_license_id";N;s:8:"products";N;s:10:"pricing_id";s:4:"2910";s:5:"quota";i:5;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2021-06-10 12:32:25";s:10:"secret_key";s:32:"sk_ET&S8^vP]:y_#C59Lo~gXW&;DOxo+";s:15:"is_whitelabeled";b:0;s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:2:"id";s:6:"369718";s:7:"updated";s:19:"2020-10-13 12:47:07";s:7:"created";s:19:"2020-06-10 12:32:25";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:2;O:17:"FS_Plugin_License":22:{s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:16:"parent_plan_name";N;s:17:"parent_plan_title";N;s:17:"parent_license_id";N;s:8:"products";N;s:10:"pricing_id";s:4:"7167";s:5:"quota";i:100;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2021-05-09 13:01:45";s:10:"secret_key";s:32:"sk_=OS>tmvLcx%2po-w-kk2wT8rxs;vG";s:15:"is_whitelabeled";b:0;s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:2:"id";s:6:"347123";s:7:"updated";s:19:"2020-11-16 04:58:14";s:7:"created";s:19:"2020-05-09 13:01:45";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:3;O:17:"FS_Plugin_License":22:{s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:16:"parent_plan_name";N;s:17:"parent_plan_title";N;s:17:"parent_license_id";N;s:8:"products";N;s:10:"pricing_id";s:4:"7167";s:5:"quota";i:100;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2021-04-28 07:55:40";s:10:"secret_key";s:32:"sk_z!T<g-qLkNsG_]BR(5+KoQ~EG8=U#";s:15:"is_whitelabeled";b:0;s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:2:"id";s:6:"339126";s:7:"updated";s:19:"2020-06-10 12:59:46";s:7:"created";s:19:"2020-04-28 07:55:40";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:4;O:17:"FS_Plugin_License":22:{s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:16:"parent_plan_name";N;s:17:"parent_plan_title";N;s:17:"parent_license_id";N;s:8:"products";N;s:10:"pricing_id";s:4:"2910";s:5:"quota";i:5;s:9:"activated";i:0;s:15:"activated_local";i:4;s:10:"expiration";s:19:"2020-05-13 05:38:06";s:10:"secret_key";s:32:"sk_wXM;w9yu&l=WSV!ILMX!9x{7mq4L%";s:15:"is_whitelabeled";b:0;s:17:"is_free_localhost";b:1;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:2:"id";s:5:"71993";s:7:"updated";s:19:"2020-11-19 09:53:06";s:7:"created";s:19:"2019-05-13 05:38:06";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:5;O:17:"FS_Plugin_License":22:{s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:16:"parent_plan_name";N;s:17:"parent_plan_title";N;s:17:"parent_license_id";N;s:8:"products";N;s:10:"pricing_id";s:4:"3572";s:5:"quota";N;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2020-03-22 07:37:56";s:10:"secret_key";s:32:"sk_^CI1$8RAphI;uaDLzO+S_ciyjwv)Z";s:15:"is_whitelabeled";b:0;s:17:"is_free_localhost";b:1;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:2:"id";s:5:"62494";s:7:"updated";s:19:"2020-10-15 04:50:59";s:7:"created";s:19:"2019-03-22 07:37:56";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}}}s:7:"updates";a:1:{i:2442;N;}}', 'yes'),
(146, 'fs_gdpr', 'a:1:{s:2:"u1";a:2:{s:8:"required";b:0;s:18:"show_opt_in_notice";b:0;}}', 'yes'),
(147, 'fs_api_cache', 'a:5:{s:26:"get:/v1/users/1418060.json";O:8:"stdClass":3:{s:6:"result";O:8:"stdClass":16:{s:15:"default_card_id";s:5:"53964";s:5:"gross";i:0;s:6:"source";i:0;s:13:"last_login_at";s:19:"2020-06-10 12:59:07";s:5:"email";s:21:"stephenr816@gmail.com";s:5:"first";s:7:"Stephen";s:4:"last";s:3:"Raj";s:7:"picture";N;s:2:"ip";s:12:"192.168.95.1";s:11:"is_verified";b:1;s:10:"secret_key";s:32:"sk_uWVdgd3U28%?F+}pYZR!DA#(?AliH";s:10:"public_key";s:32:"pk_944d72a673940e247123aaf7567d5";s:2:"id";s:7:"1418060";s:7:"created";s:19:"2018-09-05 04:22:17";s:7:"updated";s:19:"2020-10-07 10:32:09";s:11:"customer_id";N;}s:7:"created";i:1605780355;s:9:"timestamp";i:1605866755;}s:29:"get:/v1/installs/6006430.json";O:8:"stdClass":3:{s:6:"result";O:8:"stdClass":32:{s:7:"site_id";s:8:"67631891";s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:3:"url";s:19:"http://127.0.0.1:82";s:5:"title";s:9:"Test Site";s:7:"version";s:3:"1.4";s:7:"plan_id";s:4:"3767";s:10:"license_id";s:6:"475674";s:13:"trial_plan_id";N;s:10:"trial_ends";N;s:15:"subscription_id";s:6:"143199";s:5:"gross";i:0;s:12:"country_code";s:2:"in";s:8:"language";s:5:"en-US";s:7:"charset";s:5:"UTF-8";s:16:"platform_version";s:5:"5.5.3";s:11:"sdk_version";s:5:"2.4.1";s:28:"programming_language_version";s:5:"7.3.5";s:9:"is_active";b:1;s:15:"is_disconnected";b:0;s:10:"is_premium";b:1;s:14:"is_uninstalled";b:0;s:9:"is_locked";b:0;s:6:"source";i:0;s:8:"upgraded";N;s:12:"last_seen_at";s:19:"2020-11-19 10:05:59";s:26:"last_served_update_version";N;s:10:"secret_key";s:32:"sk_czKe:&3%E&;d>b*L*~nvN:q25]t$y";s:10:"public_key";s:32:"pk_d41016f57a0dada5fe490d67baad4";s:2:"id";s:7:"6006430";s:7:"created";s:19:"2020-11-19 10:05:56";s:7:"updated";s:19:"2020-11-19 10:05:56";}s:7:"created";i:1605780355;s:9:"timestamp";i:1605866755;}s:63:"get:/v1/users/1418060/plugins/2442/plans.json?show_pending=true";O:8:"stdClass":3:{s:6:"result";O:8:"stdClass":1:{s:5:"plans";a:2:{i:0;O:14:"FS_Plugin_Plan":22:{s:9:"plugin_id";s:4:"2442";s:4:"name";s:4:"free";s:5:"title";s:4:"Free";s:11:"description";N;s:17:"is_free_localhost";b:1;s:17:"is_block_features";b:1;s:12:"license_type";i:0;s:16:"is_https_support";b:0;s:12:"trial_period";N;s:23:"is_require_subscription";b:0;s:10:"support_kb";N;s:13:"support_forum";N;s:13:"support_email";N;s:13:"support_phone";N;s:13:"support_skype";N;s:18:"is_success_manager";b:0;s:11:"is_featured";b:0;s:2:"id";s:4:"3757";s:7:"updated";N;s:7:"created";s:19:"2018-08-15 10:05:56";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}i:1;O:14:"FS_Plugin_Plan":22:{s:9:"plugin_id";s:4:"2442";s:4:"name";s:3:"pro";s:5:"title";s:3:"Pro";s:11:"description";N;s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"license_type";i:0;s:16:"is_https_support";b:0;s:12:"trial_period";i:7;s:23:"is_require_subscription";b:1;s:10:"support_kb";N;s:13:"support_forum";s:29:"https://helpiewp.com/support/";s:13:"support_email";s:18:"support@pauple.com";s:13:"support_phone";N;s:13:"support_skype";N;s:18:"is_success_manager";b:0;s:11:"is_featured";b:1;s:2:"id";s:4:"3767";s:7:"updated";s:19:"2019-09-23 11:12:22";s:7:"created";s:19:"2018-08-16 15:15:02";s:22:"\0FS_Entity\0_is_updated";b:0;s:11:"_is_updated";b:0;}}}s:7:"created";i:1605780355;s:9:"timestamp";i:1605866755;}s:65:"get:/v1/users/1418060/plugins/2442/licenses.json?is_enriched=true";O:8:"stdClass":3:{s:6:"result";O:8:"stdClass":1:{s:8:"licenses";a:6:{i:0;O:8:"stdClass":25:{s:15:"subscription_id";s:6:"143199";s:12:"next_payment";s:19:"2120-11-02 07:13:08";s:17:"parent_license_id";N;s:24:"parent_license_bundle_id";N;s:17:"parent_plan_title";N;s:16:"parent_plan_name";N;s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:10:"pricing_id";s:4:"2909";s:5:"quota";i:4;s:9:"activated";i:0;s:15:"activated_local";i:2;s:10:"expiration";N;s:10:"secret_key";s:32:"sk_6BDg0X5-z8z<S9(fCk{:(XIyDddUC";s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:15:"is_whitelabeled";b:0;s:11:"environment";i:0;s:6:"source";i:0;s:2:"id";s:6:"475674";s:7:"created";s:19:"2020-11-02 07:13:08";s:7:"updated";s:19:"2020-11-19 10:05:56";s:14:"parent_plan_id";N;}i:1;O:8:"stdClass":25:{s:15:"subscription_id";N;s:12:"next_payment";N;s:17:"parent_license_id";N;s:24:"parent_license_bundle_id";N;s:17:"parent_plan_title";N;s:16:"parent_plan_name";N;s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:10:"pricing_id";s:4:"2910";s:5:"quota";i:5;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2021-06-10 12:32:25";s:10:"secret_key";s:32:"sk_ET&S8^vP]:y_#C59Lo~gXW&;DOxo+";s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:15:"is_whitelabeled";b:0;s:11:"environment";i:0;s:6:"source";i:0;s:2:"id";s:6:"369718";s:7:"created";s:19:"2020-06-10 12:32:25";s:7:"updated";s:19:"2020-10-13 12:47:07";s:14:"parent_plan_id";N;}i:2;O:8:"stdClass":25:{s:15:"subscription_id";N;s:12:"next_payment";N;s:17:"parent_license_id";N;s:24:"parent_license_bundle_id";N;s:17:"parent_plan_title";N;s:16:"parent_plan_name";N;s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:10:"pricing_id";s:4:"7167";s:5:"quota";i:100;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2021-05-09 13:01:45";s:10:"secret_key";s:32:"sk_=OS>tmvLcx%2po-w-kk2wT8rxs;vG";s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:15:"is_whitelabeled";b:0;s:11:"environment";i:0;s:6:"source";i:0;s:2:"id";s:6:"347123";s:7:"created";s:19:"2020-05-09 13:01:45";s:7:"updated";s:19:"2020-11-16 04:58:14";s:14:"parent_plan_id";N;}i:3;O:8:"stdClass":25:{s:15:"subscription_id";N;s:12:"next_payment";N;s:17:"parent_license_id";N;s:24:"parent_license_bundle_id";N;s:17:"parent_plan_title";N;s:16:"parent_plan_name";N;s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:10:"pricing_id";s:4:"7167";s:5:"quota";i:100;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2021-04-28 07:55:40";s:10:"secret_key";s:32:"sk_z!T<g-qLkNsG_]BR(5+KoQ~EG8=U#";s:17:"is_free_localhost";b:0;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:15:"is_whitelabeled";b:0;s:11:"environment";i:0;s:6:"source";i:0;s:2:"id";s:6:"339126";s:7:"created";s:19:"2020-04-28 07:55:40";s:7:"updated";s:19:"2020-06-10 12:59:46";s:14:"parent_plan_id";N;}i:4;O:8:"stdClass":25:{s:15:"subscription_id";N;s:12:"next_payment";N;s:17:"parent_license_id";N;s:24:"parent_license_bundle_id";N;s:17:"parent_plan_title";N;s:16:"parent_plan_name";N;s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:10:"pricing_id";s:4:"2910";s:5:"quota";i:5;s:9:"activated";i:0;s:15:"activated_local";i:4;s:10:"expiration";s:19:"2020-05-13 05:38:06";s:10:"secret_key";s:32:"sk_wXM;w9yu&l=WSV!ILMX!9x{7mq4L%";s:17:"is_free_localhost";b:1;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:15:"is_whitelabeled";b:0;s:11:"environment";i:0;s:6:"source";i:0;s:2:"id";s:5:"71993";s:7:"created";s:19:"2019-05-13 05:38:06";s:7:"updated";s:19:"2020-11-19 09:53:06";s:14:"parent_plan_id";N;}i:5;O:8:"stdClass":25:{s:15:"subscription_id";N;s:12:"next_payment";N;s:17:"parent_license_id";N;s:24:"parent_license_bundle_id";N;s:17:"parent_plan_title";N;s:16:"parent_plan_name";N;s:9:"plugin_id";s:4:"2442";s:7:"user_id";s:7:"1418060";s:7:"plan_id";s:4:"3767";s:10:"pricing_id";s:4:"3572";s:5:"quota";N;s:9:"activated";i:0;s:15:"activated_local";i:0;s:10:"expiration";s:19:"2020-03-22 07:37:56";s:10:"secret_key";s:32:"sk_^CI1$8RAphI;uaDLzO+S_ciyjwv)Z";s:17:"is_free_localhost";b:1;s:17:"is_block_features";b:0;s:12:"is_cancelled";b:0;s:15:"is_whitelabeled";b:0;s:11:"environment";i:0;s:6:"source";i:0;s:2:"id";s:5:"62494";s:7:"created";s:19:"2019-03-22 07:37:56";s:7:"updated";s:19:"2020-10-15 04:50:59";s:14:"parent_plan_id";N;}}}s:7:"created";i:1605780355;s:9:"timestamp";i:1605866755;}s:96:"get:/v1/installs/6006430/updates/latest.json?is_premium=true&type=all&newer_than=1.4&readme=true";O:8:"stdClass":3:{s:6:"result";O:8:"stdClass":3:{s:4:"path";s:41:":/installs/install_id/updates/latest.json";s:5:"error";O:8:"stdClass":5:{s:4:"type";s:0:"";s:7:"message";s:53:"Plugin was not yet deployed or still pending release.";s:4:"code";s:19:"plugin_not_deployed";s:4:"http";i:404;s:9:"timestamp";s:31:"Thu, 19 Nov 2020 10:07:06 +0000";}s:7:"request";O:8:"stdClass":6:{s:10:"is_premium";s:4:"true";s:4:"type";s:3:"all";s:10:"newer_than";s:3:"1.4";s:6:"readme";s:4:"true";s:11:"sdk_version";s:5:"2.4.1";s:10:"install_id";s:7:"6006430";}}s:7:"created";i:1605780424;s:9:"timestamp";i:1605782224;}}', 'no'),
(150, 'helpie_faq_category_children', 'a:0:{}', 'yes'),
(151, 'helpie_faq_page_id', '7', 'yes'),
(152, 'helpie_upgrades', 'a:3:{s:3:"0.6";b:1;s:3:"0.7";b:1;s:3:"1.0";b:1;}', 'yes'),
(153, 'helpie-faq', 'a:2:{s:15:"open_by_default";s:4:"none";s:12:"last_version";s:3:"1.0";}', 'yes'),
(154, 'helpie_version', '1.4', 'yes'),
(161, 'wpmdb_usage', 'a:2:{s:6:"action";s:8:"savefile";s:4:"time";i:1605780463;}', 'no') ;

#
# End of data contents of table `wp_options`
# --------------------------------------------------------



#
# Delete any existing table `wp_postmeta`
#

DROP TABLE IF EXISTS `wp_postmeta`;


#
# Table structure of table `wp_postmeta`
#

CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_postmeta`
#
INSERT INTO `wp_postmeta` ( `meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
(1, 2, '_wp_page_template', 'default'),
(2, 3, '_wp_page_template', 'default'),
(6, 7, 'click_counter', '0'),
(10, 9, 'click_counter', '0'),
(11, 9, '_edit_lock', '1605780264:1') ;

#
# End of data contents of table `wp_postmeta`
# --------------------------------------------------------



#
# Delete any existing table `wp_posts`
#

DROP TABLE IF EXISTS `wp_posts`;


#
# Table structure of table `wp_posts`
#

CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_posts`
#
INSERT INTO `wp_posts` ( `ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2020-11-19 09:56:39', '2020-11-19 09:56:39', '<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->', 'Hello world!', '', 'publish', 'open', 'open', '', 'hello-world', '', '', '2020-11-19 09:56:39', '2020-11-19 09:56:39', '', 0, 'http://127.0.0.1:82/?p=1', 0, 'post', '', 1),
(2, 1, '2020-11-19 09:56:39', '2020-11-19 09:56:39', '<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class="wp-block-quote"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class="wp-block-quote"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href="http://127.0.0.1:82/wp-admin/">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->', 'Sample Page', '', 'publish', 'closed', 'open', '', 'sample-page', '', '', '2020-11-19 09:56:39', '2020-11-19 09:56:39', '', 0, 'http://127.0.0.1:82/?page_id=2', 0, 'page', '', 0),
(3, 1, '2020-11-19 09:56:39', '2020-11-19 09:56:39', '<!-- wp:heading --><h2>Who we are</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Our website address is: http://127.0.0.1:82.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What personal data we collect and why we collect it</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Comments</h3><!-- /wp:heading --><!-- wp:paragraph --><p>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Media</h3><!-- /wp:heading --><!-- wp:paragraph --><p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Contact forms</h3><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Cookies</h3><!-- /wp:heading --><!-- wp:paragraph --><p>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Embedded content from other websites</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Analytics</h3><!-- /wp:heading --><!-- wp:heading --><h2>Who we share your data with</h2><!-- /wp:heading --><!-- wp:heading --><h2>How long we retain your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What rights you have over your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Where we send your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Visitor comments may be checked through an automated spam detection service.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Your contact information</h2><!-- /wp:heading --><!-- wp:heading --><h2>Additional information</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>How we protect your data</h3><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>What data breach procedures we have in place</h3><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>What third parties we receive data from</h3><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>What automated decision making and/or profiling we do with user data</h3><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Industry regulatory disclosure requirements</h3><!-- /wp:heading -->', 'Privacy Policy', '', 'draft', 'closed', 'open', '', 'privacy-policy', '', '', '2020-11-19 09:56:39', '2020-11-19 09:56:39', '', 0, 'http://127.0.0.1:82/?page_id=3', 0, 'page', '', 0),
(4, 1, '2020-11-19 09:58:53', '0000-00-00 00:00:00', '', 'Auto Draft', '', 'auto-draft', 'open', 'open', '', '', '', '', '2020-11-19 09:58:53', '0000-00-00 00:00:00', '', 0, 'http://127.0.0.1:82/?p=4', 0, 'post', '', 0),
(7, 1, '2020-11-19 10:01:05', '2020-11-19 10:01:05', '[helpie_faq]', 'Helpie FAQ', '', 'publish', 'closed', 'closed', '', 'helpie_faq_page', '', '', '2020-11-19 10:01:05', '2020-11-19 10:01:05', '', 0, 'http://127.0.0.1:82/helpie_faq_page/', 0, 'page', '', 0),
(9, 1, '2020-11-19 10:06:47', '0000-00-00 00:00:00', '', 'Auto Draft', '', 'auto-draft', 'open', 'closed', '', '', '', '', '2020-11-19 10:06:47', '0000-00-00 00:00:00', '', 0, 'http://127.0.0.1:82/?post_type=helpie_faq&p=9', 0, 'helpie_faq', '', 0) ;

#
# End of data contents of table `wp_posts`
# --------------------------------------------------------



#
# Delete any existing table `wp_term_relationships`
#

DROP TABLE IF EXISTS `wp_term_relationships`;


#
# Table structure of table `wp_term_relationships`
#

CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_term_relationships`
#
INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id`, `term_order`) VALUES
(1, 1, 0) ;

#
# End of data contents of table `wp_term_relationships`
# --------------------------------------------------------



#
# Delete any existing table `wp_term_taxonomy`
#

DROP TABLE IF EXISTS `wp_term_taxonomy`;


#
# Table structure of table `wp_term_taxonomy`
#

CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_term_taxonomy`
#
INSERT INTO `wp_term_taxonomy` ( `term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(1, 1, 'category', '', 0, 1),
(2, 2, 'helpie_faq_category', '', 0, 0) ;

#
# End of data contents of table `wp_term_taxonomy`
# --------------------------------------------------------



#
# Delete any existing table `wp_termmeta`
#

DROP TABLE IF EXISTS `wp_termmeta`;


#
# Table structure of table `wp_termmeta`
#

CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_termmeta`
#
INSERT INTO `wp_termmeta` ( `meta_id`, `term_id`, `meta_key`, `meta_value`) VALUES
(1, 2, 'click_counter', '0') ;

#
# End of data contents of table `wp_termmeta`
# --------------------------------------------------------



#
# Delete any existing table `wp_terms`
#

DROP TABLE IF EXISTS `wp_terms`;


#
# Table structure of table `wp_terms`
#

CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_terms`
#
INSERT INTO `wp_terms` ( `term_id`, `name`, `slug`, `term_group`) VALUES
(1, 'Uncategorized', 'uncategorized', 0),
(2, 'Getting Started', 'getting-started', 0) ;

#
# End of data contents of table `wp_terms`
# --------------------------------------------------------



#
# Delete any existing table `wp_usermeta`
#

DROP TABLE IF EXISTS `wp_usermeta`;


#
# Table structure of table `wp_usermeta`
#

CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_usermeta`
#
INSERT INTO `wp_usermeta` ( `umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'nickname', 'admin'),
(2, 1, 'first_name', ''),
(3, 1, 'last_name', ''),
(4, 1, 'description', ''),
(5, 1, 'rich_editing', 'true'),
(6, 1, 'syntax_highlighting', 'true'),
(7, 1, 'comment_shortcuts', 'false'),
(8, 1, 'admin_color', 'fresh'),
(9, 1, 'use_ssl', '0'),
(10, 1, 'show_admin_bar_front', 'true'),
(11, 1, 'locale', ''),
(12, 1, 'wp_capabilities', 'a:1:{s:13:"administrator";b:1;}'),
(13, 1, 'wp_user_level', '10'),
(14, 1, 'dismissed_wp_pointers', 'hfaq_admin_pointers_tutorial-one,hfaq_admin_pointers_tutorial-two,hfaq_admin_pointers_tutorial-three,hfaq_admin_pointers_tutorial-four,hfaq_admin_pointers_tutorial-five,hfaq_admin_pointers_tutorial-six'),
(15, 1, 'show_welcome_panel', '1'),
(16, 1, 'session_tokens', 'a:2:{s:64:"43131b7ff9cc11f2009dd4d9409da4298714fcca22e11e40e85fff6fe26605f8";a:4:{s:10:"expiration";i:1606989527;s:2:"ip";s:9:"127.0.0.1";s:2:"ua";s:115:"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36";s:5:"login";i:1605779927;}s:64:"2066a7962c9a6ac3a8db98fb1dc53cafca1f6f5deef103c90c40efe805c215b4";a:4:{s:10:"expiration";i:1605952728;s:2:"ip";s:9:"127.0.0.1";s:2:"ua";s:115:"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36";s:5:"login";i:1605779928;}}'),
(17, 1, 'wp_dashboard_quick_press_last_post_id', '4') ;

#
# End of data contents of table `wp_usermeta`
# --------------------------------------------------------



#
# Delete any existing table `wp_users`
#

DROP TABLE IF EXISTS `wp_users`;


#
# Table structure of table `wp_users`
#

CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


#
# Data contents of table `wp_users`
#
INSERT INTO `wp_users` ( `ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES
(1, 'admin', '$P$BBU0Pg0m5kjoNcQPv/IGXZmy4SDCbk/', 'admin', 'admin@gmail.com', 'http://127.0.0.1:82', '2020-11-19 09:56:39', '', 0, 'admin') ;

#
# End of data contents of table `wp_users`
# --------------------------------------------------------

#
# Add constraints back in and apply any alter data queries.
#

