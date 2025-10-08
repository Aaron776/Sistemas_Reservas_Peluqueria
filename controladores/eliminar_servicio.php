<?php
session_start();
include __DIR__ . "/../conexion/bd.php";

// Solo admin puede eliminar servicios
if($_SESSION['rol'] !== 'admin'){
    $_SESSION['errores'] = ["No tienes permisos para eliminar servicios."];
    header("Location: ../admin/gestion_servicios.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_servicio'])){
    $id_servicio = filter_var($_POST['id_servicio'], FILTER_VALIDATE_INT);
    $errores = [];

    //----------------Validaciones---------------
    
    if(!$id_servicio || $id_servicio < 1){
        $errores[] = "ID del servicio inválido.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("DELETE FROM servicios WHERE id = :id");
        $sql->bindParam(':id', $id_servicio, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['exito'] = "El servicio se ha eliminado correctamente.";
        header("Location: ../admin/gestion_servicios.php");
        exit();
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/gestion_servicios.php");
        exit();
    }
}else{
    $_SESSION['errores'] = ["Error al eliminar el servicio."];
    header("Location: ../admin/gestion_servicios.php");
    exit();
}
?>
