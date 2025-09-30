<?php
include_once __DIR__ . "/../autorizacion/auth.php";

if ($_SESSION['rol'] !== 'cliente') { // si no es cliente, lo mandamos al index
    header("Location: ../index.php");
    exit();
}

include_once __DIR__ . "/../templates/header.php";
include_once __DIR__ . "/../conexion/bd.php";

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario actual


// Traer los servicios de la base de datos
$sql = $conexion->prepare("SELECT id,nombre FROM servicios");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);


// Traer las citas de la base de datos de este usuario
$sql = $conexion->prepare("SELECT citas.id AS id_cita, citas.fecha AS fecha, citas.hora AS hora,citas.estado AS estado,servicios.nombre AS servicio,servicios.precio AS precio FROM citas INNER JOIN servicios ON citas.servicio_id = servicios.id INNER JOIN usuarios ON citas.usuario_id = usuarios.id WHERE citas.usuario_id = :id_usuario ORDER BY citas.fecha DESC LIMIT 10");
$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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
        --warning: #ffc107;
        --danger: #dc3545;
        --info: #17a2b8;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f4f6f9;
        color: var(--text);
        line-height: 1.6;
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 25px;
        border-radius: 15px 15px 0 0;
        margin-bottom: 0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .page-header h1 {
        font-size: 28px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header p {
        opacity: 0.9;
        font-size: 16px;
    }

    .content-card {
        background-color: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .filters-section {
        padding: 20px;
        border-bottom: 1px solid #eee;
        background-color: #f8f9fa;
    }

    .filters-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
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

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        /* espacio entre botones */
        align-items: center;
    }

    .action-buttons form {
        margin: 0;
    }


    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
    }

    .filter-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        background-color: white;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
        background-color: #f8f9fa;
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

    .appointments-section {
        padding: 20px;
    }

    .section-title {
        font-size: 20px;
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
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

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-completed {
        background-color: #e6f7ee;
        color: var(--success);
    }

    .status-pending {
        background-color: #fff8e6;
        color: var(--warning);
    }

    .status-cancelled {
        background-color: #ffe6e6;
        color: var(--danger);
    }

    .status-confirmed {
        background-color: #e6f3ff;
        color: var(--info);
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        margin-right: 5px;
    }

    .btn-view {
        background-color: var(--info);
        color: white;
    }

    .btn-cancel {
        background-color: var(--danger);
        color: white;
    }

    .btn-reschedule {
        background-color: var(--warning);
        color: white;
    }

    .action-btn:hover {
        opacity: 0.9;
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
        margin-top: 20px;
        gap: 5px;
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
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary);
    }

    .mobile-card-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
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
    }

    .mobile-card-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .appointments-table {
            display: none;
        }

        .mobile-appointment-card {
            display: block;
        }

        .filters-row {
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
        }

        .stats-cards {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            padding: 20px;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .mobile-card-actions {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            margin-right: 0;
            margin-bottom: 5px;
        }
    }
</style>
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-history"></i> Historial de Citas</h1>
        <p>Revisa todas tus citas pasadas, pendientes y canceladas</p>
    </div>

    <div class="content-card">
        <div class="filters-section">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="filter-status"><i class="fas fa-filter"></i> Filtrar por estado</label>
                    <select id="filter-status" class="filter-select">
                        <option value="all">Todas las citas</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="confirmada">Confirmadas</option>
                        <option value="cancelada">Canceladas</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-date"><i class="fas fa-calendar"></i> Filtrar por fecha</label>
                    <select id="filter-date" class="filter-select">
                        <option value="all">Todas las fechas</option>
                        <option value="today">Hoy</option>
                        <option value="week">Esta semana</option>
                        <option value="month">Este mes</option>
                        <option value="past">Pasadas</option>
                        <option value="future">Futuras</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-service"><i class="fas fa-scissors"></i> Filtrar por servicio</label>
                    <select id="filter-service" class="filter-select">
                        <option value="all">Todos los servicios</option>
                        <?php foreach ($servicios as $item) : ?>
                            <option value="<?php echo $item->id; ?>"><?php echo $item->nombre; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="appointments-section">
            <h2 class="section-title"><i class="fas fa-list"></i> Lista de Citas</h2>

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
                        <th>Fecha y Hora</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $item) :
                        // Combinar fecha y hora de la cita en un objeto DateTime
                        $fechaHoraCita = new DateTime($item->fecha . ' ' . $item->hora);
                        $ahora = new DateTime();
                        $horasRestantes = ($fechaHoraCita->getTimestamp() - $ahora->getTimestamp()) / 3600;
                    ?>
                        <tr>
                            <td><?php echo $item->fecha; ?>, <?php echo $item->hora; ?></td>
                            <td><?php echo $item->servicio; ?></td>
                            <?php if ($item->estado == 'pendiente') : ?>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                            <?php elseif ($item->estado == 'confirmada') : ?>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                            <?php elseif ($item->estado == 'cancelada') : ?>
                                <td><span class="status-badge status-cancelled">Cancelada</span></td>
                            <?php endif; ?>
                            <td>$<?php echo $item->precio; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="../cliente/reagendar_cita.php?id_cita=<?php echo $item->id_cita; ?>" class="action-btn btn-reschedule">Reagendar</a>

                                    <?php
                                    // Mostrar botón de cancelar solo si faltan más de 24 horas y la cita no está cancelada
                                    if ($horasRestantes >= 24 && $item->estado != 'cancelada') :
                                    ?>
                                        <form action="../controladores/cancelar_cita.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id_cita" value="<?php echo $item->id_cita; ?>">
                                            <button type="submit" class="action-btn btn-cancel">Cancelar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

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
    document.addEventListener("DOMContentLoaded", () => {
        const filterStatus = document.getElementById("filter-status");
        const filterDate = document.getElementById("filter-date");
        const filterService = document.getElementById("filter-service");

        // Escuchar cambios en los filtros
        filterStatus.addEventListener("change", applyFilters);
        filterDate.addEventListener("change", applyFilters);
        filterService.addEventListener("change", applyFilters);

        function applyFilters() {
            const status = filterStatus.value;
            const date = filterDate.value;
            const service = filterService.value;

            const rows = document.querySelectorAll('.appointments-table tbody tr');

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);

            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            rows.forEach(row => {
                let show = true;

                // Estado
                if (status !== 'all') {
                    const rowStatus = row.querySelector('.status-badge').textContent.trim().toLowerCase();
                    if (rowStatus !== status) show = false;
                }

                // Servicio
                if (service !== 'all') {
                    const rowService = row.children[1].textContent.trim();
                    const selectedServiceName = filterService.options[filterService.selectedIndex].text;
                    if (rowService !== selectedServiceName) {
                        show = false;
                    }
                }

                // Fecha
                if (date !== 'all') {
                    const rowDateText = row.children[0].textContent.split(',')[0].trim();
                    const rowDate = new Date(rowDateText);
                    rowDate.setHours(0, 0, 0, 0);

                    switch (date) {
                        case 'today':
                            if (rowDate.getTime() !== today.getTime()) show = false;
                            break;
                        case 'week':
                            if (rowDate < startOfWeek || rowDate > endOfWeek) show = false;
                            break;
                        case 'month':
                            if (rowDate < startOfMonth || rowDate > endOfMonth) show = false;
                            break;
                        case 'past':
                            if (rowDate >= today) show = false;
                            break;
                        case 'future':
                            if (rowDate <= today) show = false;
                            break;
                    }
                }

                row.style.display = show ? '' : 'none';
            });
        }
    });
</script>

<?php include __DIR__ . "/../templates/footer.php"; ?>