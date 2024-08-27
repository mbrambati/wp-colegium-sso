<?php

// Agregar un menú en el admin para configurar la clave privada y la URL de redirección
add_action('admin_menu', 'colegium_sso_menu');

function colegium_sso_menu() {
    add_options_page(
        'Configuración Colegium SSO',
        'Colegium SSO',
        'manage_options',
        'colegium-sso',
        'colegium_sso_options_page'
    );
}

// Mostrar la página de opciones
function colegium_sso_options_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de Colegium SSO</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('colegium_sso_options_group');
            do_settings_sections('colegium-sso');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar las opciones de la clave privada y la URL de redirección
add_action('admin_init', 'colegium_sso_register_settings');

function colegium_sso_register_settings() {
    register_setting('colegium_sso_options_group', 'colegium_sso_private_key');
    register_setting('colegium_sso_options_group', 'colegium_sso_redirect_url');
    
    add_settings_section(
        'colegium_sso_section',
        'Configuración de la Clave Privada y Redirección',
        null,
        'colegium-sso'
    );
    
    add_settings_field(
        'colegium_sso_private_key',
        'Clave Privada',
        'colegium_sso_private_key_render',
        'colegium-sso',
        'colegium_sso_section'
    );

    add_settings_field(
        'colegium_sso_redirect_url',
        'URL de Redirección',
        'colegium_sso_redirect_url_render',
        'colegium-sso',
        'colegium_sso_section'
    );
}

function colegium_sso_private_key_render() {
    $value = get_option('colegium_sso_private_key', '');
    ?>
    <input type="text" name="colegium_sso_private_key" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

function colegium_sso_redirect_url_render() {
    $value = get_option('colegium_sso_redirect_url', admin_url());
    ?>
    <input type="text" name="colegium_sso_redirect_url" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <p class="description">Ingrese la URL donde desea redirigir a los usuarios después del inicio de sesión. El valor predeterminado es el panel de administración.</p>
    <?php
}
