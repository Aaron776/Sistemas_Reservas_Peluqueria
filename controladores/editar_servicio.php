<?php
session_start();
include __DIR__ . "/../conexion/bd.php";

// Solo admin puede editar servicios
if($_SESSION['rol'] !== 'admin'){
    $_SESSION['errores'] = ["No tienes permisos para realizar esta acción."];
    header("Location: ../admin/gestion_servicios.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['duracion'])){

    $id_servicio = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = trim($_POST['precio']);
    $duracion = trim($_POST['duracion']);
    $errores = [];

    // ----------------Validaciones---------------
    if (!$id_servicio) {
        $errores[] = "ID de servicio inválido.";
    }

    if (empty($nombre)) {
        $errores[] = "El nombre es requerido";
    } elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener menos de 100 caracteres";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios";
    } elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    } elseif ($nombre !== strip_tags($nombre)) {
        $errores[] = 'No se permiten etiquetas HTML en el nombre';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $nombre)) {
        $errores[] = 'El nombre contiene contenido no permitido';
    }

    if (empty($descripcion)) {
        $errores[] = "La descripción es requerida";
    } elseif (strlen($descripcion) > 1000) {
        $errores[] = "La descripción debe tener menos de 1000 caracteres";
    } elseif ($descripcion !== strip_tags($descripcion)) {
        $errores[] = 'No se permiten etiquetas HTML en la descripción';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $descripcion)) {
        $errores[] = 'La descripción contiene contenido no permitido';
    }

    if (empty($precio)) {
        $errores[] = "El precio es requerido";
    } elseif (!is_numeric($precio)) {
        $errores[] = "El precio debe ser un número";
    } elseif ($precio < 0) {
        $errores[] = "El precio no puede ser negativo";
    }

    if (empty($duracion)) {
        $errores[] = "La duración es requerida";
    } elseif (!is_numeric($duracion)) {
        $errores[] = "La duración debe ser un número";
    } elseif ($duracion < 0) {
        $errores[] = "La duración no puede ser negativa";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("UPDATE servicios SET nombre = :nombre, descripcion = :descripcion, duracion = :duracion, precio = :precio WHERE id = :id");
        $sql->bindParam(':id', $id_servicio, PDO::PARAM_INT);
        $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $sql->bindParam(':duracion', $duracion, PDO::PARAM_INT);
        $sql->bindParam(':precio', $precio, PDO::PARAM_STR);
        $sql->execute();

        $_SESSION['exito'] = "Servicio editado exitosamente";
        header("Location: ../admin/editar_servicio.php?id_servicio=$id_servicio");
        exit;
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/editar_servicio.php?id_servicio=$id_servicio");
        exit;
    }

}else{
    $_SESSION['errores'] = ["Error al editar el servicio"];
    header("Location: ../admin/editar_servicio.php");
    exit;
}
?>
