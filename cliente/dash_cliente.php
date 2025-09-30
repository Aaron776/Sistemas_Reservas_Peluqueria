<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: " . __DIR__ . "/../index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";

$id_usuario = $_SESSION['id_usuario'];  // Obtener el ID del usuario actual

// Obtener los servicios de la base de datos
$sql = $conexion->prepare("SELECT id,nombre,descripcion,precio FROM servicios limit 3");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);

// Obtener total de citas de este usuario cliente
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE usuario_id = :id");
$sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$sql->execute();
$total_citas = $sql->fetch(PDO::FETCH_OBJ);

// Obtener las citas de este usuario cliente
$sql = $conexion->prepare("SELECT citas.estado AS estado,citas.fecha AS fecha,citas.hora AS hora,servicios.nombre AS servicio FROM citas INNER JOIN servicios ON citas.servicio_id = servicios.id WHERE citas.usuario_id = :id
ORDER BY citas.fecha DESC LIMIT 3");
$sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$sql->execute();
$citas = $sql->fetchAll(PDO::FETCH_OBJ);

// Obtener la ultima cita de este usuario cliente
$sql = $conexion->prepare("SELECT citas.fecha AS fecha,citas.hora AS hora,servicios.nombre AS servicio FROM citas INNER JOIN servicios ON citas.servicio_id = servicios.id WHERE citas.usuario_id = :id ORDER BY citas.fecha DESC LIMIT 1");
$sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$sql->execute();
$ultima_cita = $sql->fetch(PDO::FETCH_OBJ);


?>

<div class="content-header">
    <h1>Panel de Cliente</h1>
</div>

<div class="dashboard-cards">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Próxima Cita</h3>
            <div class="card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Fecha:</strong> <?= $ultima_cita->fecha; ?></p>
            <p><strong>Hora:</strong> <?= $ultima_cita->hora; ?></p>
            <p><strong>Servicio:</strong> <?= $ultima_cita->servicio; ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial</h3>
            <div class="card-icon">
                <i class="fas fa-history"></i>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Total de Citas:</strong> <?= $total_citas->total; ?></p>
            <p><strong>Servicio Favorito:</strong> Corte de Cabello</p>
            <p><strong>Última Visita:</strong> 20 de Julio, 2023</p>
        </div>
    </div>
</div>

<h3 class="section-title">Mis Próximas Citas</h3>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Citas Programadas</h3>
        <a href="reservar_cita.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nueva Cita
        </a>
    </div>
    <div class="card-body">
        <ul class="appointments-list">
            <?php foreach ($citas as $item) : ?>
                <li class="appointment-item">
                    <div class="appointment-details">
                        <h4><?php echo $item->servicio; ?></h4>
                        <p><?php echo $item->fecha; ?> - <?php echo $item->hora; ?></p>
                    </div>
                    <?php if($item->estado == 'pendiente') : ?>
                        <span class="appointment-status status-pending">Pendiente</span>
                    <?php elseif($item->estado == 'confirmada') : ?>
                        <span class="appointment-status status-confirmed">Confirmada</span>
                    <?php elseif($item->estado == 'cancelada') : ?>
                        <span class="appointment-status status-cancelled">Cancelada</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<h3 class="section-title">Servicios Disponibles</h3>
<div class="services-grid">
    <?php foreach ($servicios as $item) : ?>
        <div class="service-card">
            <div class="service-content">
                <h3><?= $item->nombre; ?></h3>
                <p><?= $item->descripcion; ?>.</p>
                <span class="price">$<?= $item->precio; ?></span>
                <a href="reservar_cita.php" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-calendar-plus"></i> Reservar Ahora
                </a>
            </div>
        </div>
    <?php endforeach; ?>

</div>
<?php include '../templates/footer.php'; ?>