<?php
session_start();

// Incluimos el archivo de conexión partiendo SIEMPRE desde la carpeta actual
include_once __DIR__ . '/../conexion/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nombre']) && isset($_POST['telefono'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $errores=[];

    // ---------------- VALIDACIONES ----------------
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre no debe superar los 100 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
        $errores[] = "El nombre solo debe contener letras y espacios.";
    }elseif ($nombre !== strip_tags($nombre)) {
        $errores[] = 'No se permiten etiquetas HTML en el nombre';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $nombre)) {
        $errores[] = 'El nombre contiene contenido no permitido';
    }

    
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    } elseif (strlen($email) > 255) {
        $errores[] = "El email es demasiado largo.";
    }

   
    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 5) {
        $errores[] = "La contraseña debe tener al menos 5 caracteres.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])/',$password)) {
        $errores[] = "La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.";
    }
    

    if(!preg_match('/^[0-9]{10}$/', $telefono)) {
        $errores[] = "El teléfono debe tener exactamente 10 dígitos numéricos.";
    }
    

    // Verificar duplicados
    if (empty($errores)) {
        try {
            $duplicado = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email OR telefono=:telefono");
            $duplicado->bindParam(':email', $email, PDO::PARAM_STR);
            $duplicado->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $duplicado->execute();

            if ($duplicado->fetchColumn() > 0) {
                $errores[] = "El email y/o telefono ya está registrados.";
            }
        } catch (PDOException $e) {
            error_log("Error verificando duplicados: " . $e->getMessage());
            $errores[] = "Error interno. Intenta nuevamente.";
        }
    }

    if(empty($errores)){
        
        $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

        $sql=$conexion->prepare("INSERT INTO usuarios (nombre, email,telefono, password) VALUES (:nombre, :email, :telefono, :password)");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':password', $password_encriptada, PDO::PARAM_STR);
        $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $sql->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $sql->execute();

        $_SESSION['exito'] = "Usuario registrado correctamente";
        header("Location: ../registro_usuario.php");
        exit;  
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../registro_usuario.php");
        exit;
    }
}else{
    $_SESSION['errores'] = "Error interno. Intenta nuevamente.";
    header("Location: ../registro_usuario.php");
    exit;
}



?>