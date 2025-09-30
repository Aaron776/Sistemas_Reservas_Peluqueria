<?php
session_start();

include __DIR__ . "/../conexion/bd.php";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_usuario'])){
    $id_usuario = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (!$id_usuario) {
        $errores[] = "ID de usuario inválido.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("DELETE FROM usuarios WHERE id = :id");
        $sql->bindParam(':id', $id_usuario);
        $sql->execute();

        $_SESSION['exito'] = "El usuario se ha eliminado correctamente";
        header("Location: ../admin/gestion_usuarios.php");
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/gestion_usuarios.php");
        exit();
    }
}else{
    echo "Error en la solicitud";
    exit;
}



?>