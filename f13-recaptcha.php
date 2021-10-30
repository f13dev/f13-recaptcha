<?php
/*
Plugin Name: F13 reCaptcha
Plugin URI: https://f13.dev/wordpress-plugins/wordpress-plugin-recaptcha/
Description: Add reCaptcha to your wordpress site
Version: 1.0.0
Author: Jim Valentine
Author URI: https://f13.dev
Text Domain: f13-recaptcha
*/

namespace F13\Recaptcha;

if (!isset($wpdb)) global $wpdb;
if (!function_exists('get_plugins')) require_once(ABSPATH.'wp-admin/includes/plugin.php');
if (!defined('F13_RECAPTCHA'))                      define('F13_RECAPTCHA', get_plugin_data(__FILE__, false, false));
if (!defined('F13_RECAPTCHA_PATH'))                 define('F13_RECAPTCHA_PATH', plugin_dir_path( __FILE__ ));
if (!defined('F13_RECAPTCHA_URL'))                  define('F13_RECAPTCHA_URL', plugin_dir_url(__FILE__));

if (!defined('F13_RECAPTCHA_ENABLE_NOBODY'))        define('F13_RECAPTCHA_ENABLE_NOBODY', 0);
if (!defined('F13_RECAPTCHA_ENABLE_NOT_LOGGED_IN')) define('F13_RECAPTCHA_ENABLE_NOT_LOGGED_IN', 10);
if (!defined('F13_RECAPTCHA_ENABLE_EVERYBODY'))     define('F13_RECAPTCHA_ENABLE_EVERYBODY', 20);

class Plugin
{
    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'style_and_scripts'));
        spl_autoload_register(__NAMESPACE__.'\Plugin::loader');

        if (is_admin()) {
            $a = new Controllers\Admin();
        }

        $c = new Controllers\Control();
    }

    public static function loader($name)
    {
        $name = trim(ltrim($name, '\\'));
        if (strpos($name, __NAMESPACE__) !== 0) {
            return;
        }
        $file = str_replace(__NAMESPACE__, '', $name);
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
        $file = plugin_dir_path(__FILE__).strtolower($file).'.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            die('Class not found: '.$name);
        }
    }

    public function style_and_scripts()
    {
        wp_enqueue_style('f13_recaptcha', F13_RECAPTCHA_URL.'css/f13-recaptcha.css', array(), F13_RECAPTCHA['Version']);
        wp_enqueue_script('f13_recaptcha', F13_RECAPTCHA_URL.'js/f13-recaptcha.js', array('jquery'), F13_RECAPTCHA['Version']);
    }
}

$p = new Plugin();
$p->init();