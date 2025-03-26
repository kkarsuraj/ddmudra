<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ddmudra' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '_=$0N-;J8(,Yxu_2J.SX:;0q|>TNW{MH*k9ZT1Y#}jbSmWXjp&}IC30Kj]g`I%w5' );
define( 'SECURE_AUTH_KEY',  'x2eE?XhM,t vTZ1&==1f8R&|Fg/>P6<x~ZLELkZd(_*=^MrqKvR=#LN%%DBqC|d?' );
define( 'LOGGED_IN_KEY',    'A+O{!g2)vgzh{O:wsFe7DM)0@dN7T/w@n6;Az(+H7UM8GGt,KxgC/bgGMv{@nA/s' );
define( 'NONCE_KEY',        'Jy`:@YiPc)OuMpN$OZ@rNA}LdqP,?!Et8k+VI(W _FSGn,P|UiE@#B2|$ZxNYxM7' );
define( 'AUTH_SALT',        'U+1Ff76+:+u[R!}=b=)wB(>fYaR94$<V:Czb{xn-ZH8oU(X^+lu[wcj1!.kH}n!t' );
define( 'SECURE_AUTH_SALT', 'RE9VWA3Dh8ja&t>l;BU_X;d<SJJ_#o55$b9+jYr;0vq.;GR J*P-Jm}&JYJv)_*/' );
define( 'LOGGED_IN_SALT',   'Ko+uEn[zNWTV)mGC1sdzstSokvI.s7 ?i#Zi7W!)Rf4h(9zZV#]v>G/#9F[PSN(7' );
define( 'NONCE_SALT',       'fHIg$</1zZ!0*do(0Ows,}Fo|~J:Hs6`$};t~X;AM}Qt|:mR:GW+4yZ!W?|?^w4W' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
