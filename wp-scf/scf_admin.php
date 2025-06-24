<?php
// Admin settings for Simple Contact Form

add_action('admin_menu', 'scf_add_admin_menu');
add_action('admin_init', 'scf_settings_init');

function scf_add_admin_menu() {
    add_options_page('Simple Contact Form', 'Contact Form', 'manage_options', 'scf_settings', 'scf_options_page');
}

function scf_settings_init() {
    register_setting('scf_plugin', 'scf_settings');

    add_settings_section(
        'scf_plugin_section',
        __('Form & SMTP Settings', 'scf'),
        null,
        'scf_plugin'
    );

    add_settings_field('form_title', 'Form Title', 'scf_field_input', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'form_title']);
    add_settings_field('recipient_email', 'Recipient Email', 'scf_field_input', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'recipient_email']);
    add_settings_field('smtp_host', 'SMTP Host', 'scf_field_input', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'smtp_host']);
    add_settings_field('smtp_port', 'SMTP Port', 'scf_field_input', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'smtp_port']);
    add_settings_field('smtp_user', 'SMTP Username', 'scf_field_input', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'smtp_user']);
    add_settings_field('smtp_password', 'SMTP Password', 'scf_field_password', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'smtp_password']);
    add_settings_field('smtp_encryption', 'SMTP Encryption', 'scf_field_select', 'scf_plugin', 'scf_plugin_section', ['label_for' => 'smtp_encryption']);
}

function scf_field_input($args) {
    $options = get_option('scf_settings');
    $value = isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '';
    echo "<input type='text' name='scf_settings[{$args['label_for']}]' value='{$value}' class='regular-text'>";
}

function scf_field_password($args) {
    $options = get_option('scf_settings');
    $value = isset($options[$args['label_for']]) ? esc_attr(scf_decrypt($options[$args['label_for']])) : '';
    echo "<input type='password' name='scf_settings[{$args['label_for']}]' value='{$value}' class='regular-text'>";
}

function scf_field_select($args) {
    $options = get_option('scf_settings');
    $selected = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    echo "<select name='scf_settings[{$args['label_for']}]'>
        <option value='' " . selected($selected, '', false) . ">None</option>
        <option value='ssl' " . selected($selected, 'ssl', false) . ">SSL</option>
        <option value='tls' " . selected($selected, 'tls', false) . ">TLS</option>
    </select>";
}

function scf_options_page() {
    ?>
    <div class="wrap">
        <h1>Simple Contact Form Settings</h1>
        <form action='options.php' method='post'>
            <?php
            settings_fields('scf_plugin');
            do_settings_sections('scf_plugin');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
