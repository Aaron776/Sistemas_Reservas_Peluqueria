<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";
include __DIR__ . "/../conexion/bd.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])){
    $email = htmlspecialchars(trim($_POST['email']));
    $errores = [];

    //Validaciones
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }


    $sql = $conexion->prepare("SELECT email FROM usuarios WHERE email = :email");
    $sql->bindParam(':email', $email, PDO::PARAM_STR);
    $sql->execute();
    $usuario = $sql->fetch(PDO::FETCH_OBJ);

    if(empty($errores) && $usuario){// Si el usuario existe en la base de datos
        // Generar código aleatorio de 8 caracteres
        $nuevaPassword = substr(bin2hex(random_bytes(4)), 0, 8);
         // Hashear el código correcto
         $codigoHasheado = password_hash($nuevaPassword, PASSWORD_DEFAULT);

        $sql = $conexion->prepare("UPDATE usuarios SET password = :password WHERE email = :email");
        $sql->bindParam(':password', $codigoHasheado);
        $sql->bindParam(':email', $email);
        $sql->execute();

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'patriciortiz1996@gmail.com'; // Tu correo
            $mail->Password   = 'omdq shpx yixk cujk';    // Contraseña o App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('aronortiz759@gmail.com', 'Sistema de Reservas StyleCut');
            $mail->addAddress($usuario->email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - Sistema de Reservas StyleCut';
            $mail->Body    = "
                <h2>Recuperación de contraseña</h2>
                <p>Hola, has solicitado recuperar tu contraseña.</p>
                <p>Tu nueva contraseña es: <b>{$nuevaPassword}</b></p>
                <p>Te recomendamos cambiarla después de iniciar sesión.</p>
            ";

            $mail->send();
            $_SESSION['exito'] = "¡Correo enviado! Por favor revisa tu bandeja de entrada.";
        } catch (Exception $e) {
            $_SESSION['errores'] = ["Error al enviar el correo: {$mail->ErrorInfo}"];
        }
    }else{
        $_SESSION['errores'] = $errores;
        exit;
    }

    header("Location: ../olvido_password.php");
    exit();

}else{
    $_SESSION['errores'] = "Error en la solicitud";
    header("Location: ../olvido_password.php");
    exit;
}

?>