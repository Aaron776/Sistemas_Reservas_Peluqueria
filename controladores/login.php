<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $errores=[];

    // ---------------- VALIDACIONES ----------------
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es valido.";
    }

    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("SELECT id, email,nombre,password,rol FROM usuarios WHERE email = :email");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        $usuario = $sql->fetch(PDO::FETCH_OBJ);

        if ($usuario && password_verify($password, $usuario->password)) {
            $_SESSION['logueado'] = true;
            $_SESSION['id_usuario'] = $usuario->id;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['rol'] = $usuario->rol;

            if ($_SESSION['rol'] == "admin") {
                header("Location: ../admin/dash_admin.php");
                exit;
            } else if ($_SESSION['rol'] == "cliente") {
                header("Location: ../cliente/dash_cliente.php");
                exit;
            }
        }else{
            $errores[] = "El email o la contraseña son incorrectos.";
            $_SESSION['errores'] = $errores;
            header("Location: ../login.php");
            exit;
        }
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../login.php");
        exit;
    }
}else{
    echo "Error en la solicitud";
    exit;
}
