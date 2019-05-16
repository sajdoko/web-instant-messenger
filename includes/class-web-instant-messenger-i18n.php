<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/includes
 * @author     LocalWeb S.R.L <sajmir.doko@localweb.it>
 */
class Web_Instant_Messenger_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'web-instant-messenger',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
