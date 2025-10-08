<?php
session_start();
include __DIR__ . "/../conexion/bd.php";

// Solo admin puede eliminar usuarios
if ($_SESSION['rol'] !== 'admin') {
    $_SESSION['errores'] = ["No tienes permisos para realizar esta acción."];
    header("Location: ../admin/gestion_usuarios.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_usuario'])){

    $id_usuario = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (!$id_usuario) {
        $errores[] = "ID de usuario inválido.";
    }

    // Evitar que un admin se borre a sí mismo
    if($id_usuario == $_SESSION['id_usuario']){
        $errores[] = "No puedes eliminar tu propio usuario.";
    }

    // Verificar que el usuario exista
    $sql_check = $conexion->prepare("SELECT id FROM usuarios WHERE id = :id");
    $sql_check->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $sql_check->execute();
    if(!$sql_check->fetch(PDO::FETCH_OBJ)){
        $errores[] = "El usuario no existe.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("DELETE FROM usuarios WHERE id = :id");
        $sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['exito'] = "El usuario se ha eliminado correctamente";
        header("Location: ../admin/gestion_usuarios.php");
        exit();
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../admin/gestion_usuarios.php");
        exit();
    }

}else{
    $_SESSION['errores'] = ["Error en la solicitud"];
    header("Location: ../admin/gestion_usuarios.php");
    exit();
}
?>
