<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
ob_start(); //should be removed once the white page error is resolved

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('DB_NAME', 'tenripor_tal');
define('DB_NAME', 'tenriportal');

/** MySQL database username */
//define('DB_USER', 'tenripor_tal');
define('DB_USER', 'root');

/** MySQL database password */
//define('DB_PASSWORD', 'p6rtalTENRI');
define('DB_PASSWORD', 'p6rtalTENRI'); //SET PASSWORD FOR 'root'@'localhost' = PASSWORD('p6rtalTENRI')

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_ALLOW_REPAIR', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'P|T)QsT/mqvx87!blcwA!1uF)DG@>&1NHHz3}nlS!D<Y4tYu3jT#L(p1Ku:cL:0 ');
define('SECURE_AUTH_KEY',  'n[@B,6y_XAIB/Vs#G*cF)UBiQuh*tnBCOD (kZ Z3FF);RNu),moH~Z|Zcz%:;Sj');
define('LOGGED_IN_KEY',    '~/F9TNsJ7jh=pV]K0tY0hC}Y@S@ij&Qs{*}^MmMq5l5BXYBZo@J4pG?Fxp&Q-0+w');
define('NONCE_KEY',        'w^uWLDwLQmel][GeeqM]: arsCYuNUlq0%3p3!gR3zU,XZXV(`yU%H-Q~?Nau:$$');
define('AUTH_SALT',        '6wVwhkL:&K=/$b]2xlpwGuh1:nlVJN,[AOEAD``e4{dU*!!TVa9v871!64gv-T,a');
define('SECURE_AUTH_SALT', '2>N(Kh,oL.R4`w+@xEm{6JDxt%b0!BT+_mrabF5qdAGiiU`{Q36-jf^M8/)g0TQP');
define('LOGGED_IN_SALT',   '<]GrdOA)*6dKW$98,_Y15rEB]BY2>APQP|k@y:Gz,Sy6ew|O!`d%$U+ND K3Ed#>');
define('NONCE_SALT',       ' h`vsS0$,Wi.j1Qg#`t(@CA~.I]t1`:>JF89OgEdDO6M?ok]?#*qz`%9yul/a^?r');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
