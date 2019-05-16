<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://localweb.it/
 * @since      1.1.0
 *
 * @package    Web_Instant_Messenger
 * @subpackage Web_Instant_Messenger/admin/partials
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap">

<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
<hr>

<?php
process_form(); 
function process_form() {
    function domain() {
        $domain = get_option('siteurl'); //or home
        // $domain = str_replace('http://', '', $domain);
        // $domain = str_replace('https://', '', $domain);
        // $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
        return urlencode($domain);
    }
    if (isset($_POST['verify_attivation'])) {
        $api_url = 'https://localweb.it/chat/api/cliente/verifica-plugin.php';
        $response = wp_remote_get($api_url, array(
            'method' => 'GET',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array('domain' => domain()),
            'cookies' => array(),
        )
        );
        $ret_body = wp_remote_retrieve_body($response);
        $data = json_decode($ret_body);
        $option_activation_status = array();
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            echo _e('Something went wrong: ', 'web-instant-messenger'). $error_message;
            return;
        } elseif ($data->response == 'verified' ) {
            echo '<div class="notice notice-success is-dismissible"><p>';
            echo _e('Web Instant Messenger authorized!', 'web-instant-messenger') ;
            echo '</p></div>';
            $option_activation_status['activation_status'] = 1;
            $option_activation_status['token'] = ($data->token != '') ? $data->token : '';
            update_option( 'wim_activation_status', $option_activation_status );
            return;
        } elseif ($data->response == 'unverified' ) {
            echo '<div class="notice notice-error"><p>';
            echo _e('Web Instant Messenger unauthorized!', 'web-instant-messenger') ;
            echo '</p></div>';
            return;
        } else {
            echo '<div class="notice notice-error"><p>';
            echo _e('Not a valid domain!', 'web-instant-messenger') ;
            echo '</p></div>';
            return;
        }
    }
}
?>
<?php $activation_status = get_option('wim_activation_status'); ?>

<?php if ($activation_status['activation_status'] != 1 || strlen($activation_status['token']) != 32) :?>
<form method="post" id="verifica_attivazione" action="">
    <table class="widefat">
        <tbody>
            <tr>
                <td class="verify_attivation_btn">
                <input class="button-primary" type="submit" name="verify_attivation" value="<?php _e( 'Verify Activation', 'web-instant-messenger'); ?>" />
                </td>
                <td>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<?php  else : ?>
