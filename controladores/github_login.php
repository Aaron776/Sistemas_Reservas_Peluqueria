<?php
session_start();

// Cargar variables de entorno
require_once __DIR__ . '/../config/env.php'; 

// Configuración de GitHub OAuth desde variables de entorno
$client_id = $_ENV['GITHUB_CLIENT_ID'];
$redirect_uri = $_ENV['GITHUB_REDIRECT_URI'];
$scope = 'user:email';

// Generar estado aleatorio para proteger contra ataques CSRF
$state = bin2hex(random_bytes(16));
$_SESSION['github_oauth_state'] = $state;

// URL de autorización de GitHub
$auth_url = "https://github.com/login/oauth/authorize?" . http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => $scope,
    'state' => $state
]);

// Redirigir al usuario a GitHub
header("Location: $auth_url");
exit;
