<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

if ($_SESSION['rol'] !== 'cliente') {
    header("Location: " . __DIR__ . "/../index.php");
    exit();
}

include_once __DIR__ . '/../templates/header.php';
include_once __DIR__ . '/../conexion/bd.php';

// Validar que venga el id_cita
if (!isset($_GET['id_cita']) || !is_numeric($_GET['id_cita'])) {
    die("Solicitud inválida");
}
$id_cita = $_GET['id_cita']; // ID de la cita que mando por la URL para reagendar
$id_usuario = $_SESSION['id_usuario']; // ID del usuario actual

// Traer los servicios de la base de datos
$sql = $conexion->prepare("SELECT id,nombre FROM servicios");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);

// Obtener los datos de la cita actual para poder reagendarla
$sql = $conexion->prepare("
    SELECT 
        citas.id AS id_cita, 
        citas.fecha AS fecha, 
        citas.hora AS hora,
        citas.estado AS estado, 
        servicios.nombre AS servicio, 
        servicios.precio AS precio,
        citas.servicio_id AS servicio_id
    FROM citas
    INNER JOIN servicios ON citas.servicio_id = servicios.id
    INNER JOIN usuarios ON citas.usuario_id = usuarios.id
    WHERE citas.id = :id_cita AND citas.usuario_id = :id_usuario
");
$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
$sql->execute();
$cita = $sql->fetch(PDO::FETCH_OBJ);

// Validar que la cita exista y pertenezca al usuario
if (!$cita) {
    die("Cita no encontrada");
    exit();
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
        --error: #dc3545;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: linear-gradient(135deg, #f8f4f0 0%, #e8ddd0 100%);
        color: var(--text);
        line-height: 1.6;
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 30px;
        border-radius: 15px 15px 0 0;
        text-align: center;
        margin-bottom: 0;
    }

    .header h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .header p {
        opacity: 0.9;
        font-size: 16px;
    }

    .reschedule-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .current-appointment {
        background: var(--light);
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .current-appointment h2 {
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .appointment-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .detail-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .detail-value {
        color: var(--text);
        font-size: 16px;
    }

    .reschedule-form {
        padding: 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        color: var(--primary);
        font-size: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--secondary);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background-color: #fafafa;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: var(--accent);
        outline: none;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(193, 122, 74, 0.1);
    }

    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .time-slot {
        padding: 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .time-slot:hover {
        border-color: var(--secondary);
    }

    .time-slot.selected {
        border-color: var(--accent);
        background: var(--light);
        color: var(--primary);
        font-weight: 600;
    }

    .time-slot.unavailable {
        background: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    .stylist-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .stylist-option {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .stylist-option:hover {
        border-color: var(--secondary);
    }

    .stylist-option.selected {
        border-color: var(--accent);
        background: var(--light);
    }

    .stylist-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--secondary);
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-weight: bold;
        font-size: 20px;
    }

    .stylist-name {
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--dark);
    }

    .stylist-specialty {
        font-size: 14px;
        color: #6c757d;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 15px 25px;
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
        flex: 1;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(138, 90, 68, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .alert {
        padding: 10px 15px;
        margin-bottom: 15px;
        border-radius: 3px;
        font-size: 14px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .availability-info {
        background: #e6f7ee;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .availability-info i {
        color: var(--success);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .stylist-options {
            grid-template-columns: 1fr;
        }

        .time-slots {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 480px) {
        .header {
            padding: 20px;
        }

        .reschedule-form {
            padding: 20px;
        }

        .time-slots {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-calendar-alt"></i> Reagendar Cita</h1>
        <p>Selecciona una nueva fecha y hora para tu cita</p>
    </div>

    <div class="reschedule-card">
        <div class="current-appointment">
            <h2><i class="fas fa-calendar-day"></i> Cita Actual</h2>
            <div class="appointment-details">
                <div class="detail-item">
                    <span class="detail-label">Servicio:</span>
                    <span class="detail-value"><?php echo $cita->servicio; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha Actual:</span>
                    <span class="detail-value"><?php echo $cita->fecha; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Hora Actual:</span>
                    <span class="detail-value"><?php echo $cita->hora; ?></span>
                </div>
            </div>
        </div>

        <form class="reschedule-form" id="reschedule-form" action="../controladores/reagendar_cita.php" method="POST">
            <?php if (isset($_SESSION['errores'])) : ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($_SESSION['errores'] as $error) : ?>
                            <li><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>
            <input type="hidden" name="id_cita" value="<?php echo $id_cita; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-calendar-plus"></i> Nueva Fecha y Hora</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="new-date"><i class="fas fa-calendar"></i> Nueva Fecha</label>
                        <input type="date" id="new-date" class="form-input" name="fecha" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value="<?php echo $cita->fecha; ?>">
                    </div>

                    <div class="form-group">
                        <label for="service"><i class="fas fa-scissors"></i> Servicio</label>
                        <select id="service" class="form-input" name="servicio" required>
                            <?php foreach ($servicios as $item) : ?>
                                <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $cita->servicio_id) ? 'selected' : ''; ?>>
                                    <?php echo $item->nombre; ?> - $<?php echo $cita->precio; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Hora</label>
                    <input type="time" id="time" name="hora" class="form-input" required value="<?php echo $cita->hora; ?>">
                </div>
            </div>
            <div class="availability-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Recordatorio:</strong> Puedes cancelar tu cita hasta 24 horas antes de la hora programada.
                    Después de este tiempo, por favor contacta directamente con la peluquería.
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> Confirmar Reagendamiento
                </button>
            </div>
        </form>
    </div>
</div>



<?php include_once __DIR__ . '/../templates/footer.php'; ?>