<?php namespace F13\Recaptcha\Views;

class Admin
{
    public $label_all_wordpress_plugins;
    public $label_plugins_by_f13;

    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }

        $this->label_all_wordpress_plugins = __('All WordPress Plugins', 'f13-recaptcha');
        $this->label_plugins_by_f13 = __('Plugins by F13', 'f13-recaptcha');
    }

    public function f13_settings()
    {
        $response = wp_remote_get('https://f13dev.com/f13-plugins/');
        $response = wp_remote_get('https://pluginlist.f13.dev');
        $body     = wp_remote_retrieve_body( $response );
        $v = '<div class="wrap">';
            $v .= '<h1>'.$this->label_plugins_by_f13.'</h1>';
            $v .= '<div id="f13-plugins">'.$body.'</div>';
            $v .= '<a href="'.admin_url('plugin-install.php').'?s=f13dev&tab=search&type=author">'.$this->label_all_wordpress_plugins.'</a>';
        $v .= '</div>';

        return $v;
    }

    public function recaptcha_settings()
    {
        $v = '<div class="wrap">';
            $v .= '<h1>'.__('F13 Google reCaptcha Settings', 'f13-recaptcha').'</h1>';

            $v .= '<p>Welcome to the settings page for Google reCaptcha.</p>';
            $v .= '<p>This plugin requires a Google reCaptcha API key to function</p>';
            $v .= '<h3>To obtain a Google reCaptcha API key:</h3>';
            $v .= '<ol>';
                $v .= '<li>Log-in to your Google account or register if you do not have one.</li>';
                $v .= '<li>Visit <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a>.</li>';
                $v .= '<li>Enter a name for your API key</li>';
                $v .= '<li>Select "reCaptcha v2 > "I\'m not a robot"</li>';
                $v .= '<li>Enter your websites domain name</li>';
                $v .= '<li>Read and accept the Google reCaptcha terms</li>';
                $v .= '<li>Submit the form</li>';
                $v .= '<li>Copy and paste the public and private API Keys to the fields below.</li>';
            $v .= '</ol>';

            $v .= '<form method="post" action="options.php">';
                $v .= '<input type="hidden" name="option_page" value="'.esc_attr('f13-recaptcha-settings-group').'">';
                $v .= '<input type="hidden" name="action" value="update">';
                $v .= '<input type="hidden" id="_wpnonce" name="_wpnonce" value="'.wp_create_nonce('f13-recaptcha-settings-group-options').'">';
                do_settings_sections('f13-recaptcha-settings-group');
                $v .= '<table class="form-table">';
                    $v .= '<tr valign="top">';
                        $v .= '<th scope="row">'.__('Public key', 'f13-recaptcha').'</th>';
                        $v .= '<td>';
                            $v .= '<input type="password" name="f13_recaptcha_public_key" value="'.esc_attr(get_option('f13_recaptcha_public_key')).'" style="width: 100%; max-width: 400px;">';
                        $v .= '</td>';
                    $v .= '</tr>';
                    $v .= '<tr valign="top">';
                        $v .= '<th scope="row">'.__('Private key', 'f13-recaptcha').'</th>';
                        $v .= '<td>';
                            $v .= '<input type="password" name="f13_recaptcha_private_key" value="'.esc_attr(get_option('f13_recaptcha_private_key')).'" style="width: 100%; max-width: 400px;">';
                        $v .= '</td>';
                    $v .= '</tr>';
                    $v .= '<tr>';
                        $v .= '<th scope="row">'.__('Enable recaptcha for', 'f13-recaptcha').'</th>';
                        $v .= '<td>';
                            $v .= '<select name="f13_recaptcha_enable" style="width: 100%; max-width: 400px;">';
                                $enable = esc_attr(get_option('f13_recaptcha_enable'));
                                $v .= '<option value="'.F13_RECAPTCHA_ENABLE_NOBODY.'" '.($enable == F13_RECAPTCHA_ENABLE_NOBODY ? 'selected="selected"' : '').'>'.__('Nobody', 'f13-recaptcha').'</option>';
                                $v .= '<option value="'.F13_RECAPTCHA_ENABLE_NOT_LOGGED_IN.'" '.($enable == F13_RECAPTCHA_ENABLE_NOT_LOGGED_IN ? 'selected="selected"' : '').'>'.__('Not logged in (visitors)', 'f13-recaptcha').'</option>';
                                $v .= '<option value="'.F13_RECAPTCHA_ENABLE_EVERYBODY.'" '.($enable == F13_RECAPTCHA_ENABLE_EVERYBODY ? 'selected="selected"' : '').'>'.__('Everybody (visitors and members)', 'f13-recaptcha').'</option>';
                            $v .= '</select>';
                        $v .= '</td>';
                    $v .= '</tr>';
                $v .= '</table>';
                $v .= '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>';
            $v .= '</form>';

            $v .= '<h3>'.__('Support', 'f13-recaptcha').'</div>';
            $v .= '<p>';
                $v .= 'For end user and developer support, please visit <a href="https://f13.dev/wordpress-plugin-recaptcha" target="_blank">F13 reCaptcha</a>';
            $v .= '</p>';

        $v .= '</div>';

        return $v;
    }
}