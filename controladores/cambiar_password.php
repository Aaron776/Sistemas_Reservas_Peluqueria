<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php'; // Incluimos el archivo de conexión partiendo desde la carpeta actual

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password_actual']) && isset($_POST['id_usuario']) && isset($_POST['password_nueva'])) {

    $password_actual = htmlspecialchars(trim($_POST['password_actual']));
    $password_nueva = htmlspecialchars(trim($_POST['password_nueva']));
    $id_usuario = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (!$id_usuario) {
        $errores[] = "ID de usuario inválido.";
    }
    if (empty($password_actual)) {
        $errores[] = "La contraseña actual es obligatoria.";
    }
    if (empty($password_nueva)) {
        $errores[] = "La contraseña nueva es obligatoria.";
    }

    if (empty($errores)) {
        // Verificar que el usuario exista
        $sql = $conexion->prepare("SELECT password FROM usuarios WHERE id = :id");
        $sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $sql->execute();
        $usuario = $sql->fetch(PDO::FETCH_OBJ);

        if (!$usuario) {
            $errores[] = "Usuario no encontrado.";
        } else {
            // Verificar que la contraseña actual sea correcta
            if (!password_verify($password_actual, $usuario->password)) {
                $errores[] = "La contraseña actual no es correcta.";
            }

            // Evitar que la nueva contraseña sea igual a la actual
            if (password_verify($password_nueva, $usuario->password)) {
                $errores[] = "La contraseña nueva debe ser diferente a la actual.";
            }
        }
    }

    // ---------------- PROCESAR CAMBIO ----------------
    if (empty($errores)) {
        $password_nueva_encriptada = password_hash($password_nueva, PASSWORD_DEFAULT);
        $sql = $conexion->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
        $sql->bindParam(':password', $password_nueva_encriptada);
        $sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['exito'] = "La contraseña se ha cambiado correctamente";
        header("Location: ../cambiar_password.php");
        exit;
    } else {
        $_SESSION['errores'] = $errores;
        header("Location: ../cambiar_password.php");
        exit;
    }

} else {
    echo "Error en la solicitud";
    exit;
}
