<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

// Verificar que el usuario esté logueado
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente'){
    $_SESSION['errores'] = ["Debes iniciar sesión para reservar una cita."];
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" 
    && isset($_POST['id_usuario'], $_POST['fecha'], $_POST['hora'], $_POST['servicio_id'])) {

    $id_usuario  = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
    $id_servicio = filter_var($_POST['servicio_id'], FILTER_VALIDATE_INT);
    $fecha       = trim($_POST['fecha']);
    $hora        = trim($_POST['hora']);
    $errores = [];

    //---------------- VALIDACIONES ----------------

    // Validar que el ID del usuario sea el del logueado
    if ($id_usuario !== $_SESSION['id_usuario']) {
        $errores[] = "No puedes reservar citas para otro usuario.";
    }

    if (!$id_servicio) {
        $errores[] = "El servicio no es válido.";
    } else {
        // Validar que el servicio exista
        $check_servicio = $conexion->prepare("SELECT id FROM servicios WHERE id = :id_servicio");
        $check_servicio->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
        $check_servicio->execute();
        if(!$check_servicio->fetch()){
            $errores[] = "El servicio seleccionado no existe.";
        }
    }

    // Validación de fecha
    if (empty($fecha)) {
        $errores[] = "La fecha es obligatoria.";
    } else {
        $fecha_valida = DateTime::createFromFormat('Y-m-d', $fecha);
        if (!$fecha_valida || $fecha_valida->format('Y-m-d') !== $fecha) {
            $errores[] = "La fecha no tiene un formato válido (YYYY-MM-DD).";
        } elseif ($fecha_valida < new DateTime('today')) {
            $errores[] = "No puedes reservar una cita en una fecha pasada.";
        }
    }

    // Validación de hora
    if (empty($hora)) {
        $errores[] = "La hora es obligatoria.";
    } elseif (!preg_match("/^(?:[01]\d|2[0-3]):[0-5]\d$/", $hora)) {
        $errores[] = "La hora no tiene un formato válido (HH:MM en 24h).";
    } else {
        $hora_dt = DateTime::createFromFormat('H:i', $hora);
        $hora_min = DateTime::createFromFormat('H:i', '08:00');
        $hora_max = DateTime::createFromFormat('H:i', '20:00');
        if ($hora_dt < $hora_min || $hora_dt > $hora_max) {
            $errores[] = "Solo puedes reservar citas entre las 08:00 y las 20:00 horas.";
        }
    }

    // Validar disponibilidad
    if (empty($errores)) {
        $check = $conexion->prepare("SELECT id FROM citas WHERE fecha = :fecha AND hora = :hora");
        $check->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $check->bindParam(':hora', $hora, PDO::PARAM_STR);
        $check->execute();
        if ($check->fetch()) {
            $errores[] = "Ya existe una cita registrada en esa fecha y hora.";
        }
    }

    // Procesar inserción
    if (empty($errores)) {
        $sql = $conexion->prepare(
            "INSERT INTO citas (usuario_id, servicio_id, fecha, hora) 
             VALUES (:id_usuario, :id_servicio, :fecha, :hora)"
        );
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
        $sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $sql->bindParam(':hora', $hora, PDO::PARAM_STR);
        $sql->execute();

        $id_cita = $conexion->lastInsertId();
        header("Location: ../cliente/confirmar_cita.php?id_cita=$id_cita");
        exit();
    } else {
        $_SESSION['errores'] = $errores;
        header("Location: ../cliente/reservar_cita.php");
        exit();
    }

} else {
    $_SESSION['errores'] = "Error en la solicitud.";
    header("Location: ../cliente/reservar_cita.php");
    exit();
}
?>
