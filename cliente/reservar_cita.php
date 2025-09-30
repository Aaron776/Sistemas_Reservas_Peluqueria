<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: " . __DIR__ . "/../index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario actual

// Obtener servicios de la base de datos
$sql = $conexion->prepare("SELECT id, nombre,precio FROM servicios");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);
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
    }

    body {
        background-color: #f4f6f9;
        color: var(--text);
        line-height: 1.6;
        min-height: 100vh;
        padding: 20px;
    }

    .appointment-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .appointment-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 25px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .appointment-header h1 {
        font-size: 26px;
        margin-bottom: 5px;
    }

    .appointment-header p {
        opacity: 0.9;
        font-size: 14px;
    }

    .appointment-content {
        background-color: white;
        padding: 30px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
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

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 0 3px rgba(193, 122, 74, 0.1);
    }

    .btn {
        padding: 12px 20px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        border: none;
        display: block;
        width: 100%;
        background-color: var(--primary);
        color: white;
    }

    .btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="appointment-container">
    <div class="appointment-header">
        <h1><i class="fas fa-calendar-plus"></i> Reservar Cita</h1>
        <p>Selecciona la fecha, hora y servicio que desees</p>
    </div>

    <div class="appointment-content">
        <form id="appointment-form" action="../controladores/registrar_cita.php" method="POST">
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
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>">

            <!-- Fecha -->
            <div class="form-group">
                <label for="fecha"><i class="fas fa-calendar-day"></i> Fecha de la Cita</label>
                <input type="date" id="fecha" name="fecha" class="form-input" required>
            </div>

            <!-- Hora -->
            <div class="form-group">
                <label for="hora"><i class="fas fa-clock"></i> Hora de la Cita</label>
                <input type="time" id="hora" name="hora" class="form-input" required>
            </div>

            <!-- Servicio -->
            <div class="form-group">
                <label for="id_servicio"><i class="fas fa-scissors"></i> Selecciona un Servicio</label>
                <select id="id_servicio" name="servicio_id" class="form-select" required>
                    <option value="">-- Selecciona un servicio --</option>
                    <?php foreach ($servicios as $item) : ?>
                        <option value="<?php echo $item->id; ?>"><?php echo $item->nombre; ?> - $<?php echo $item->precio; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-calendar-check"></i> Confirmar Reserva
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . "/../templates/footer.php"; ?>