<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de admin
if ($_SESSION['rol'] !== 'admin') {
        header("Location: /Sistemas_Web_PHP/Sistema_Web_Citas_Peluqueria/index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
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
        --sidebar-width: 250px;
        --header-height: 60px;
        --box-border-radius: 8px;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
        --info: #17a2b8;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Source Sans Pro', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f4f6f9;
        color: var(--text);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
    }

    /* Sidebar */
    .sidebar {
        width: var(--sidebar-width);
        background-color: var(--primary);
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-brand {
        padding: 20px 15px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-brand h2 {
        font-size: 24px;
        margin: 0;
    }

    .sidebar-brand span {
        color: var(--secondary);
    }

    .sidebar-user {
        padding: 20px 15px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark);
        font-weight: bold;
        font-size: 18px;
        margin-right: 10px;
    }

    .sidebar-user-info {
        flex: 1;
    }

    .sidebar-user-name {
        font-weight: 600;
        font-size: 16px;
    }

    .sidebar-user-role {
        font-size: 13px;
        opacity: 0.8;
    }

    .sidebar-menu {
        padding: 15px 0;
        overflow-y: auto;
        max-height: calc(100vh - 180px);
    }

    .sidebar-menu-header {
        padding: 10px 15px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.7;
    }

    .sidebar-menu-item {
        padding: 12px 15px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        cursor: pointer;
        border-left: 3px solid transparent;
    }

    .sidebar-menu-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        border-left-color: var(--secondary);
    }

    .sidebar-menu-item i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Header */
    .header {
        background-color: white;
        height: var(--header-height);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        position: sticky;
        top: 0;
        z-index: 800;
    }

    .toggle-sidebar {
        display: none;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--primary);
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-secondary {
        background-color: #e9ecef;
        color: #495057;
    }

    .btn-secondary:hover {
        background-color: #dde2e6;
    }

    .btn-success {
        background-color: var(--success);
        color: white;
    }

    .btn-danger {
        background-color: var(--danger);
        color: white;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    /* Content */
    .content {
        flex: 1;
        padding: 20px;
    }

    .content-header {
        margin-bottom: 20px;
    }

    .content-header h1 {
        font-size: 24px;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .breadcrumb {
        display: flex;
        list-style: none;
        font-size: 14px;
        color: #6c757d;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "/";
        padding: 0 8px;
    }

    .breadcrumb-item.active {
        color: var(--primary);
    }

    /* Dashboard Cards */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background-color: white;
        border-radius: var(--box-border-radius);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .icon-primary {
        background-color: var(--primary);
    }

    .icon-success {
        background-color: var(--success);
    }

    .icon-warning {
        background-color: var(--warning);
    }

    .icon-info {
        background-color: var(--info);
    }

    .card-body {
        padding: 20px;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 10px;
    }

    .card-text {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
    }

    /* Charts */
    .charts-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-container {
        background-color: white;
        border-radius: var(--box-border-radius);
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
    }

    .chart-actions select {
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .chart-canvas {
        position: relative;
        height: 300px;
    }

    /* Section Title */
    .section-title {
        font-size: 18px;
        color: var(--primary);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--secondary);
    }

    /* Tables */
    .table-container {
        background-color: white;
        border-radius: var(--box-border-radius);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: #f8f9fa;
        color: var(--primary);
        font-weight: 600;
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-confirmed {
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

    .action-btn {
        padding: 5px 8px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 5px;
        font-size: 12px;
    }

    .btn-edit {
        background-color: var(--secondary);
        color: var(--dark);
    }

    .btn-delete {
        background-color: #ffe6e6;
        color: var(--danger);
    }

    .btn-view {
        background-color: #e6f7ee;
        color: var(--success);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .toggle-sidebar {
            display: block;
        }

        .charts-row {
            grid-template-columns: 1fr;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .overlay.show {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .dashboard-cards {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .header-actions {
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .chart-actions {
            display: none;
        }
    }
</style>

<div class="overlay" id="overlay"></div>
<div class="content-header">
    <h1>Panel de Administración</h1>
</div>

<div class="dashboard-cards">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ingresos del Mes</h3>
            <div class="card-icon icon-primary">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="card-value">$8,450.00</div>
            <p class="card-text"><span class="text-success"><i class="fas fa-arrow-up"></i> 12.5%</span> respecto al mes anterior</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Citas Totales</h3>
            <div class="card-icon icon-success">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="card-value">156</div>
            <p class="card-text"><span class="text-success"><i class="fas fa-arrow-up"></i> 8%</span> respecto a la semana pasada</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Clientes Activos</h3>
            <div class="card-icon icon-warning">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="card-value">243</div>
            <p class="card-text"><span class="text-success"><i class="fas fa-arrow-up"></i> 5%</span> nuevos clientes este mes</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Servicios Realizados</h3>
            <div class="card-icon icon-info">
                <i class="fas fa-scissors"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="card-value">287</div>
            <p class="card-text"><span class="text-success"><i class="fas fa-arrow-up"></i> 15%</span> respecto al mes anterior</p>
        </div>
    </div>
</div>

<div class="charts-row">
    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Ingresos Mensuales</h3>
            <div class="chart-actions">
                <select id="revenue-period">
                    <option value="month">Este Mes</option>
                    <option value="quarter">Este Trimestre</option>
                    <option value="year">Este Año</option>
                </select>
            </div>
        </div>
        <div class="chart-canvas">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Servicios Más Solicitados</h3>
            <div class="chart-actions">
                <select id="services-period">
                    <option value="month">Este Mes</option>
                    <option value="quarter">Este Trimestre</option>
                    <option value="year">Este Año</option>
                </select>
            </div>
        </div>
        <div class="chart-canvas">
            <canvas id="servicesChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-row">
    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Citas por Día de la Semana</h3>
            <div class="chart-actions">
                <select id="appointments-period">
                    <option value="week">Esta Semana</option>
                    <option value="month">Este Mes</option>
                </select>
            </div>
        </div>
        <div class="chart-canvas">
            <canvas id="appointmentsChart"></canvas>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Estado de Citas</h3>
        </div>
        <div class="chart-canvas">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<h3 class="section-title">Citas Recientes</h3>
<div class="table-container">
    <div class="card-header">
        <h3 class="card-title">Últimas Citas Programadas</h3>
        <button class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nueva Cita
        </button>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha y Hora</th>
                    <th>Estilista</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Sánchez</td>
                    <td>Corte de Cabello</td>
                    <td>15 Ago 2023 - 10:30 AM</td>
                    <td>María Rodríguez</td>
                    <td><span class="status-badge status-confirmed">Confirmada</span></td>
                    <td>
                        <button class="action-btn btn-view"><i class="fas fa-eye"></i></button>
                        <button class="action-btn btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="action-btn btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Ana García</td>
                    <td>Coloración</td>
                    <td>15 Ago 2023 - 11:45 AM</td>
                    <td>Carlos Méndez</td>
                    <td><span class="status-badge status-pending">Por confirmar</span></td>
                    <td>
                        <button class="action-btn btn-view"><i class="fas fa-eye"></i></button>
                        <button class="action-btn btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="action-btn btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Roberto López</td>
                    <td>Tratamiento Capilar</td>
                    <td>15 Ago 2023 - 2:15 PM</td>
                    <td>Laura Fernández</td>
                    <td><span class="status-badge status-confirmed">Confirmada</span></td>
                    <td>
                        <button class="action-btn btn-view"><i class="fas fa-eye"></i></button>
                        <button class="action-btn btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="action-btn btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Marta Díaz</td>
                    <td>Manicura y Pedicura</td>
                    <td>16 Ago 2023 - 9:00 AM</td>
                    <td>Elena Castro</td>
                    <td><span class="status-badge status-confirmed">Confirmada</span></td>
                    <td>
                        <button class="action-btn btn-view"><i class="fas fa-eye"></i></button>
                        <button class="action-btn btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="action-btn btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Carlos Ruiz</td>
                    <td>Corte de Cabello</td>
                    <td>16 Ago 2023 - 4:30 PM</td>
                    <td>María Rodríguez</td>
                    <td><span class="status-badge status-cancelled">Cancelada</span></td>
                    <td>
                        <button class="action-btn btn-view"><i class="fas fa-eye"></i></button>
                        <button class="action-btn btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="action-btn btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<?php include '../templates/footer.php'; ?>
<script>
    // Funcionalidad para mostrar/ocultar sidebar en móviles
    const toggleSidebar = document.getElementById('toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('overlay');

    toggleSidebar.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    // Funcionalidad para los elementos del menú
    document.querySelectorAll('.sidebar-menu-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-menu-item').forEach(i => {
                i.classList.remove('active');
            });
            this.classList.add('active');

            // En una implementación real, aquí se cargaría el contenido correspondiente
            if (this.querySelector('span').textContent === 'Cerrar Sesión') {
                window.location.href = 'login.html';
            }
        });
    });

    // Gráfico de Ingresos Mensuales
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Ingresos en $',
                data: [6200, 5900, 7200, 8100, 7800, 8500, 9200, 8450, 0, 0, 0, 0],
                backgroundColor: 'rgba(138, 90, 68, 0.1)',
                borderColor: '#8a5a44',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfico de Servicios Más Solicitados
    const servicesCtx = document.getElementById('servicesChart').getContext('2d');
    const servicesChart = new Chart(servicesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Corte de Cabello', 'Coloración', 'Tratamientos', 'Manicura', 'Otros'],
            datasets: [{
                data: [45, 25, 15, 10, 5],
                backgroundColor: [
                    '#8a5a44',
                    '#c17a4a',
                    '#d4b8a5',
                    '#3a2e26',
                    '#f8f4f0'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Citas por Día de la Semana
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    const appointmentsChart = new Chart(appointmentsCtx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Número de Citas',
                data: [18, 22, 20, 24, 28, 35, 12],
                backgroundColor: '#8a5a44',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfico de Estado de Citas
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: ['Confirmadas', 'Pendientes', 'Canceladas'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Funcionalidad para los botones de acción
    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (this.classList.contains('btn-view')) {
                alert('Funcionalidad de ver detalles');
            } else if (this.classList.contains('btn-edit')) {
                alert('Funcionalidad de editar');
            } else if (this.classList.contains('btn-delete')) {
                if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
                    alert('Cita eliminada correctamente');
                }
            }
        });
    });
</script>