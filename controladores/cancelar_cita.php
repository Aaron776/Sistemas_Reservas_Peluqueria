<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cita'])){
    $id_cita = htmlspecialchars(trim($_POST['id_cita'])); // ID de la cita a cancelar
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (!$id_cita) {
        $errores[] = "ID de cita inválido.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("DELETE FROM citas WHERE id = :id_cita");
        $sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['exito'] = "La cita se ha cancelado correctamente";
        header("Location: ../cliente/historial_citas.php");
        exit;
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../cliente/historial_citas.php");
        exit;
    }
}else{
    echo "Error en la solicitud";
    exit;
}



?>