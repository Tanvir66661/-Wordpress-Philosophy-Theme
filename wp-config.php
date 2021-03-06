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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'leFc]Q*e_R=!NIM<;u1&@W}iYs8?@]nOqFc/?Ywjlahzvfi`Rh{`v?v&Ax$n#,,{' );
define( 'SECURE_AUTH_KEY',  'O-Q._iy<LSXzp=pN!mips2R$P$r*_DU=:0A5T$m(AoY3nGTRz2ahHL7nIF!gQdny' );
define( 'LOGGED_IN_KEY',    'nFR%Y@S%#a?rApxGOVH+sLh@balz^#xKSZycw-D`ecIXG+^|2fxp(XAy/Fih8{OO' );
define( 'NONCE_KEY',        'k^AiHAk@n+e!|(A>,rl3.D2CjDoF|3NC4@^HL9_9nW!c{};kZv@51}Li4S@/KnC%' );
define( 'AUTH_SALT',        'b`u6fB-*>frie5E^guha&b0,ll3O#5}h)a+c?1p!`9z|i,>@xIuN-PB^$w+.ZD3d' );
define( 'SECURE_AUTH_SALT', ':gp}^Me?Z|?#|h7c#-`/<J}s_]pWheb|+&Vgv xv1]G%&ClPH1 4f2gFq>MhfjV=' );
define( 'LOGGED_IN_SALT',   '$pU|x[Tl=76wqey{ &owZjFvOq+Fi+/4v_Z:Nm<Q2WU:Nf,qT2F +Hphq><e|34f' );
define( 'NONCE_SALT',       '(7oLRfK[/Q}};pMwN~E+u! (3U3rU|Inj*^h`pQ9e/;hxyDt,C6uPB7BF3:AcS.D' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
