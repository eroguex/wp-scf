<?php
/*
Plugin Name: Simple Contact Form with Captcha and SMTP
Description: A customizable contact form plugin with CAPTCHA and SMTP support.
Version: 1.0
Author: Your Name
*/

// Include required files
require_once plugin_dir_path(__FILE__) . 'scf-form.php';
require_once plugin_dir_path(__FILE__) . 'scf-admin.php';

// Load PHPMailer init hook to apply SMTP settings
add_action('phpmailer_init', 'scf_configure_phpmailer');

function scf_get_encryption_key() {
    return hash('sha256', AUTH_KEY . SECURE_AUTH_KEY . LOGGED_IN_KEY . NONCE_KEY);
}

function scf_encrypt($data) {
    $key = scf_get_encryption_key();
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function scf_decrypt($data) {
    $key = scf_get_encryption_key();
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}

function scf_configure_phpmailer($phpmailer) {
    $options = get_option('scf_settings');
    if (!empty($options['smtp_host'])) {
        $phpmailer->isSMTP();
        $phpmailer->Host = $options['smtp_host'];
        $phpmailer->Port = intval($options['smtp_port']);
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $options['smtp_user'];
        $phpmailer->Password = scf_decrypt($options['smtp_password']);
        $phpmailer->SMTPSecure = $options['smtp_encryption'];
    }
}

// Auto-encrypt SMTP password before saving settings
add_filter('pre_update_option_scf_settings', 'scf_encrypt_password_on_save', 10, 2);

function scf_encrypt_password_on_save($new, $old) {
    if (!empty($new['smtp_password']) && base64_decode($new['smtp_password'], true) === false) {
        $new['smtp_password'] = scf_encrypt($new['smtp_password']);
    }
    return $new;
}
