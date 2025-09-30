<?php
include __DIR__ . "/autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de cliente o de admin
if ($_SESSION['rol'] !== 'cliente' && $_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/templates/header.php";

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario actual
?>
<style>
    :root {
        --primary: #8a5a44;
        --secondary: #d4b8a5;
        --accent: #c17a4a;
        --dark: #3a2e26;
        --light: #f8f4f0;
        --text: #333333;
        --success: #4caf50;
        --error: #f44336;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: var(--light);
        color: var(--text);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .password-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        min-height: 100vh;
    }

    .password-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        overflow: hidden;
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

    .password-header {
        background-color: var(--primary);
        color: white;
        padding: 25px;
        text-align: center;
    }

    .password-header h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .password-header p {
        opacity: 0.9;
    }

    .password-form {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        border-color: var(--accent);
        outline: none;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-size: 16px;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--accent);
    }

    .btn-primary:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    .message {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: none;
    }

    .message-success {
        background-color: #e6f7ee;
        color: var(--success);
        border: 1px solid #c3e6cb;
    }

    .message-error {
        background-color: #ffe6e6;
        color: var(--error);
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 576px) {
        .password-header h1 {
            font-size: 24px;
        }

        body {
            padding: 10px;
        }

        .password-form {
            padding: 20px;
        }
    }
</style>

<div class="password-wrapper">
    <div class="password-container">
        <div class="password-header">
            <h1>Cambiar Contraseña</h1>
            <p>Protege tu cuenta con una contraseña segura</p>
        </div>

        <form class="password-form" id="password-form" action="controladores/cambiar_password.php" method="POST">
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
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

            <div class="form-group">
                <label for="current-password">Contraseña Actual</label>
                <input type="password" id="current-password" name="password_actual" class="form-input" placeholder="Ingresa tu contraseña actual" required>
            </div>

            <div class="form-group">
                <label for="new-password">Nueva Contraseña</label>
                <input type="password" id="new-password" name="password_nueva" class="form-input" placeholder="Crea una nueva contraseña" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" id="submit-button">Cambiar Contraseña</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . "/templates/footer.php"; ?>