<form method="post" name="activate_options" action="options.php">

    <?php
        //Grab all options

            $options = get_option($this->plugin_name);

            // Activate
            $activate = $options['activate'];
            $token = $activation_status['token'];
            $rag_soc = $options['rag_soc'];
            $auto_show_wim = $options['auto_show_wim'];
            $show_wim_after = $options['show_wim_after'];
            $show_mobile = $options['show_mobile'];
            $lingua = $options['lingua'];
            $messaggio_0 = $options['messaggio_0'];
            $messaggio_1 = $options['messaggio_1'];

            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
            $checked = $selected = $disabled = NULL;
        ?>
        <table class="widefat">
            <tbody>
                <tr>
                    <!-- Activate Web Instant Messenger -->
                        <th scope="row" class="options-rows">
                            <label for="<?php echo $this->plugin_name;?>-activate">
                                <?php _e('Activate Web Instant Messenger', $this->plugin_name);?>
                            </label>
                        </th>
                        <td class="options-rows">
                            <legend class="screen-reader-text">
                                <span>
                                    <?php _e('Activate Web Instant Messenger', $this->plugin_name);?>
                                </span>
                            </legend>
                            <input type="checkbox" id="<?php echo $this->plugin_name;?>-activate" name="<?php echo $this->plugin_name;?>[activate]" value="1"
                                <?php checked($activate,1);?>/>
                        </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-rag_soc">
                            <?php _e('Business Name', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>
                                    <?php _e('Insert here your business name which will appear on the chat header', $this->plugin_name);?>
                                </span>
                            </legend>
                            <input type="text" maxlength="20" class="all-options" id="<?php echo $this->plugin_name;?>-rag_soc" name="<?php echo $this->plugin_name;?>[rag_soc]"
                                value="<?php echo !empty($rag_soc) ? $rag_soc : substr(get_option('blogname'), 0, 20) . ' ...';?>" />
                            <p class="description">
                                <?php _e('Insert here your business name which will appear on the chat header', $this->plugin_name);?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-auto_show_wim">
                            <?php _e('Auto show WIM', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                            <select id="<?php echo $this->plugin_name;?>-auto_show_wim" name="<?php echo $this->plugin_name;?>[auto_show_wim]">
                                <option value="SI" <?php selected( $auto_show_wim, 'SI', TRUE ); ?>><?php _e('YES', $this->plugin_name);?></option>
                                <option value="NO" <?php selected( $auto_show_wim, 'NO', TRUE ); ?>><?php _e('NO', $this->plugin_name);?></option>
                            </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-show_wim_after">
                            <?php _e('Auto show WIM after', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                            <select id="<?php echo $this->plugin_name;?>-show_wim_after" name="<?php echo $this->plugin_name;?>[show_wim_after]">
                                <option value="5" <?php selected( $show_wim_after, '5', TRUE ); ?>><?php _e('5s', $this->plugin_name);?></option>
                                <option value="10" <?php selected( $show_wim_after, '10', TRUE ); ?>><?php _e('10s', $this->plugin_name);?></option>
                                <option value="20" <?php selected( $show_wim_after, '20', TRUE ); ?>><?php _e('20s', $this->plugin_name);?></option>
                                <option value="30" <?php selected( $show_wim_after, '30', TRUE ); ?>><?php _e('30s', $this->plugin_name);?></option>
                                <option value="45" <?php selected( $show_wim_after, '45', TRUE ); ?>><?php _e('45s', $this->plugin_name);?></option>
                                <option value="60" <?php selected( $show_wim_after, '60', TRUE ); ?>><?php _e('60s', $this->plugin_name);?></option>
                            </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-show_mobile">
                            <?php _e('Show On Mobile', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                            <select id="<?php echo $this->plugin_name;?>-show_mobile" name="<?php echo $this->plugin_name;?>[show_mobile]">
                                <option value="SI" <?php selected( $show_mobile, 'SI', TRUE ); ?>><?php _e('YES', $this->plugin_name);?></option>
                                <option value="NO" <?php selected( $show_mobile, 'NO', TRUE ); ?>><?php _e('NO', $this->plugin_name);?></option>
                            </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-lingua">
                            <?php _e('Language', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                            <select id="<?php echo $this->plugin_name;?>-lingua" name="<?php echo $this->plugin_name;?>[lingua]">
                                <option value="it" <?php selected( $lingua, 'it', TRUE ); ?>><?php _e('IT', $this->plugin_name);?></option>
                                <option value="en" <?php selected( $lingua, 'en', TRUE ); ?>><?php _e('EN', $this->plugin_name);?></option>
                            </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-messaggio_0">
                            <?php _e('Automatic Message 0', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                        <fieldset>
                            <textarea id="<?php echo $this->plugin_name;?>-messaggio_0" name="<?php echo $this->plugin_name;?>[messaggio_0]" maxlength="250" cols="55" rows="3" class=""><?php echo !empty($messaggio_0) ? $messaggio_0 : _e('Salve! Come posso esserle utile?', $this->plugin_name);?></textarea>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="options-rows">
                        <label for="<?php echo $this->plugin_name;?>-messaggio_1">
                            <?php _e('Automatic Message 1', $this->plugin_name);?>
                        </label>
                    </th>
                    <td class="options-rows">
                        <fieldset>
                            <textarea id="<?php echo $this->plugin_name;?>-messaggio_1" name="<?php echo $this->plugin_name;?>[messaggio_1]" maxlength="250" cols="55" rows="3" class=""><?php echo !empty($messaggio_1) ? $messaggio_1 : _e('Gentilmente, mi può lasciare un contatto telefonico o email in modo da poterla eventualmente ricontattare?', $this->plugin_name);?></textarea>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="token" value="<?php echo !empty($token) ? $token : '';?>" id="token" />
                        <input type="hidden" name="save_options" value="save_options" id="save_options" />
                        <?php submit_button(__('Save Options', $this->plugin_name), 'primary','submit', TRUE); ?>
                    </td>
                </tr>
            </tbody>
        </table>
</form>

<?php endif; ?>


</div>