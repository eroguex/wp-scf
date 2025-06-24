<?php
// Admin settings page for Simple Contact Form

add_action('admin_menu', 'scf_add_admin_menu');
add_action('admin_init', 'scf_register_settings');

function scf_add_admin_menu() {
    add_options_page(
        'Simple Contact Form',
        'Simple Contact Form',
        'manage_options',
        'simple-contact-form',
        'scf_settings_page'
    );
}

function scf_register_settings() {
    register_setting('scf_settings_group', 'scf_settings');
}

function scf_settings_page() {
    $options = get_option('scf_settings');
    ?>
    <div class="wrap">
        <h1>Simple Contact Form Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('scf_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Recipient Email</th>
                    <td><input type="email" name="scf_settings[recipient_email]" value="<?php echo esc_attr($options['recipient_email'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTP Host</th>
                    <td><input type="text" name="scf_settings[smtp_host]" value="<?php echo esc_attr($options['smtp_host'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTP Port</th>
                    <td><input type="number" name="scf_settings[smtp_port]" value="<?php echo esc_attr($options['smtp_port'] ?? ''); ?>" class="small-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTP Username</th>
                    <td><input type="text" name="scf_settings[smtp_user]" value="<?php echo esc_attr($options['smtp_user'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTP Password</th>
                    <td><input type="password" name="scf_settings[smtp_password]" value="" placeholder="••••••••" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Encryption</th>
                    <td>
                        <select name="scf_settings[smtp_encryption]">
                            <option value="">None</option>
                            <option value="ssl" <?php selected($options['smtp_encryption'] ?? '', 'ssl'); ?>>SSL</option>
                            <option value="tls" <?php selected($options['smtp_encryption'] ?? '', 'tls'); ?>>TLS</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>
