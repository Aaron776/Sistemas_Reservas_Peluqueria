<?php
session_start();
include __DIR__ . "/../conexion/bd.php";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['precio']) && isset($_POST['duracion'])){
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = trim($_POST['precio']);
    $duracion = trim($_POST['duracion']);
    $errores = [];

    // ----------------Validaciones---------------
    if (empty($nombre)) {
        $errores[] = "El nombre es requerido";
    }elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener menos de 100 caracteres";
    }elseif (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios";
    }elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }elseif ($nombre !== strip_tags($nombre)) {
        $errores[] = 'No se permiten etiquetas HTML en el nombre';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $nombre)) {
        $errores[] = 'El nombre contiene contenido no permitido';
    }

    if (empty($descripcion)) {
        $errores[] = "La descripción es requerida";
    }elseif (strlen($descripcion) > 1000) {
        $errores[] = "La descripción debe tener menos de 1000 caracteres";
    }elseif ($descripcion !== strip_tags($descripcion)) {
        $errores[] = 'No se permiten etiquetas HTML en la descripción';
    }elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $descripcion)) {
        $errores[] = 'La descripción contiene contenido no permitido';
    }


    if (empty($precio)) {
        $errores[] = "El precio es requerido";
    }elseif (!is_numeric($precio)) {
        $errores[] = "El precio debe ser un número";
    }elseif ($precio < 0) {
        $errores[] = "El precio no puede ser negativo";
    }

    if (empty($duracion)) {
        $errores[] = "La duración es requerida";
    }elseif (!is_numeric($duracion)) {
        $errores[] = "La duración debe ser un número";
    }elseif ($duracion < 0) {
        $errores[] = "La duración no puede ser negativa";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("INSERT INTO servicios (nombre, descripcion, duracion, precio) VALUES (:nombre, :descripcion, :duracion, :precio)");
        $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $sql->bindParam(':duracion', $duracion, PDO::PARAM_INT);
        $sql->bindParam(':precio', $precio);
        $sql->execute();

        $_SESSION['exito'] = "Servicio agregado correctamente";
        header("Location: ../admin/agregar_servicio.php");
        exit;
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/agregar_servicio.php");
        exit;
    }

}else{
    $_SESSION['errores'] = "Error al agregar el servicio";
    header("Location: ../admin/agregar_servicio.php");
    exit;
}




?>