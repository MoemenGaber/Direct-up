<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "wordpress" );
/** MySQL database username */
define( 'DB_USER', "wordpress" );
/** MySQL database password */
define( 'DB_PASSWORD', "wordpress" );
/** MySQL hostname */
define( 'DB_HOST', "database" );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '=UuhrmuCjG{GHLP,dl.v9uu-BP(qi}vu6io__)QT*{C7|1NE$cteM|epiA5K%-aa' );
define( 'SECURE_AUTH_KEY',  'rGYKKlUgT6;06a.-0=|/u9[EJirm[pm5>-MDL36 reTQ/Dzxz6>,ui<x DSTFSta' );
define( 'LOGGED_IN_KEY',    'k]ocyMqNV~2 o=UTCH7.M~1/pB|WAclwhS{lwT{)H1bN!b4^_;H#< z/:/KOUg%]' );
define( 'NONCE_KEY',        'C?6/0zExb;xK|8nw*Ekczd7=`Ucz&yk8&z*b-6y7oa}iipWx*2~`zvyR& ^dxa9h' );
define( 'AUTH_SALT',        'f>H:7O tQip&W]R?X/Q;LJl,6s_S2|b&hRqw4W`mZrR4bV@{?q|T(&Uzeo PT#)m' );
define( 'SECURE_AUTH_SALT', 'wewTxN4Qw%2Gl;tcx*z47?w]tkM6`.%!$v+%1Y/BY3LGk/hZo,(o6FwP)cEvkiJl' );
define( 'LOGGED_IN_SALT',   'W^j1xq $-+E//LsQYfdI5jqsD{3XyXihgHuTHqsGEEDgy}m:DYW9)n|XDfUew/s%' );
define( 'NONCE_SALT',       'M5i?;c/1=Az-!Gc)0u,j#4$UDTSB^?;1($`9<F-r<]bqvWQb{/BNc)gUa/#3]X86' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
