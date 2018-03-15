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
 
// Include local configuration
if (file_exists(dirname(__FILE__) . '/local-config.php')) {
	include(dirname(__FILE__) . '/local-config.php');
}

// Global DB config
if (!defined('DB_NAME')) {
	define('DB_NAME', 'wp-nuxt-test');
}
if (!defined('DB_USER')) {
	define('DB_USER', 'wp-nuxt-test');
}
if (!defined('DB_PASSWORD')) {
	define('DB_PASSWORD', 'NFtDYj7yf6Dio7X8');
}
if (!defined('DB_HOST')) {
	define('DB_HOST', '127.0.0.1:3306');
}

/** Database Charset to use in creating database tables. */
if (!defined('DB_CHARSET')) {
	define('DB_CHARSET', 'utf8');
}

/** The Database Collate type. Don't change this if in doubt. */
if (!defined('DB_COLLATE')) {
	define('DB_COLLATE', '');
}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0Pt~;b^>CS)-/[*|y6w-T65lqt{0/)fsq5*]v>-@5HU}/{IdU&T=cgMQ)0n|KR0M');
define('SECURE_AUTH_KEY',  'a^|X;kR]<1W!gX?6Uc{hp|S9^bDlxVTLEG $!avkEX{L@w+ZnJ6BZeATah62gfc#');
define('LOGGED_IN_KEY',    '%)C}dr&@pMb^g)cmx^{La9z,BtY5L@21L%IPU3cwJzG-N;<*4:Oc8a2_?pI;~Dq]');
define('NONCE_KEY',        'h^+.BuMT)_QKiQ48WMtj}bIRr(NnLN^1x5hm}idGN!XF<c`bG2)}dx/bIx/*7Z&E');
define('AUTH_SALT',        '<4nO.k2YVrf3/%M?EJ24^w xfAs8a[ON>E2FHO%/L`b}beVExLQ}_h44B|ZXc5P#');
define('SECURE_AUTH_SALT', '%( o6udAU6ZPA:d)UjAKK=pJxc|QM=<~rmnJ])/t5BSkKKkj+r]/~A}|]ezq/ng|');
define('LOGGED_IN_SALT',   '& M:COj~TUL@o}]a?8-/&q[V|c1h(/2eS+^s,5)8>Fn^UwG`z1v[ss?mb*>;6bXR');
define('NONCE_SALT',       'nCnaU^V0?e(AI1]K*.,b~YjpjDCZ1>dz048U4sGM#0W]0@@MgsY59IDuh:]8CtFZ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');


/**
 * Set custom paths
 *
 * These are required because wordpress is installed in a subdirectory.
 */

$path = str_replace($_SERVER['DOCUMENT_ROOT'],"",__DIR__);
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != "80")?":".$_SERVER['SERVER_PORT']:"";
if (!defined('WP_SITEURL')) {
	define('WP_SITEURL', $protocol . $_SERVER['SERVER_NAME'] .$port.$path.'/wordpress');
}
if (!defined('WP_HOME')) {
	define('WP_HOME',    $protocol . $_SERVER['SERVER_NAME'] .$port.$path);
}
if (!defined('WP_CONTENT_DIR')) {
	define('WP_CONTENT_DIR', dirname(__FILE__) . '/content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', $protocol . $_SERVER['SERVER_NAME'] .$port.$path. '/content');
}


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if (!defined('WP_DEBUG')) {
	define('WP_DEBUG', false);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
