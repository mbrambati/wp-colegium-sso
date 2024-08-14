<?php

// Agregar un menú en el admin para configurar la clave privada
add_action('admin_menu', 'wp_colegium_sso_menu');

function wp_colegium_sso_menu() {
    add_options_page(
        'Configuración WP Colegium SSO',
        'WP Colegium SSO',
        'manage_options',
        'wp-colegium-sso',
        'wp_colegium_sso_options_page'
    );
}

// Mostrar la página de opciones
function wp_colegium_sso_options_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de WP Colegium SSO</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_colegium_sso_options_group');
            do_settings_sections('wp-colegium-sso');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar la opción de la clave privada
add_action('admin_init', 'wp_colegium_sso_register_settings');

function wp_colegium_sso_register_settings() {
    register_setting('wp_colegium_sso_options_group', 'wp_colegium_sso_private_key');
    
    add_settings_section(
        'wp_colegium_sso_section',
        'Configuración de la Clave Privada',
        null,
        'wp-colegium-sso'
    );
    
    add_settings_field(
        'wp_colegium_sso_private_key',
        'Clave Privada',
        'wp_colegium_sso_private_key_render',
        'wp-colegium-sso',
        'wp_colegium_sso_section'
    );
}

function wp_colegium_sso_private_key_render() {
    $value = get_option('wp_colegium_sso_private_key', '');
    ?>
    <input type="text" name="wp_colegium_sso_private_key" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}
