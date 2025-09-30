<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";

// Obtener el ID de la cita a editar
$id_cita = $_GET['id_cita'];

if (isset($_GET['id_cita']) && ctype_digit($_GET['id_cita'])) { // aqui valido si estoy mandando el id por la url y si es un entero
    $id_cita = (int) $_GET['id_cita']; // conversión segura a entero
} else {
    die("Solicitud inválida.");
}


// Obtener los datos de la cita que se va a editar de la base de datos
$sql = $conexion->prepare("SELECT citas.id,citas.fecha,citas.hora,servicios.nombre AS servicio,citas.estado AS estado, servicios.precio AS precio, usuarios.nombre AS cliente FROM citas INNER JOIN servicios ON citas.servicio_id = servicios.id INNER JOIN usuarios ON citas.usuario_id = usuarios.id WHERE citas.id = :id_cita");
$sql->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
$sql->execute();
$cita = $sql->fetch(PDO::FETCH_OBJ);
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
        --warning: #ffc107;
        --info: #17a2b8;
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .header p {
        opacity: 0.9;
        font-size: 16px;
    }

    .edit-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .current-info {
        background: var(--light);
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .current-info h2 {
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        color: var(--text);
        font-size: 16px;
        padding: 10px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .edit-form {
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

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background-color: #fafafa;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: var(--accent);
        outline: none;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(193, 122, 74, 0.1);
    }

    .status-option {
        padding: 8px 12px;
        margin: 5px 0;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fff8e6;
        color: var(--warning);
        border-left: 4px solid var(--warning);
    }

    .status-confirmed {
        background-color: #e6f7ee;
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .status-completed {
        background-color: #e6f3ff;
        color: var(--info);
        border-left: 4px solid var(--info);
    }

    .status-cancelled {
        background-color: #ffe6e6;
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    .status-preview {
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
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

    .btn-secondary:hover {
        background: #5a6268;
    }

    .notes-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .notes-section h4 {
        color: var(--primary);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
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

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .edit-form {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .header {
            padding: 20px;
        }

        .header h1 {
            font-size: 24px;
        }

        .current-info {
            padding: 20px;
        }
    }
</style>

<div class="container">
    <div class="header">
        <h1><i class="fas fa-edit"></i> Editar Estado de Cita</h1>
        <p>Actualiza el estado de la cita seleccionada</p>
    </div>

    <div class="edit-card">
        <div class="current-info">
            <h2><i class="fas fa-calendar-alt"></i> Información de la Cita</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-user"></i> Cliente</span>
                    <span class="info-value"><?php echo $cita->cliente; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-scissors"></i> Servicio</span>
                    <span class="info-value"><?php echo $cita->servicio; ?> - $<?php echo $cita->precio; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-info-circle"></i> Estado Actual</span>
                    <?php
                    switch ($cita->estado) {
                        case 'pendiente':
                            echo '<span class="info-value status-pending">Pendiente</span>';
                            break;
                        case 'confirmada':
                            echo '<span class="info-value status-confirmed">Confirmada</span>';
                            break;
                        case 'cancelada':
                            echo '<span class="info-value status-cancelled">Cancelada</span>';
                            break;
                        default:
                            echo '<span class="info-value">Desconocido</span>';
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>

        <form class="edit-form" id="edit-form" action="../controladores/editar_estado_cita.php" method="POST">
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
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-sync-alt"></i> Actualizar Estado</h3>

                <div class="form-group">
                    <label for="status"><i class="fas fa-flag"></i> Nuevo Estado de la Cita</label>
                    <select id="status" name="estado" class="form-select" required>
                        <?php if($cita->estado=='pendiente'):?>
                            <option value="pendiente" selected>Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        <?php elseif($cita->estado=='confirmada'):?>
                            <option value="confirmada" selected>Confirmada</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="cancelada">Cancelada</option>
                        <?php elseif($cita->estado=='cancelada'):?>
                            <option value="cancelada" selected>Cancelada</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                        <?php endif;?>
                    </select>
                </div>
            </div>

            <div class="notes-section">
                <h4><i class="fas fa-lightbulb"></i> Información sobre los estados</h4>
                <div class="status-option status-pending">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Pendiente:</strong> La cita está programada pero no confirmada por el cliente
                    </div>
                </div>
                <div class="status-option status-confirmed">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Confirmada:</strong> El cliente ha confirmado su asistencia
                    </div>
                </div>
                <div class="status-option status-cancelled">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <strong>Cancelada:</strong> La cita ha sido cancelada por el cliente o el establecimiento
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Estado
                </button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . "/../templates/footer.php"; ?>