<?php
session_start();
include_once __DIR__ . '/../conexion/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" 
    && isset($_POST['id_usuario'], $_POST['id_cita'], $_POST['fecha'], $_POST['servicio'], $_POST['hora'])) {
    
    $id_cita     = filter_var($_POST['id_cita'], FILTER_VALIDATE_INT);
    $id_usuario  = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
    $id_servicio = filter_var($_POST['servicio'], FILTER_VALIDATE_INT);
    $fecha       = trim($_POST['fecha']);
    $hora        = trim($_POST['hora']);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (!$id_cita || !$id_usuario || !$id_servicio) {
        $errores[] = "IDs inválidos para cita, usuario o servicio.";
    }

    if (empty($fecha)) {
        $errores[] = "La fecha es obligatoria.";
    } else {
        $fecha_valida = DateTime::createFromFormat('Y-m-d', $fecha);
        $hoy = new DateTime('today', new DateTimeZone('America/Guayaquil'));
        if (!$fecha_valida || $fecha_valida->format('Y-m-d') !== $fecha) {
            $errores[] = "La fecha no tiene un formato válido (YYYY-MM-DD).";
        } elseif ($fecha_valida < $hoy) {
            $errores[] = "No puedes reagendar a una fecha pasada.";
        }
    }

    if (empty($hora)) {
        $errores[] = "La hora es obligatoria.";
    } elseif (!preg_match("/^(?:[01]\d|2[0-3]):[0-5]\d$/", $hora)) {
        $errores[] = "La hora no tiene un formato válido (HH:MM en 24h).";
    } else {
        $hora_dt  = DateTime::createFromFormat('H:i', $hora);
        $hora_min = DateTime::createFromFormat('H:i', '08:00');
        $hora_max = DateTime::createFromFormat('H:i', '20:00');
        if ($hora_dt < $hora_min || $hora_dt > $hora_max) {
            $errores[] = "Solo puedes reagendar citas entre las 08:00 y las 20:00 horas.";
        }
    }

    if (empty($errores)) {
        // Validar que la cita pertenezca al usuario
        $check = $conexion->prepare("SELECT id FROM citas WHERE id=:id_cita AND usuario_id=:id_usuario");
        $check->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $check->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $check->execute();

        if (!$check->fetch()) {
            $errores[] = "La cita no existe o no pertenece al usuario.";
        }
    }

    if (empty($errores)) {
        // Validar que el servicio exista
        $checkServicio = $conexion->prepare("SELECT id FROM servicios WHERE id = :id_servicio");
        $checkServicio->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
        $checkServicio->execute();
        if (!$checkServicio->fetch()) {
            $errores[] = "El servicio seleccionado no existe.";
        }
    }

    if (empty($errores)) {
        // Validar disponibilidad
        $check2 = $conexion->prepare("SELECT id FROM citas WHERE fecha=:fecha AND hora=:hora AND id != :id_cita");
        $check2->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $check2->bindParam(':hora', $hora, PDO::PARAM_STR);
        $check2->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $check2->execute();

        if ($check2->fetch()) {
            $errores[] = "Ya existe otra cita registrada en esa fecha y hora.";
        }
    }

    // ---------------- PROCESAR UPDATE ----------------
    if (empty($errores)) {
        $sql = $conexion->prepare("UPDATE citas 
            SET fecha=:fecha, hora=:hora, servicio_id=:id_servicio  
            WHERE id=:id_cita AND usuario_id=:id_usuario");
        $sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $sql->bindParam(':hora', $hora, PDO::PARAM_STR);
        $sql->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
        $sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['exito'] = "Cita reagendada con éxito.";
        header("Location: ../cliente/historial_citas.php");
        exit();
    } else {
        $_SESSION['errores'] = $errores;
        header("Location: ../cliente/reagendar_cita.php?id_cita=$id_cita");
        exit();
    }

} else {
    $_SESSION['errores'] = ["Error en la solicitud."];
    header("Location: ../cliente/historial_citas.php");
    exit();
}
?>
