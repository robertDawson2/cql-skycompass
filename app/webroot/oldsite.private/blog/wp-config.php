<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'thearenaclub_blog');

/** MySQL database username */
define('DB_USER', 'arenaclubblog');

/** MySQL database password */
define('DB_PASSWORD', 'NE!thKDpcR#b424Z8T');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '-vyOU[D7#Jr=ROO{W4qUIJ.{+[vf8^mh-,5+Z2LI.v)Ld>kc, kce3;6e&lRl}i4');
define('SECURE_AUTH_KEY',  '@2.qW0# b+Hj}zb?.4**X|p3O<*D80QbOT{yhTS+[]Ae[O3xf+:2`5QEybI01V@t');
define('LOGGED_IN_KEY',    '!-<;m?T8%o}|eU5{^25in~,UGi+F:NvYx+cQ|`9Fn~4].s<$f{W:j-fN&90exoe3');
define('NONCE_KEY',        'WPr&}r}IT4r]]8AqT@y,N8tELdAz@;EQ]}h_;<x#%eRVC~oe}P.)Ri1uV[s|c6>Z');
define('AUTH_SALT',        '@LTP4JLN&ZQ%-|B|>yg;RE4Ev~ ,It_&sEXS.@=.IoZ6Jv/ZQYBar!vN$ILjNcMz');
define('SECURE_AUTH_SALT', 'M@@.dM`0LgS`-N/+t,r+&A=G[]FB1}QV>;R,qAoFJ<[t!2x|P-vXiLoy;iMj_fOa');
define('LOGGED_IN_SALT',   'e/x=E$CX:WXY!8`+S[un$XN/ QMPuB&!0zWIDi7bR.YiOZ5V|T^Di%n=b+EZe,09');
define('NONCE_SALT',       'kc?!aV|(+$VQ7f|g;Ht|pfl.)-Fo#||pj1p6-C 0_J5ulPQfA_ndoF}EaZ:-#0u6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
