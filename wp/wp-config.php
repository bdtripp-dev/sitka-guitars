<?php
/**
 * Universal wp-config.php for Docker-based WordPress
 * Works in local dev, remote dev, CI, and production.
 * Contains no secrets — all sensitive values come from environment variables.
 */

// Helper for Docker secrets (supports *_FILE)
if (!function_exists('getenv_docker')) {
    function getenv_docker($env, $default = '') {
        if ($fileEnv = getenv($env . '_FILE')) {
            return rtrim(file_get_contents($fileEnv), "\r\n");
        }
        $val = getenv($env);
        return ($val !== false) ? $val : $default;
    }
}

/**
 * Database settings
 */
define('DB_NAME',     getenv_docker('WORDPRESS_DB_NAME'));
define('DB_USER',     getenv_docker('WORDPRESS_DB_USER'));
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD'));
define('DB_HOST',     getenv_docker('WORDPRESS_DB_HOST', 'db'));

define('DB_CHARSET',  getenv_docker('WORDPRESS_DB_CHARSET', 'utf8mb4'));
define('DB_COLLATE',  getenv_docker('WORDPRESS_DB_COLLATE', ''));

/**
 * Authentication keys and salts
 */
define('AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY'));
define('AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT'));

/**
 * Table prefix
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

/**
 * Debug settings (environment-driven)
 */
define('WP_DEBUG', getenv('WP_DEBUG') === 'true');
define('WP_DEBUG_LOG', getenv('WP_DEBUG_LOG') === 'true');
define('WP_DEBUG_DISPLAY', getenv('WP_DEBUG_DISPLAY') === 'true');
define('WP_FILESYSTEM_DEBUG', getenv('WP_FILESYSTEM_DEBUG') === 'true');
@ini_set('display_errors', getenv('WP_DEBUG_DISPLAY') === 'true' ? 1 : 0);
define('WP_DISABLE_FATAL_ERROR_HANDLER', getenv('WP_DISABLE_FATAL_ERROR_HANDLER') === 'true');

/**
 * Custom values
 */
define('FS_METHOD', 'direct');
define('FS_CHMOD_DIR', 0775);
define('FS_CHMOD_FILE', 0664);
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', false);
define('WP_TEMP_DIR', __DIR__ . '/wp-content/temp/');

define('WP_MEMORY_LIMIT', '256M');
define('WP_AUTO_UPDATE_CORE', false);
define('AUTOMATIC_UPDATER_DISABLED', true);

define('AUTOSAVE_INTERVAL', 120);
define('WP_POST_REVISIONS', 10);
set_time_limit(300);

/**
 * Other per-environment constants
 */
if ($home = getenv('WP_HOME')) {
    define('WP_HOME', $home);
}
if ($siteurl = getenv('WP_SITEURL')) {
    define('WP_SITEURL', $siteurl);
}
define( 'FORCE_SSL_ADMIN', getenv('FORCE_SSL_ADMIN') === 'true' );

/**
 * HTTPS reverse proxy support
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}

/**
 * Extra config injection (official Docker feature)
 */
if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
    eval($configExtra);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';