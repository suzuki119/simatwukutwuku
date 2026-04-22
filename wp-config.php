<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'D]Fxth7|b1Q~eEM_I5o^@Rpn]&$tQ[,+{ ,Al+M#f:CMvgH/?Uf24t.#^7P#xy)%' );
define( 'SECURE_AUTH_KEY',   '89}p#=XQ*}Zquc}S-NE?]u]Vx_BhQ:3bQhP A2_CTa7H5W4k?JLyzdXF<nAEawP*' );
define( 'LOGGED_IN_KEY',     'E#Sg,oV.iq>a`Sqy!4.IdKF!8^C(=&Baye2K@#5j}oUhx3GI5t:n,9kparY@8,=0' );
define( 'NONCE_KEY',         'SA6Xc)%iLt?L:3[>w^f565b~rB/,R=w{3nN0H1aExd% &:/rC-2EdP&G!R]|SF&d' );
define( 'AUTH_SALT',         'fJ,hw@<xbP{@QN^TGB7.Q1~(8seR;8T/u>~1o,Cz1_7CyX_Bl6%z./S[hF8c|5}~' );
define( 'SECURE_AUTH_SALT',  'Rp:,D-OvhMsLQbwN6 c0_hmW`#f)> yPLZd2U=$0od>kyLNlmY3$6uO9.srk=5b?' );
define( 'LOGGED_IN_SALT',    'm-i BSzM 8Eicyivld3O?auP/J7Fow*Kk{mC,$|S@%{c]T3sNM-O^n.xbqKv%E5D' );
define( 'NONCE_SALT',        'Ao<tFGr,D2z*>-UamUZPq6cEsY2=z0AgfaN e9rG4UacNk*Eq.zXeV$CEM<XmCLN' );
define( 'WP_CACHE_KEY_SALT', 'DRZ?},lH;k*!Ap(5vKB@w:F~iVC^h;7&^C^buE:QSD~rCsEa!#S>xHrg]TM-,*_z' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
