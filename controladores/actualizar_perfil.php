<?php
session_start();
include __DIR__ . "/../conexion/bd.php";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario']) && isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['telefono'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $id_usuario = trim($_POST['id_usuario']);
    $errores = [];

    // ---------------- VALIDACIONES ----------------

    if (empty($id_usuario)) {
        $errores[] = "ID de usuario no proporcionado.";
    } elseif (!is_numeric($id_usuario) || $id_usuario <= 0) {
        $errores[] = "ID de usuario inválido.";
    }

    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre no debe superar los 100 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios.";
    } elseif ($nombre !== strip_tags($nombre)) {
        $errores[] = "No se permiten etiquetas HTML en el nombre.";
    }

    if (empty($email)) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    } elseif (strlen($email) > 255) {
        $errores[] = "El correo electrónico es demasiado largo.";
    }

    // Teléfono (opcional, pero si existe, validar 10 dígitos)
    if (!empty($telefono) && !preg_match('/^\d{10}$/', $telefono)) {
        $errores[] = "El teléfono debe tener exactamente 10 dígitos numéricos.";
    }

     // ---------------- VERIFICAR DUPLICADOS ----------------
     if (empty($errores)) {
        try {
            $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE (email = :email OR telefono = :telefono) AND id != :id_usuario");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $duplicado = $stmt->fetchColumn();

            if ($duplicado > 0) {
                $errores[] = "El correo electrónico y/o teléfono ya están registrados en otro usuario.";
            }
        } catch (PDOException $e) {
            error_log("Error verificando duplicados: " . $e->getMessage());
            $errores[] = "Error interno. Intenta nuevamente.";
        }
    }


    if(empty($errores)){
        $sql = $conexion->prepare("UPDATE usuarios SET nombre=:nombre, email=:email,telefono=:telefono WHERE id = :id_usuario");
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR);
        $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $sql->bindParam(':telefono', $_POST['telefono'], PDO::PARAM_STR);
        $sql->execute();

        $_SESSION['exito'] = "Perfil editado con exito";
        header("Location: ../perfil.php");
        exit;
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../perfil.php");
        exit;
    }
}else{
    $_SESSION['errores'] = "Error al editar el perfil";
    header("Location: ../perfil.php");
    exit;
}
