<?php
// Frontend form for Simple Contact Form

add_shortcode('simple_contact_form', 'scf_render_form');
add_action('init', 'scf_handle_form_submission');

function scf_render_form() {
    ob_start();
    ?>
    <form method="post">
        <label>Name:<br><input type="text" name="scf_name" required></label><br>
        <label>Email:<br><input type="email" name="scf_email" required></label><br>
        <label>Message:<br><textarea name="scf_message" required></textarea></label><br>
        <?php wp_nonce_field('scf_submit_form', 'scf_nonce'); ?>
        <label>What is 3 + 4? (Anti-spam)<br><input type="text" name="scf_captcha" required></label><br>
        <input type="submit" name="scf_submit" value="Send">
    </form>
    <?php
    return ob_get_clean();
}

function scf_handle_form_submission() {
    if (!isset($_POST['scf_submit'])) return;
    if (!isset($_POST['scf_nonce']) || !wp_verify_nonce($_POST['scf_nonce'], 'scf_submit_form')) return;

    if (trim($_POST['scf_captcha']) !== '7') {
        wp_die('Captcha failed.');
    }

    $options = get_option('scf_settings');

    $to = isset($options['recipient_email']) ? $options['recipient_email'] : get_option('admin_email');
    $subject = 'New Contact Form Submission';
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    $body = sprintf("Name: %s<br>Email: %s<br>Message:<br>%s",
        esc_html($_POST['scf_name']),
        esc_html($_POST['scf_email']),
        nl2br(esc_html($_POST['scf_message']))
    );

    wp_mail($to, $subject, $body, $headers);
    wp_redirect(home_url('/thank-you'));
    exit;
}
