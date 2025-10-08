<?php
// Comprobar el estado actual de la sesión
if (session_status() === PHP_SESSION_NONE) { // Si no hay ninguna sesión activa
    session_start(); // Inicia una nueva sesión o reanuda la existente
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleCut - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
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
            display: flex;
            flex-direction: column;
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
            flex: 1;
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
            text-decoration: none;
            color: white;
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

        /* Sidebar Footer - Para los botones de acción */
        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .sidebar-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-btn {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 100%;
        }

        .sidebar-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .sidebar-btn i {
            margin-right: 10px;
            width: 16px;
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
            background-color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
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

        /* Section Title */
        .section-title {
            font-size: 18px;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary);
        }

        /* Appointments */
        .appointments-list {
            list-style: none;
        }

        .appointment-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .appointment-item:last-child {
            border-bottom: none;
        }

        .appointment-details h4 {
            margin-bottom: 5px;
            font-size: 16px;
        }

        .appointment-details p {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .appointment-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-confirmed {
            background-color: #e6f7ee;
            color: #0cb577;
        }

        .status-pending {
            background-color: #fff8e6;
            color: #f4c150;
        }

        .status-cancelled {
            background-color: #ffe6e6;
            color: #f45050;
        }

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .service-card {
            background-color: white;
            border-radius: var(--box-border-radius);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-content {
            padding: 20px;
        }

        .service-content h3 {
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 18px;
        }

        .service-content p {
            margin-bottom: 15px;
            font-size: 14px;
            color: #6c757d;
        }

        .price {
            color: var(--accent);
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
            display: block;
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

        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .header-actions {
                flex-direction: column;
                align-items: flex-end;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>Style<span>Cut</span></h2>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><?php echo substr($_SESSION['nombre'], 0, 1); ?></div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?php echo ucfirst($_SESSION['nombre']); ?></div>
                <div class="sidebar-user-role"><?php echo ucfirst($_SESSION['rol']); ?></div>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-header">Navegación Principal</div>

            <?php if ($_SESSION['rol'] == 'cliente') { ?>
                <a href="../cliente/dash_cliente.php" class="sidebar-menu-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <a href="../cliente/reservar_cita.php" class="sidebar-menu-item">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Reservar Cita</span>
                </a>

                <a href="../cliente/historial_citas.php" class="sidebar-menu-item">
                    <i class="fas fa-history"></i>
                    <span>Historial de Citas</span>
                </a>

                <a href="../cliente/servicios_disponibles.php" class="sidebar-menu-item">
                    <i class="fas fa-scissors"></i>
                    <span>Servicios</span>
                </a>
            <?php } elseif ($_SESSION['rol'] == 'admin') { ?>
                <a href="../admin/dash_admin.php" class="sidebar-menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <a href="../admin/gestion_citas.php" class="sidebar-menu-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Gestión de Citas</span>
                </a>

                <a href="../admin/gestion_usuarios.php" class="sidebar-menu-item">
                    <i class="fas fa-users"></i>
                    <span>Gestión de Usuarios</span>
                </a>

                <a href="../admin/gestion_servicios.php" class="sidebar-menu-item">
                    <i class="fas fa-scissors"></i>
                    <span>Gestión de Servicios</span>
                </a>
            <?php } ?>
        </div>

        <!-- Footer del sidebar con los botones de acción -->
        <div class="sidebar-footer">
            <div class="sidebar-actions">
                <a href="http://localhost/Sistemas_Web_PHP/Sistema_Web_Citas_Peluqueria/cambiar_password.php" class="sidebar-btn">
                    <i class="fas fa-lock"></i> Cambiar Contraseña
                </a>
                <a href="http://localhost/Sistemas_Web_PHP/Sistema_Web_Citas_Peluqueria/perfil.php" class="sidebar-btn">
                    <i class="fas fa-user"></i> Mi Perfil
                </a>
                <a href="http://localhost/Sistemas_Web_PHP/Sistema_Web_Citas_Peluqueria/controladores/logout.php" class="sidebar-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Content -->
        <div class="content">