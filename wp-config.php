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
define('DB_NAME', 'john');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ddAG>`Y{^rz/B+wdB7ebkJKsMkIhp+ t/wv[rWV-H)h1v[)ZIo{g-7TDsJwzN!et');
define('SECURE_AUTH_KEY',  'yB jq{-8OX[-!P.&A~:f^:yo)&.vH(bH61v`{=vq}eQQ}/]eZ|it _%NK]3,`z 2');
define('LOGGED_IN_KEY',    '/rI [K]*- agEmTY_[Xy f@oDKjgtF#CQmQ`=1!,:{vUR.*Vc-OyC~(Hyr5!bJ>q');
define('NONCE_KEY',        '`O]b-ZIQ>SC{sT]*+wf2&OQHBQP`]0qq8h=-,ql9z`=;Wv&eE=!_gfL=[BGpXyi>');
define('AUTH_SALT',        ',qL^4;1mrh.<m3vR{)kyf[(FD<Pai{g 18N]s6Xv}~e,.WW:~fKn2|KzcW=^CUMV');
define('SECURE_AUTH_SALT', 'Gc^svI03GFo-9CR&.hBN-k&HVme Jj6USWqY-l+#h<>$F+Y`sXg_(!fszE^4uxPP');
define('LOGGED_IN_SALT',   '50Y-laF4?PfQ~{UulBG3<X_J)2BI`]-i1I&SVvXLawU*7D^BYIU&v<]8ul+q :qR');
define('NONCE_SALT',       ';_q.T QeedG-&c6a&kxO<mt_8vk]E-Np3i_&]|Exz}oqLmvPW7qFC*n-f|aok(Y,');

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
