<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';
include_once __DIR__ . '/../config/env.php'; // 🔒 Cargar variables seguras desde .env

// Configuración segura
$client_id = $_ENV['GITHUB_CLIENT_ID'];
$client_secret = $_ENV['GITHUB_CLIENT_SECRET'];
$redirect_uri = $_ENV['GITHUB_REDIRECT_URI'];

// Verificar estado para prevenir CSRF
if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['github_oauth_state']) {
    $_SESSION['errores'] = ["Error de seguridad en la autenticación."];
    header("Location: ../login.php");
    exit;
}

// Verificar que tenemos el código
if (!isset($_GET['code'])) {
    $_SESSION['errores'] = ["No se recibió el código de autorización."];
    header("Location: ../login.php");
    exit;
}

$code = $_GET['code'];

// Intercambiar código por token de acceso
$token_url = 'https://github.com/login/oauth/access_token';
$token_data = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $code,
    'redirect_uri' => $redirect_uri
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$response = curl_exec($ch);
curl_close($ch);

$token_info = json_decode($response, true);

if (!isset($token_info['access_token'])) {
    $_SESSION['errores'] = ["Error al obtener el token de acceso."];
    header("Location: ../login.php");
    exit;
}

$access_token = $token_info['access_token'];

// Obtener información del usuario
$user_url = 'https://api.github.com/user';
$ch = curl_init($user_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'User-Agent: StyleCut-App'
]);
$user_response = curl_exec($ch);
curl_close($ch);

$github_user = json_decode($user_response, true);

// Obtener email si no está público
$email = $github_user['email'];
if (empty($email)) {
    $emails_url = 'https://api.github.com/user/emails';
    $ch = curl_init($emails_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'User-Agent: StyleCut-App'
    ]);
    $emails_response = curl_exec($ch);
    curl_close($ch);
    
    $emails = json_decode($emails_response, true);
    foreach ($emails as $email_data) {
        if ($email_data['primary'] && $email_data['verified']) {
            $email = $email_data['email'];
            break;
        }
    }
}

if (empty($email)) {
    $_SESSION['errores'] = ["No se pudo obtener el email de GitHub."];
    header("Location: ../login.php");
    exit;
}

// Buscar o crear usuario
$sql = $conexion->prepare("SELECT id, email, nombre, rol FROM usuarios WHERE email = :email");
$sql->bindParam(':email', $email, PDO::PARAM_STR);
$sql->execute();
$usuario = $sql->fetch(PDO::FETCH_OBJ);

if ($usuario) {
    // Usuario existe, iniciar sesión
    $_SESSION['logueado'] = true;
    $_SESSION['id_usuario'] = $usuario->id;
    $_SESSION['email'] = $usuario->email;
    $_SESSION['nombre'] = $usuario->nombre;
    $_SESSION['rol'] = $usuario->rol;
} else {
    // Crear nuevo usuario
    $nombre = $github_user['name'] ?? $github_user['login'];
    $rol = 'cliente'; // Rol por defecto
    
    $insert_sql = $conexion->prepare(
        "INSERT INTO usuarios (email, nombre, rol, github_id, password) 
         VALUES (:email, :nombre, :rol, :github_id, :password)"
    );
    
    $random_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
    
    $insert_sql->bindParam(':email', $email, PDO::PARAM_STR);
    $insert_sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $insert_sql->bindParam(':rol', $rol, PDO::PARAM_STR);
    $insert_sql->bindParam(':github_id', $github_user['id'], PDO::PARAM_INT);
    $insert_sql->bindParam(':password', $random_password, PDO::PARAM_STR);
    
    if ($insert_sql->execute()) {
        $nuevo_id = $conexion->lastInsertId();
        
        $_SESSION['logueado'] = true;
        $_SESSION['id_usuario'] = $nuevo_id;
        $_SESSION['email'] = $email;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol'] = $rol;
    } else {
        $_SESSION['errores'] = ["Error al crear el usuario."];
        header("Location: ../login.php");
        exit;
    }
}

// Redirigir según el rol
if ($_SESSION['rol'] == "admin") {
    header("Location: ../admin/dash_admin.php");
} else {
    header("Location: ../cliente/dash_cliente.php");
}
exit;
