<?php
declare( strict_types=1 );

$_root_dir = dirname( __DIR__ );
$_env_dir = dirname( __DIR__ );

require_once $_root_dir . '/vendor/autoload.php';

if ( is_readable( $_env_dir . '/.env' ) ) {
	$dotenv = Dotenv\Dotenv::createUnsafeImmutable( $_env_dir );
	$dotenv->load();
}

/* Path to the WordPress codebase you'd like to test. Add a forward slash in the end. */
define( 'ABSPATH', dirname( __FILE__ ) . '/wordpress/' );

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
define( 'WP_DEFAULT_THEME', 'default' );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// ** MySQL settings ** //

/*
 * This configuration file will be used by the copy of WordPress being tested.
 * wordpress/wp-config.php will be ignored.
 *
 * WARNING WARNING WARNING!
 * These tests will DROP ALL TABLES in the database with the prefix named below.
 * DO NOT use a production database or one that is shared with something else.
 */

define( 'DB_NAME',     getenv( 'WP_TESTS_DB_NAME' ) ?: 'test' );
define( 'DB_USER',     getenv( 'WP_TESTS_DB_USER' ) ?: 'root' );
define( 'DB_PASSWORD', getenv( 'WP_TESTS_DB_PASS' ) ?: 'password' );
define( 'DB_HOST',     getenv( 'WP_TESTS_DB_HOST' ) ?: 'mysql' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define('AUTH_KEY',         'V]N7iwP->cl2a|;Wa;A,V72+|W/Y[aU;qL|]!#+PML-A*$N&}+6`2_d|jn~)h>[j');
define('SECURE_AUTH_KEY',  'Jjc.nZ>DV&=hgr@kdZvhjM,$+$eB<-F+1j|.bKisR&JAdvJFqai|?1n^3/><JK =');
define('LOGGED_IN_KEY',    '%r)sYrctgA-7CHz>jW9Yo&~B2I!,Pjc!bGF0G(xV9e9&FT||WhNi eP{gKqnaj@8');
define('NONCE_KEY',        'e+_N$RvRyd<Qhff/J:Rj!dYYj-[]{]*{W)X$:~:??rkziJ^e|_>0ajk)`W5&?YO6');
define('AUTH_SALT',        '0l!uaGqQ97hK;18k0q]Dgfl-:u</@}bDx+Lmh]+gZ-K;2XD)P+m5.ycgZgQ#*S|:');
define('SECURE_AUTH_SALT', 'KN5_Di4oR+hEh}|X6Uh]JgI{~3R[<tbcAn>lfUnb,iOn&;+R;<NxDc5P)a(}rL+L');
define('LOGGED_IN_SALT',   'GfWv>b{NnARG)4j%D[2fyu|B-(<? 2S[!8u;$H?RJSju!#=bu98+ GCh,cT](<zn');
define('NONCE_SALT',       'QO=KkT =*0E2h7L`=7]a^[,$nTU3sntV_nxeu`~9;M#JDts}fc,3OyHB6hN=)Eg}');

$table_prefix = 'wptests_';   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WPLANG', '' );
