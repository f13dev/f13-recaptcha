=== Plugin Name ===
Contributors: f13dev
Tags: recaptcha, comments, spam, captcha
Requires at least: 5.0
Tested up to: 5.8.1
Stable tag: 1.0.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add Google reCaptcha to the comments section on blog posts. Additional hooks for adding reCaptcha to custom forms.

== Description ==

Add Google reCaptcha to the comments section on blog posts. Requires Google reCaptcha v2 Checkbox API key.

Simple configuration via the admin settings page:
* reCaptcha public key
* reCaptcha private key
* enable reCaptcha for (Everyone | Visitors | Nobody [disabled])

Additional hooks for programmers:
$v = apply_filters('f13_recaptcha_add');
Will place a reCaptcha checkbox in the desired place.

$validate = apply_filters('f13_recaptcha_validate');
if (!empty($validate)) {
    $v = $validate
    // reCaptcha failed
} else {
    // reCaptcah passed
}