<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

// Verificar que el usuario esté logueado y sea cliente
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente'){
    $_SESSION['errores'] = ["No tienes permisos para cancelar esta cita."];
    header("Location: ../login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cita'])){
    $id_cita = filter_var($_POST['id_cita'], FILTER_VALIDATE_INT);
    $errores = [];

    // Validaciones
    if(!$id_cita || $id_cita < 1){
        $errores[] = "ID de cita inválido.";
    }

    // Verificar que la cita pertenece al usuario actual
    $sql_check = $conexion->prepare("SELECT id FROM citas WHERE id = :id_cita AND usuario_id = :id_usuario");
    $sql_check->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
    $sql_check->bindParam(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
    $sql_check->execute();
    $cita = $sql_check->fetch(PDO::FETCH_OBJ);

    if(!$cita){
        $errores[] = "No se encontró la cita o no tienes permisos para cancelarla.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("DELETE FROM citas WHERE id = :id_cita");
        $sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['exito'] = "La cita se ha cancelado correctamente.";
        header("Location: ../cliente/historial_citas.php");
        exit();
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../cliente/historial_citas.php");
        exit();
    }

}else{
    $_SESSION['errores'] = ["Error en la solicitud."];
    header("Location: ../cliente/historial_citas.php");
    exit();
}
?>
