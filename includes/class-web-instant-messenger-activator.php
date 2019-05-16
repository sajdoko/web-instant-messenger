<?php

/**
 * Fired during plugin activation
 *
 * @link       https://localweb.it/
 * @since      1.1.0
 *
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.0
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/includes
 * @author     LocalWeb S.R.L <sajmir.doko@localweb.it>
 */
class Web_Instant_Messenger_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.0
	 */
	public static function activate() {
		$activation_status = get_option('wim_activation_status');
		$option_activation_status['activation_status'] = 0;
		$option_activation_status['token'] = ($activation_status['token'] != '') ? $activation_status['token']: '';
		update_option( 'wim_activation_status', $option_activation_status );
	}

}