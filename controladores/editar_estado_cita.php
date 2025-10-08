<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

// Verificar permisos de admin
if($_SESSION['rol'] !== 'admin'){
    $_SESSION['errores'] = ["No tienes permisos para actualizar el estado de la cita."];
    header("Location: ../admin/gestion_citas.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cita']) && isset($_POST['estado'])){
    $id_cita = filter_var($_POST['id_cita'], FILTER_VALIDATE_INT);
    $estado = trim($_POST['estado']);
    $errores = [];
    $estados_permitidos = ['pendiente','confirmada','cancelada'];

    //---------------- Validaciones--------------

    if(!$id_cita || $id_cita < 1){
        $errores[] = "ID de la cita inválido.";
    }
    if(!in_array($estado, $estados_permitidos)){
        $errores[] = "Estado de la cita no válido.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("UPDATE citas SET estado = :estado WHERE id = :id_cita");
        $sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $sql->bindParam(':estado', $estado, PDO::PARAM_STR);
        $sql->execute();

        $_SESSION['exito'] = "El estado de la cita se ha actualizado correctamente.";
        header("Location: ../admin/gestion_citas.php");
        exit();
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/editar_estado_cita.php?id_cita={$id_cita}");
        exit();
    }
}else{
    $_SESSION['errores'] = ["Error en la solicitud."];
    header("Location: ../admin/editar_estado_cita.php");
    exit();
}
?>
