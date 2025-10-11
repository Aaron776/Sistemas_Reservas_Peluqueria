<?php
session_start();

// Incluir configuración de entorno
include_once __DIR__ . '/../config/env.php'; // 🔒 Carga las variables del .env

// Configuración de Facebook OAuth desde .env
$app_id = $_ENV['FACEBOOK_APP_ID'];
$redirect_uri = $_ENV['FACEBOOK_REDIRECT_URI'];
$scope = 'email,public_profile';

// Generar estado para CSRF protection
$state = bin2hex(random_bytes(16));
$_SESSION['facebook_oauth_state'] = $state;

// URL de autorización de Facebook
$auth_url = "https://www.facebook.com/v18.0/dialog/oauth?" . http_build_query([
    'client_id' => $app_id,
    'redirect_uri' => $redirect_uri,
    'scope' => $scope,
    'state' => $state,
    'response_type' => 'code'
]);

// Redirigir al usuario a la autenticación de Facebook
header("Location: $auth_url");
exit;
