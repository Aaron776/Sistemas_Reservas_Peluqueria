<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: " . __DIR__ . "/../index.php"); // si no es cliente, lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";

// Validar que venga el id_cita y sea un número
if (!isset($_GET['id_cita']) || !is_numeric($_GET['id_cita'])) {
    die("Solicitud inválida.");
}

$id_usuario = $_SESSION['id_usuario']; // ID del usuario logueado
$id_cita = (int) $_GET['id_cita'];     // ID de la cita que mando por la URL desde el controlador llamado de confirmar_cita

// Traer datos de la cita registrada solo si pertenece al usuario logueado
$sql = $conexion->prepare("
    SELECT 
        citas.fecha AS fecha, 
        citas.hora AS hora, 
        servicios.nombre AS servicio, 
        servicios.precio AS precio,
        usuarios.nombre AS cliente
    FROM citas
    INNER JOIN servicios ON citas.servicio_id = servicios.id
    INNER JOIN usuarios ON citas.usuario_id = usuarios.id
    WHERE citas.id = :id_cita AND citas.usuario_id = :id_usuario
");
$sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->execute();
$cita = $sql->fetch(PDO::FETCH_OBJ);

// Validar resultado
if (!$cita) {
    die("Cita no encontrada o no tienes permiso para verla.");
}
?>


<style>
    :root {
        --primary: #8a5a44;
        --primary-dark: #6b4634;
        --secondary: #d4b8a5;
        --accent: #c17a4a;
        --dark: #3a2e26;
        --light: #f8f4f0;
        --text: #333333;
        --success: #28a745;
        --warning: #ffc107;
    }

    body {
        background-color: #f4f6f9;
        color: var(--text);
        line-height: 1.6;
        min-height: 100vh;
        padding: 20px;
    }

    .confirmation-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .confirmation-header {
        background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
        color: white;
        padding: 30px;
        border-radius: 15px 15px 0 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .confirmation-header::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        animation: float 20s infinite linear;
    }

    @keyframes float {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(-20px, -20px) rotate(360deg); }
    }

    .confirmation-icon {
        font-size: 60px;
        margin-bottom: 15px;
        display: block;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    .confirmation-header h1 {
        font-size: 28px;
        margin-bottom: 10px;
        position: relative;
    }

    .confirmation-header p {
        opacity: 0.9;
        font-size: 16px;
        position: relative;
    }

    .confirmation-content {
        background-color: white;
        padding: 30px;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .reservation-details {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
        border-left: 4px solid var(--success);
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-item:last-child {
        border-bottom: none;
        font-weight: bold;
        font-size: 18px;
        color: var(--primary);
    }

    .detail-label {
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-value {
        color: var(--text);
        text-align: right;
    }

    .price {
        color: var(--success);
        font-weight: bold;
        font-size: 20px;
    }

    .client-info {
        background-color: var(--light);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid var(--secondary);
    }

    .client-info h3 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        text-align: center;
    }

    .btn-confirm {
        background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
        color: white;
    }

    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    .important-notes {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    .important-notes h4 {
        color: #856404;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .important-notes ul {
        margin: 0;
        padding-left: 20px;
        color: #856404;
    }

    .important-notes li {
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .action-buttons {
            grid-template-columns: 1fr;
        }
        
        .detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .detail-value {
            text-align: left;
            width: 100%;
        }
    }
</style>

<div class="confirmation-container">
        <div class="confirmation-header">
            <i class="fas fa-check-circle confirmation-icon"></i>
            <h1>¡Reserva Confirmada!</h1>
            <p>Revisa los detalles de tu cita a continuación</p>
        </div>

        <div class="confirmation-content">
            <div class="reservation-details">
                <h2 style="color: var(--primary); margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-calendar-alt"></i> Detalles de la Reserva
                </h2>
                
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fas fa-user"></i> Cliente
                    </span>
                    <span class="detail-value"><?php echo $cita->cliente; ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fas fa-scissors"></i> Servicio
                    </span>
                    <span class="detail-value"><?php echo $cita->servicio; ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fas fa-calendar-day"></i> Fecha
                    </span>
                    <span class="detail-value"><?php echo $cita->fecha; ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fas fa-clock"></i> Hora
                    </span>
                    <span class="detail-value"><?php echo  $cita->hora; ?> hrs </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fas fa-tag"></i> Precio
                    </span>
                    <span class="detail-value price">$<?php echo $cita->precio; ?></span>
                </div>
            </div>
            <div class="important-notes">
                <h4><i class="fas fa-exclamation-triangle"></i> Importante</h4>
                <ul>
                    <li>Llega 15 minutos antes de tu cita</li>
                    <li>Trae tu identificación</li>
                    <li>Cancelaciones con 24 horas de anticipación</li>
                    <li>El precio puede variar según complejidad</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="reservar_cita.php" class="btn btn-confirm">
                    <i class="fas fa-arrow-left"></i> Volver a Reservar Citas
                </a>
                
                <a href="reservar_cita.html" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Modificar Reserva
                </a>
            </div>
        </div>
    </div>
<?php include __DIR__ . "/../templates/footer.php"; ?>