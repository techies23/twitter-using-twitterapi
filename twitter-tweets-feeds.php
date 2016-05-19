<?php
/**
 *
 * @link              http://www.deepenbajracharya.com.np
 * @since             1.0
 * @package           Tweet using TwitterAPI
 *
 * Plugin Name:      	Tweet using TwitterAPI
 * Plugin URI:        http://deepenbajracharya.com.np/
 * Description:       Twitter tweets and feeds makes it easy for the users to show their twitter feeds into their site. This is a simple though powerful plugin. 
 * Version:           1.3
 * Author:            Deepen Bajracharya
 * Author URI:        http://deepenbajracharya.com.np/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tf
 * Domain Path:       /languages
 */


if(!defined('ABSPATH')) {
	die('Access Denied');
	exit;
}

define( 'TF_PLUGIN_NAME', 'Twitter Feeds' );
define( 'TF_PLUGIN_SLUG', 'tweets-feeds' );
define( 'TF_PATH', plugin_dir_path( __FILE__ ) );
define( 'TF_INCLUDES_PATH', plugin_dir_path( __FILE__ ) .'includes' );
define( 'TF_URL', plugin_dir_url( __FILE__ ) );
define( 'TF_REQUIRED_PHP_VERSION', '5.4' );
define( 'TF_PLUGIN_VERSION', '1.3' );
define( 'TF_JSON_PATH', plugin_dir_path( __FILE__ ) . 'json/' );

function tf_activate() {
	require_once TF_PATH . 'includes/activators/activator.php';
	Tf_Activator::activate();
}

function tf_deactivate() {
	require_once TF_PATH . 'includes/activators/deactivator.php';
	Tf_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'tf_activate' );
register_deactivation_hook( __FILE__, 'tf_deactivate' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require TF_PATH . 'src/TwitterAPIMain.php';
new TwitterAPIMain();

?>
