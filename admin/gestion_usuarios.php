<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";

// Obtener la lista de usuarios
$sql = $conexion->prepare("SELECT id,nombre,rol,telefono,email FROM usuarios");
$sql->execute();
$usuarios = $sql->fetchAll(PDO::FETCH_OBJ);

//Obtener cantidad total de usuarios
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM usuarios");
$sql->execute();
$total_usuarios = $sql->fetch(PDO::FETCH_OBJ);

// Obtener total de usaurios con rol admin
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'admin'");
$sql->execute();
$total_usuarios_admin = $sql->fetch(PDO::FETCH_OBJ);

// Obtener total de usaurios con rol cliente
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'cliente'");
$sql->execute();
$total_usuarios_cliente = $sql->fetch(PDO::FETCH_OBJ);
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
        max-width: 1200px;
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

    .users-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .users-actions {
        padding: 20px;
        background: var(--light);
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 300px;
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

    .search-input {
        width: 100%;
        padding: 12px 15px;
        padding-left: 45px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 0 3px rgba(193, 122, 74, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .users-stats {
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

    .users-table-container {
        padding: 20px;
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .users-table th {
        background-color: var(--light);
        color: var(--dark);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--secondary);
    }

    .users-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .users-table tr:hover {
        background-color: #f8f9fa;
    }

    .user-avatar {
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

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-details h4 {
        margin-bottom: 3px;
        color: var(--dark);
    }

    .user-details p {
        color: #6c757d;
        font-size: 14px;
    }

    .role-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-admin {
        background-color: #e6f3ff;
        color: var(--info);
    }

    .role-cliente {
        background-color: #e6f7ee;
        color: var(--success);
    }

    .role-stylist {
        background-color: #fff8e6;
        color: var(--warning);
    }

    .status-active {
        color: var(--success);
        font-weight: 600;
    }

    .status-inactive {
        color: var(--error);
        font-weight: 600;
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

    .btn-danger {
        background-color: var(--error);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }

    .no-users {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .no-users i {
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

    .mobile-user-card {
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

    /* Modal de confirmación */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        max-width: 500px;
        width: 100%;
        padding: 30px;
        position: relative;
        animation: modalAppear 0.3s ease;
    }

    @keyframes modalAppear {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .close-modal {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--dark);
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-secondary {
        flex: 1;
        padding: 12px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .users-table {
            display: none;
        }

        .mobile-user-card {
            display: block;
        }

        .users-actions {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .users-stats {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .header {
            padding: 20px;
        }

        .header h1 {
            font-size: 24px;
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
        <h1><i class="fas fa-users-cog"></i> Gestión de Usuarios</h1>
        <p>Administra los usuarios registrados en el sistema</p>
    </div>

    <div class="users-card">
        <div class="users-actions">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar usuario por nombre o email...">
            </div>
        </div>

        <div class="users-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?php echo $total_usuarios->total; ?></div>
                <div class="stat-label">Usuarios Totales</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-number"><?php echo $total_usuarios_admin->total; ?></div>
                <div class="stat-label">Administradores</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                <div class="stat-number"><?php echo $total_usuarios_cliente->total; ?></div>
                <div class="stat-label">Clientes</div>
            </div>
        </div>

        <div class="users-table-container">
            <!-- Tabla para desktop -->
            <table class="users-table">
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
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $item) : ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar"><?php echo substr($item->nombre, 0, 2); ?></div>
                                    <div class="user-details">
                                        <h4><?php echo $item->nombre; ?></h4>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge role-admin"><?php echo  ucfirst($item->rol); ?></span>
                            </td>
                            <td><?php echo $item->email; ?></td>
                            <td><?php echo $item->telefono; ?></td>
                            <td>
                                <form action="../controladores/eliminar_usuario.php" method="POST">
                                    <input type="hidden" name="id_usuario" value="<?php echo $item->id; ?>">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tarjetas para móvil -->
            <?php foreach ($usuarios as $item) : ?>
                <div class="mobile-user-card">
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Usuario:</span>
                        <span>
                            <div class="user-info">
                                <div class="user-avatar"><?= substr($item->nombre, 0, 2); ?></div>
                                <div class="user-details">
                                    <h4><?= htmlspecialchars($item->nombre); ?></h4>
                                    <p><?= htmlspecialchars($item->email); ?></p>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Rol:</span>
                        <span class="role-badge <?= $roleClass ?>"><?= ucfirst($item->rol); ?></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Teléfono:</span>
                        <span><?= htmlspecialchars($item->telefono); ?></span>
                    </div>
                    <div class="mobile-card-actions">
                        <button class="btn btn-danger" onclick="openDeleteModal(<?= $item->id ?>,'<?= addslashes($item->nombre) ?>')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
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
    // Búsqueda en tiempo real
    document.querySelector('.search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.users-table tbody tr');

        rows.forEach(row => {
            const nombre = row.querySelector('.user-details h4').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            if (nombre.includes(searchTerm) || email.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<?php include __DIR__ . "/../templates/footer.php"; ?>