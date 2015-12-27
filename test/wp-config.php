<?php
ini_set('display_errors','on');
error_reporting(E_ALL);
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'flexbil_wp1');


/** MySQL database username */
define('DB_USER', 'flexbil_com');


/** MySQL database password */
define('DB_PASSWORD', 'OK-Billeje');


/** MySQL hostname */
define('DB_HOST', 'localhost');


/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');


/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'D.|4KQzIa@Cjpr!o#z-Mx-dTqUo&p+;be|K#p<ly+Ot@HJ7)V.W5cr%kzYaU|q5:');

define('SECURE_AUTH_KEY',  'K|~fdSwc:7Y,o? 1J%G5P3NlI(VOz Ls-?=.AlPe)Imeq]js[vLXl{Q!.v]MiF1]');

define('LOGGED_IN_KEY',    'Jzx]p+]cTm+W8[lWKWO_(y=-k%.vD+@Wn_1~AZ_BkXf- Fr+j)_xDAG`4_a][fy@');

define('NONCE_KEY',        'p-Z_-2-4ap06k:sPu8jyyybKwkO]8KT-?2*X|Gp;(Anai3M*u^~yK],gQxiq.f|0');

define('AUTH_SALT',        '|7L>^?[gJ-!c3IPSl>_0SSHFE:3dun2&Z5!`L.f%Dv(>`s}(Z* Wb&0zuRPvm]J$');

define('SECURE_AUTH_SALT', '&2.;jbK|l/9i]|Xj;QeaD46-ql@fd_k[X.fA!m3f4g+jvKBP.7e7VU&X/_B|2WH$');

define('LOGGED_IN_SALT',   'F5W/-c;u:<WV2A{[HOsJzTv|IDFS$3C+i tf$MQ10nb|9X*[Ft@=uu^R{~k(MCYI');

define('NONCE_SALT',       ' .hOi?Y6`Ar/ XW|DdR|l2,(%={*#KN}WKq{5; pVO$jBw<?!<|Q.Lb|sC})L|z}');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpdk_';


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
