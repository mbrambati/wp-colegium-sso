<?php
/**
 * Plugin Name: Colegium SSO
 * Description: Plugin de SSO para WordPress que permite la autenticación utilizando JWT.
 * Version: 1.0.0
 * Author: Tu Nombre
 * License: GPL2
 */

// Asegúrate de que el archivo no se ejecute directamente
if (!defined('ABSPATH')) {
    exit;
}

// Incluir el archivo de opciones
require_once plugin_dir_path(__FILE__) . 'options.php';

// Registrar el endpoint de la API REST
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/sso', array(
        'methods' => 'GET',
        'callback' => 'handle_sso_login',
        'permission_callback' => '__return_true',
    ));
});

// Función para manejar el SSO login
function handle_sso_login(WP_REST_Request $request) {
    $token = $request->get_param('token');

    // Obtener la clave privada desde las opciones del plugin
    $key = get_option('colegium_sso_private_key', '');

    if (!$key) {
        return new WP_REST_Response('Clave privada no configurada', 500);
    }

    if ($token) {
        $payload = validateJwt($token, $key);
        if ($payload) {
            $email = $payload['email'];
            $user = get_user_by('email', $email);

            if ($user) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                wp_redirect(admin_url());
                exit;
            } else {
                return new WP_REST_Response('Usuario no encontrado', 404);
            }
        } else {
            return new WP_REST_Response('Token inválido o expirado', 403);
        }
    } else {
        return new WP_REST_Response('Token no proporcionado', 400);
    }
}

// Funciones para crear y validar JWTs
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function validateJwt($jwt, $key) {
    list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);
    
    $header = json_decode(base64UrlDecode($headerEncoded), true);
    $payload = json_decode(base64UrlDecode($payloadEncoded), true);
    $signature = base64UrlDecode($signatureEncoded);
    
    $validSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $key, true);
    
    if (hash_equals($signature, $validSignature)) {
        if ($payload['exp'] < time()) {
            return false;  // Token expirado
        }
        return $payload;
    }
    return false;
}
