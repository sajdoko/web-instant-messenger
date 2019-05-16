<?php
/**
 * Plugin Name:       Web Instant Messenger
 * Plugin URI:        https://webinstantmessenger.it/
 * Description:       Gestisci i messaggi del sito direttamente dalla tua app: My LocalWeb. Con "Web Instant Messenger" sei costantemente in contatto con i tuoi clienti, per guidarli in tempo reale e aumentare le conversioni.
 * Version:           1.1.0
 * Author:            LocalWeb S.R.L
 * Author URI:        https://localweb.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       web-instant-messenger
 * Domain Path:       /languages
 */
/**
 *
 * @link              https://localweb.it/
 * @since             1.1.0
 * @package           Web_Instant_Messenger
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WIM_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-web-instant-messenger-activator.php
 */
function activate_web_instant_messenger() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-web-instant-messenger-activator.php';
	Web_Instant_Messenger_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-web-instant-messenger-deactivator.php
 */
function deactivate_web_instant_messenger() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-web-instant-messenger-deactivator.php';
	Web_Instant_Messenger_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_web_instant_messenger' );
register_deactivation_hook( __FILE__, 'deactivate_web_instant_messenger' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-web-instant-messenger.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_web_instant_messenger() {

	$plugin = new Web_Instant_Messenger();
	$plugin->run();

}
run_web_instant_messenger();
