<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cita']) && isset($_POST['estado'])){
    $id_cita = trim($_POST['id_cita']);
    $estado = trim($_POST['estado']);
    $errores = [];

    //Validaciones
    if (empty($id_cita)) {
        $errores[] = "El ID de la cita es obligatorio.";
    }

    if (empty($estado)) {
        $errores[] = "El estado de la cita es obligatorio.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("UPDATE citas SET estado = :estado WHERE id = :id_cita");
        $sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $sql->bindParam(':estado', $estado, PDO::PARAM_STR);
        $sql->execute();

        $_SESSION['exito'] = "El estado de la cita se ha actualizado correctamente.";
        header("Location: ../admin/gestion_citas.php");
        exit;

    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/editar_estado_cita.php");
        exit;
    }


}else{
    echo "Error en la solicitud";
    exit;
}


?>