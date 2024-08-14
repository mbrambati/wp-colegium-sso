<?php

// Agregar un menú en el admin para configurar la clave privada
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

// Registrar la opción de la clave privada
add_action('admin_init', 'colegium_sso_register_settings');

function colegium_sso_register_settings() {
    register_setting('colegium_sso_options_group', 'colegium_sso_private_key');
    
    add_settings_section(
        'colegium_sso_section',
        'Configuración de la Clave Privada',
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
}

function colegium_sso_private_key_render() {
    $value = get_option('colegium_sso_private_key', '');
    ?>
    <input type="text" name="colegium_sso_private_key" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}
