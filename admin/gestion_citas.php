<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";
// Obtener todos los servicios de la base de datos
$sql = $conexion->prepare("SELECT id,nombre FROM servicios");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);

//Obtener las citas con estado pendiente
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE estado = 'pendiente'");
$sql->execute();
$total_citas_pendientes = $sql->fetch(PDO::FETCH_OBJ);

//Obtener todas las citas de este dia
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE DATE(fecha) = CURDATE()");
$sql->execute();
$total_citas_hoy = $sql->fetch(PDO::FETCH_OBJ);

// Obtener todas las citas de este mes que esten en estado confirmado
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE MONTH(fecha) = MONTH(CURDATE()) AND estado = 'confirmado'");
$sql->execute();
$total_citas_mes = $sql->fetch(PDO::FETCH_OBJ);

// Obtener todas las citas  de la base de datos
$sql = $conexion->prepare("SELECT citas.id AS id_cita, citas.fecha, citas.hora, citas.estado, citas.servicio_id,
servicios.nombre AS servicio, servicios.precio AS precio,
usuarios.nombre AS cliente, usuarios.email AS email
FROM citas
INNER JOIN servicios ON citas.servicio_id = servicios.id
INNER JOIN usuarios ON citas.usuario_id = usuarios.id
");
$sql->execute();
$citas = $sql->fetchAll(PDO::FETCH_OBJ);
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
        max-width: 1400px;
        margin: 0 auto;
    }

    .header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 30px;
        border-radius: 15px 15px 0 0;
        margin-bottom: 0;
    }

    .header h1 {
        font-size: 32px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header p {
        opacity: 0.9;
        font-size: 16px;
    }

    .appointments-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .appointments-actions {
        padding: 20px;
        background: var(--light);
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filters-container {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .filter-select {
        padding: 10px 15px;
        border: 2px solid #e9ecef;
        border-radius: 6px;
        font-size: 14px;
        background: white;
        min-width: 150px;
    }

    .appointments-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
        background: #f8f9fa;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary);
    }

    .stat-icon {
        font-size: 30px;
        margin-bottom: 10px;
        color: var(--primary);
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: var(--accent);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
    }

    .appointments-table-container {
        padding: 20px;
        overflow-x: auto;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .appointments-table th {
        background-color: var(--light);
        color: var(--dark);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--secondary);
    }

    .appointments-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .appointments-table tr:hover {
        background-color: #f8f9fa;
    }

    .client-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-weight: bold;
        font-size: 16px;
    }

    .client-details h4 {
        margin-bottom: 3px;
        color: var(--dark);
    }

    .client-details p {
        color: #6c757d;
        font-size: 14px;
    }

    .service-info {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .service-price {
        color: var(--accent);
        font-weight: bold;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fff8e6;
        color: var(--warning);
    }

    .status-confirmed {
        background-color: #e6f7ee;
        color: var(--success);
    }

    .status-completed {
        background-color: #e6f3ff;
        color: var(--info);
    }

    .status-cancelled {
        background-color: #ffe6e6;
        color: var(--error);
    }

    .datetime-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .date {
        font-weight: 600;
        color: var(--dark);
    }

    .time {
        color: #6c757d;
        font-size: 14px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
    }

    .no-appointments {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .no-appointments i {
        font-size: 50px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
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

    .pagination-btn {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background-color: white;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .pagination-btn.active {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination-btn:hover:not(.active) {
        background-color: #f8f9fa;
    }

    .mobile-appointment-card {
        display: none;
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary);
    }

    .mobile-card-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .mobile-card-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .mobile-card-label {
        font-weight: 600;
        color: var(--dark);
        min-width: 100px;
    }

    .mobile-card-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .appointments-table {
            display: none;
        }

        .mobile-appointment-card {
            display: block;
        }

        .appointments-actions {
            flex-direction: column;
        }

        .filters-container {
            width: 100%;
            justify-content: space-between;
        }

        .filter-group {
            flex: 1;
        }

        .appointments-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .header {
            padding: 20px;
        }

        .header h1 {
            font-size: 24px;
        }

        .filters-container {
            flex-direction: column;
        }

        .appointments-stats {
            grid-template-columns: 1fr;
        }

        .mobile-card-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-calendar-alt"></i> Gestión de Citas</h1>
        <p>Administra y edita las citas programadas en el sistema</p>
    </div>

    <div class="appointments-card">
        <div class="appointments-actions">
            <div class="filters-container">
                <div class="filter-group">
                    <label for="filter-status">Estado</label>
                    <select id="filter-status" class="filter-select">
                        <option value="all">Todos los estados</option>
                        <option value="pending">Pendientes</option>
                        <option value="confirmed">Confirmadas</option>
                        <option value="cancelled">Canceladas</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-date">Fecha</label>
                    <select id="filter-date" class="filter-select">
                        <option value="all">Todas las fechas</option>
                        <option value="today">Hoy</option>
                        <option value="week">Esta semana</option>
                        <option value="month">Este mes</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-service">Servicio</label>
                    <select id="filter-service" class="filter-select">
                        <option value="all">Todos los servicios</option>
                        <?php foreach ($servicios as $item) : ?>
                            <option value="<?php echo $item->id; ?>"><?php echo $item->nombre; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="appointments-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-number"><?php echo $total_citas_hoy->total; ?></div>
                <div class="stat-label">Citas Hoy</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-number"><?php echo $total_citas_pendientes->total; ?></div>
                <div class="stat-label">Pendientes</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-number"><?php echo $total_citas_mes->total; ?></div>
                <div class="stat-label">Este Mes</div>
            </div>
        </div>

        <div class="appointments-table-container">
            <!-- Tabla para desktop -->
            <table class="appointments-table">
                <?php if (isset($_SESSION['exito'])) : ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= $_SESSION['exito']; ?>
                    </div>
                    <?php unset($_SESSION['exito']); ?>
                <?php endif; ?>
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
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($citas as $item) : ?>
                            <td>
                                <div class="client-info">
                                    <div class="client-avatar"><?php echo substr($item->cliente, 0, 2); ?></div>
                                    <div class="client-details">
                                        <h4><?php echo $item->cliente; ?></h4>
                                        <p><?php echo $item->email; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="service-info"><?php echo $item->servicio; ?></div>
                                <div class="service-price">$<?php echo $item->precio; ?></div>
                            </td>
                            <td>
                                <div class="datetime-info">
                                    <span class="date"><?php echo $item->fecha; ?></span>
                                    <span class="time"><?php echo $item->hora; ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($item->estado == 'pendiente') : ?>
                                    <span class="status-badge status-pending" data-estado="pending">Pendiente</span>
                                <?php elseif ($item->estado == 'confirmada') : ?>
                                    <span class="status-badge status-confirmed" data-estado="confirmed">Confirmada</span>
                                <?php elseif ($item->estado == 'cancelada') : ?>
                                    <span class="status-badge status-cancelled" data-estado="cancelled">Cancelada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar_estado_cita.php?id_cita=<?php echo $item->id_cita; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tarjetas para móvil -->
            <?php foreach ($citas as $item) : ?>
                <div class="mobile-appointment-card">
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Cliente:</span>
                        <span>
                            <div class="client-info">
                                <div class="client-avatar"><?php echo substr($item->cliente, 0, 2); ?></div>
                                <div class="client-details">
                                    <h4><?php echo $item->cliente; ?></h4>
                                    <p><?php echo $item->email; ?></p>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Servicio:</span>
                        <span>
                            <div class="service-info"><?php echo $item->servicio; ?></div>
                            <div class="service-price">$<?php echo $item->precio; ?></div>
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Fecha:</span>
                        <span><?php echo $item->fecha; ?>, <?php echo $item->hora; ?></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Estado:</span>
                        <?php if ($item->estado == 'pendiente') : ?>
                            <span class="status-badge status-pending">Pendiente</span>
                        <?php elseif ($item->estado == 'confirmada') : ?>
                            <span class="status-badge status-confirmed">Confirmada</span>
                        <?php elseif ($item->estado == 'cancelada') : ?>
                            <span class="status-badge status-cancelled">Cancelada</span>
                        <?php endif; ?>
                    </div>
                    <div class="mobile-card-actions">
                        <a href="editar_estado_cita.php?id_cita=<?php echo $item->id_cita; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="pagination">
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">Siguiente</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Filtros interactivos
    document.addEventListener('DOMContentLoaded', function() {
        const filterStatus = document.getElementById('filter-status');
        const filterDate = document.getElementById('filter-date');
        const filterService = document.getElementById('filter-service');

        function applyFilters() {
            const status = filterStatus.value;
            const dateFilter = filterDate.value;
            const service = filterService.value;

            const tableRows = document.querySelectorAll('.appointments-table tbody tr');
            const mobileCards = document.querySelectorAll('.mobile-appointment-card');

            tableRows.forEach((row, index) => {
                const estado = row.querySelector('td:nth-child(4) .status-badge').dataset.estado;
                const fechaText = row.querySelector('td:nth-child(3) .date').textContent;
                const servicioId = row.querySelector('td:nth-child(2) .service-info').dataset.id;

                let show = true;

                // Filtrar por estado
                if (status !== 'all' && estado !== status) {
                    show = false;
                }

                // Filtrar por servicio
                if (service !== 'all' && servicioId !== service) {
                    show = false;
                }


                // Filtrar por fecha
                if (dateFilter !== 'all') {
                    const citaDate = new Date(fechaText);
                    const now = new Date();
                    const startOfWeek = new Date(now);
                    startOfWeek.setDate(now.getDate() - now.getDay()); // Domingo como inicio de semana
                    const endOfWeek = new Date(startOfWeek);
                    endOfWeek.setDate(startOfWeek.getDate() + 6);

                    const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                    const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                    if (dateFilter === 'today' && citaDate.toDateString() !== now.toDateString()) show = false;
                    if (dateFilter === 'week' && (citaDate < startOfWeek || citaDate > endOfWeek)) show = false;
                    if (dateFilter === 'month' && (citaDate < startOfMonth || citaDate > endOfMonth)) show = false;
                }

                row.style.display = show ? '' : 'none';
                mobileCards[index].style.display = show ? 'block' : 'none';
            });
        }

        filterStatus.addEventListener('change', applyFilters);
        filterDate.addEventListener('change', applyFilters);
        filterService.addEventListener('change', applyFilters);
    });
</script>

<?php include __DIR__ . "/../templates/footer.php"; ?>