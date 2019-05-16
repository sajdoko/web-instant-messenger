<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://localweb.it/
 * @since      1.0.0
 *
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/admin
 * @author     LocalWeb S.R.L <sajmir.doko@localweb.it>
 */
class Web_Instant_Messenger_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/web-instant-messenger-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * An instance of this class should be passed to the run() function
         * defined in Web_Instant_Messenger_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Web_Instant_Messenger_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/web-instant-messenger-admin.js', array( 'jquery' ), $this->version, false );

    }

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_options_page(__('Web Instant Messenger Options', $this->plugin_name), __('WIM Options', $this->plugin_name), 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links($links) {
        /*
         *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
         */
        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page() {
        include_once 'partials/web-instant-messenger-admin-display.php';
    }

    /**
     * Sanitize plugin input options.
     *
     * @since    1.0.0
     */
    public function validate($input) {
        // All checkboxes inputs
        $valid = array();

        //Activate
        $valid['activate'] = (isset($input['activate']) && !empty($input['activate'])) ? 1 : 0;
        $valid['rag_soc'] = (strlen($input['rag_soc']) > 20) ? sanitize_text_field(substr($input['rag_soc'], 0, 20)) : sanitize_text_field($input['rag_soc']);
        $valid['auto_show_wim'] = (isset($input['auto_show_wim']) && !empty($input['auto_show_wim'])) ? $input['auto_show_wim'] : 'SI';
        $valid['show_wim_after'] = (isset($input['show_wim_after']) && !empty($input['show_wim_after'])) ? $input['show_wim_after'] : '5';
        $valid['show_mobile'] = (isset($input['show_mobile']) && !empty($input['show_mobile'])) ? $input['show_mobile'] : 'SI';
        $valid['lingua'] = (isset($input['lingua']) && !empty($input['lingua'])) ? $input['lingua'] : 'it';
        $valid['messaggio_0'] = (strlen($input['messaggio_0']) > 250) ? sanitize_textarea_field(substr($input['messaggio_0'], 0, 250)) : sanitize_textarea_field($input['messaggio_0']);
        $valid['messaggio_1'] = (strlen($input['messaggio_1']) > 250) ? sanitize_textarea_field(substr($input['messaggio_1'], 0, 250)) : sanitize_textarea_field($input['messaggio_1']);
        // $valid['verifica'] = sanitize_text_field($input['verifica']);

		if (isset($_POST['save_options']) && isset($_POST['token']) &&  strlen($_POST['token']) == 32) {
            $datat = array();
            foreach ($_POST[$this->plugin_name] as $name => $val) {
                if (strlen($val) > 250) {
                    $val = substr($val, 0, 250);
                }
                $datat[$name] = $val;
            }
            $api_url = 'https://localweb.it/chat/api/cliente/aggiorna.php';
            $token = (isset($_POST['token']) && !empty($_POST['token'])) ? sanitize_text_field($_POST['token']) : "";
            $auto_show_wim = (isset($datat['auto_show_wim']) && !empty($datat['auto_show_wim'])) ? sanitize_text_field($datat['auto_show_wim']) : "SI";
            $show_wim_after = (isset($datat['show_wim_after']) && !empty($datat['show_wim_after'])) ? sanitize_text_field($datat['show_wim_after']) : "5";
            $show_mobile = (isset($datat['show_mobile']) && !empty($datat['show_mobile'])) ? sanitize_text_field($datat['show_mobile']) : "SI";
            $lingua = (isset($datat['lingua']) && !empty($datat['lingua'])) ? sanitize_text_field($datat['lingua']) : "it";
            $messaggio_0 = (isset($datat['messaggio_0']) && !empty($datat['messaggio_0'])) ? sanitize_textarea_field($datat['messaggio_0']) : "Salve! Come posso esserle utile?";
            $messaggio_1 = (isset($datat['messaggio_1']) && !empty($datat['messaggio_1'])) ? sanitize_textarea_field($datat['messaggio_1']) : "Gentilmente, mi può lasciare un contatto telefonico o email in modo da poterla eventualmente ricontattare?";
            $response = wp_remote_post($api_url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => json_encode(array('plugin_token' => $token, 'auto_show_wim' => $auto_show_wim, 'show_wim_after' => $show_wim_after, 'show_mobile' => $show_mobile, 'lingua' => $lingua, 'messaggio_0' => $messaggio_0, 'messaggio_1' => $messaggio_1)),
                'cookies' => array(),
            )
            );
            $ret_body = wp_remote_retrieve_body($response);
            $data = json_decode($ret_body);
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                add_settings_error(
                    $this->plugin_name,
                    $this->plugin_name. '_settings_not_updated_error',
                    _e('Something went wrong: ' . $error_message, $this->plugin_name),
                    'error'
                );
            } elseif ($data->response == 'success') {
                add_settings_error(
                    $this->plugin_name,
                    $this->plugin_name. '_settings_updated',
                    'Le impostazioni sono state salvate',
                    'updated'
                );
            } elseif ($data->response == 'danger') {
                add_settings_error(
                    $this->plugin_name,
                    $this->plugin_name. '_settings_not_updated_danger',
                    $data->message,
                    'error'
                );
            } else {
                add_settings_error(
                    $this->plugin_name,
                    $this->plugin_name. '_settings_not_updated_not_known',
                    $data->message,
                    'error'
                );
            }
        }


        return $valid;
    }

    /**
     * Register plugin input options.
     *
     * @since    1.1.0
     */
    public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }

    /**
     * Notices template
     */
    public function wim_dashboard_notices() {
        $class = 'notice notice-error';
        $message = __('Irks! An error has occurred twice.', $this->plugin_name);
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }

    public function insert_wim_footer() {
        $activation_status = get_option('wim_activation_status');
        $options = get_option($this->plugin_name);
        $rag_soc = $options['rag_soc'];
        $activate = $options['activate'];
        if ($activation_status['activation_status'] == 1 && $activate == 1 && $rag_soc != '') {
            echo '<script type="text/javascript">
					(function(d){
						var s = d.getElementsByTagName(\'script\'),f = s[s.length-1], p = d.createElement(\'script\');
						window.WidgetId = "USC_WIDGET";
						p.type = \'text/javascript\';
						p.setAttribute(\'charset\',\'utf-8\');
						p.async = 1;
						p.id = "ultimate_support_chat";
						p.src = "//www.localweb.it/chat/widget/ultimate_chat_widget.js";
						f.parentNode.insertBefore(p, f);
					}(document));
				</script>';
            echo '<p id="rag_soc" style="display:none">';
            echo $rag_soc;
            echo '</p>';
        } elseif ($activate != 1) {
            echo '<script type="text/javascript">
			console.log("WIM not activated!");
			</script>';
        } elseif ($rag_soc == '') {
            echo '<script type="text/javascript">
			console.log("Missing business name!");
			</script>';
        } else {
            echo '<script type="text/javascript">
			console.log("WIM installed!");
			</script>';
        }

    }
}
