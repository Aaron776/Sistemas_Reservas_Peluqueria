<?php
include __DIR__ . "/autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de cliente o de admin
if ($_SESSION['rol'] !== 'cliente' && $_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/templates/header.php";
include __DIR__ . "/conexion/bd.php";

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario actual

// Obtener los datos del usuario actual para editar los datos de perfil
$sql = $conexion->prepare("SELECT id,nombre,telefono,email FROM usuarios WHERE id = :id_usuario");
$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->execute();
$usuario = $sql->fetch(PDO::FETCH_OBJ);
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
        max-width: 900px;
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

    .profile-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .profile-content {
        padding: 40px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 40px;
        padding-bottom: 25px;
        border-bottom: 2px solid var(--light);
    }

    .avatar-section {
        text-align: center;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark);
        font-weight: bold;
        font-size: 40px;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .avatar-edit {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 35px;
        height: 35px;
        background: var(--primary);
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar-edit:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

    .avatar-upload {
        display: none;
    }

    .user-info h2 {
        color: var(--primary);
        margin-bottom: 5px;
        font-size: 24px;
    }

    .user-info p {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .user-stats {
        display: flex;
        gap: 20px;
        margin-top: 15px;
    }

    .stat {
        text-align: center;
    }

    .stat-number {
        font-size: 18px;
        font-weight: bold;
        color: var(--primary);
    }

    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
    }

    .form-section {
        margin-bottom: 35px;
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
        margin-bottom: 25px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
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

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 14px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background-color: #fafafa;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--accent);
        outline: none;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(193, 122, 74, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    .form-help {
        font-size: 13px;
        color: #6c757d;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Toggle Switch */
    .toggle-group {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 10px;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.toggle-slider {
        background-color: var(--success);
    }

    input:checked+.toggle-slider:before {
        transform: translateX(30px);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid #e9ecef;
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
        text-decoration: none;
        text-align: center;
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

    .btn-outline {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-outline:hover {
        background: var(--primary);
        color: white;
    }

    /* Security Section */
    .security-section {
        background: var(--light);
        border-radius: 10px;
        padding: 25px;
        margin-top: 30px;
        border-left: 4px solid var(--accent);
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

    .security-section h4 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .security-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    /* Notification Preferences */
    .preferences-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 15px;
    }

    .preference-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .preference-info h5 {
        margin-bottom: 3px;
        color: var(--dark);
    }

    .preference-info p {
        font-size: 13px;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .user-stats {
            justify-content: center;
        }

        .security-actions {
            grid-template-columns: 1fr;
        }

        .preferences-grid {
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

        .profile-content {
            padding: 25px 20px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            font-size: 32px;
        }
    }
</style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-user-edit"></i> Editar Perfil</h1>
            <p>Actualiza tu información personal</p>
        </div>

        <div class="profile-card">
            <form class="profile-content" id="profile-form" action="controladores/actualizar_perfil.php" method="POST">
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

                <input type="hidden" name="id_usuario" value="<?php echo $usuario->id; ?>">

                <!-- Información Personal -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-user-circle"></i> Información Personal</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-signature"></i> Nombre Completo *
                            </label>
                            <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo htmlspecialchars($usuario->nombre); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Correo Electrónico *
                            </label>
                            <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($usuario->email); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">
                                <i class="fas fa-phone"></i> Teléfono
                            </label>
                            <input type="text" id="telefono" name="telefono" class="form-input" value="<?php echo htmlspecialchars($usuario->telefono); ?>" placeholder="+56 9 1234 5678">
                            <div class="form-help">
                                <i class="fas fa-info-circle"></i> Opcional - Para notificaciones importantes
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Acciones del Formulario -->
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para restablecer el formulario
        function resetForm() {
            if (confirm('¿Estás seguro de que quieres restablecer todos los cambios?')) {
                document.getElementById('profile-form').reset();
                document.getElementById('displayName').textContent = '<?php echo htmlspecialchars($_SESSION['nombre']); ?>';
                const avatarDisplay = document.getElementById('avatarDisplay');
                avatarDisplay.style.backgroundImage = 'none';
                avatarDisplay.innerHTML = '<?php echo strtoupper(substr($_SESSION['nombre'], 0, 1)); ?>';
            }
        }
    </script>

    <?php include __DIR__ . "/templates/footer.php"; ?>