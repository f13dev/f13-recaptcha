<?php namespace F13\Recaptcha\Controllers;

class Control
{
    private $captcha_error;
    private $enable;
    private $private_key;
    private $public_key;
    private $recaptcha_scripts;
    private $recaptcha_verify_url;

    public $label_error;
    public $label_recaptcha_failed;
    public $label_recaptcha_please_complete;

    public function __construct()
    {
        $this->recaptcha_verify_url             = 'https://www.google.com/recaptcha/api/siteverify';
        $this->recaptcha_scripts                = 'https://www.google.com/recaptcha/api.js';
        $this->enable                           = esc_attr(get_option('f13_recaptcha_enable'));
        $this->public_key                       = esc_attr(get_option('f13_recaptcha_public_key'));
        $this->private_key                      = esc_attr(get_option('f13_recaptcha_private_key'));

        $this->label_error                      = __('Error', 'f13-recaptcha');
        $this->label_recaptcha_failed           = __('This form is for humans only!', 'f13-recaptcha');
        $this->label_recaptcha_please_complete  = __('Please complete the reCaptcha checkbox.', 'f13-recaptcha');

        add_action('comment_form_defaults',     array($this, 'fields'));
        add_filter('preprocess_comment',        array($this, 'validate'));

        add_filter('f13_recaptcha_add',         array($this, 'recaptcha_add'), 10, 0);
        add_filter('f13_recaptcha_validate',    array($this, 'recaptcha_validate'), 10, 1);
    }

    private function _show_captcha()
    {
        if (
            $this->enable == F13_RECAPTCHA_ENABLE_NOT_LOGGED_IN && !is_user_logged_in() ||
            $this->enable == F13_RECAPTCHA_ENABLE_EVERYBODY
        ) {
            return true;
        }
        return false;
    }

    public function fields($default)
    {
        if (!$this->_show_captcha()) {
            return $default;
        }

        $default['submit_field'] = $this->recaptcha_add().$default['submit_field'];

        return $default;
    }

    public function recaptcha_add($v = '')
    {
        if (!$this->_show_captcha()) {
            return $v;
        }
        $v .= '<p>';
            $v .= '<script src="'.$this->recaptcha_scripts.'" async defer></script>';
            $v .= '<div class="g-recaptcha" data-sitekey="'.$this->public_key.'"></div>';
        $v .= '</p>';

        return $v;
    }

    public function recaptcha_validate()
    {
        if (!$this->_show_captcha()) {
            return '';
        }

        $recaptcha_response = filter_input(INPUT_POST, 'g-recaptcha-response');

        if (empty($recaptcha_response)) {
            $v = '<div class="f13-recaptcha-error">';
                $v .= '<strong>'.$this->label_error.':</strong> ';
                $v .= $this->label_recaptcha_please_complete;
            $v .= '</div>';

            return $v;
        }

        $response = wp_remote_post($this->recaptcha_verify_url, array(
            'body' => array(
                'secret' => $this->private_key,
                'response' => $recaptcha_response,
            ),
        ));

        if (is_wp_error($response)) {
            wp_die($response->get_error_message());
        }

        $data = json_decode(wp_remote_retrieve_body($response));
        if (!$data->success) {
            return '<div class="f13-recaptcha-error">'.$this->label_recaptcha_failed.'</div>';
        }

        return '';
    }

    public function validate( $commentdata )
    {
        if (!$this->_show_captcha()) {
            return $commentdata;
        }

        $msg = $this->recaptcha_validate();
        if (!empty($msg)) {
            $msg .= '<p><a href="javascript:history.back();">&laquo Back</a>';
            wp_die($msg);
        }

        return $commentdata;
    }
}