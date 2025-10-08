<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleCut - Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .recovery-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }
        
        .recovery-header {
            background-color: var(--primary);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .back-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
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
        
        .recovery-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .recovery-header p {
            opacity: 0.9;
        }
        
        .recovery-steps {
            display: flex;
            justify-content: space-between;
            padding: 15px 30px;
            background-color: var(--light);
            position: relative;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .step.active .step-number {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .step.completed .step-number {
            background-color: var(--success);
            color: white;
            border-color: var(--success);
        }
        
        .step-text {
            font-size: 0.8rem;
            text-align: center;
        }
        
        .step-line {
            position: absolute;
            top: 30px;
            left: 80px;
            right: 80px;
            height: 2px;
            background-color: #ccc;
            z-index: 1;
        }
        
        .recovery-form {
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
        
        .code-inputs {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .code-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        
        .code-input:focus {
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
        
        .btn-secondary {
            background-color: var(--light);
            color: var(--dark);
            border: 1px solid #ddd;
        }
        
        .btn-secondary:hover {
            background-color: #e8e8e8;
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
        
        .message-info {
            background-color: #e6f3ff;
            color: #0066cc;
            border: 1px solid #b3d9ff;
        }
        
        .resend-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .resend-text {
            color: #666;
            margin-bottom: 10px;
        }
        
        .countdown {
            font-weight: bold;
            color: var(--primary);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .form-footer a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
        }
        
        @media (max-width: 576px) {
            .recovery-header h1 {
                font-size: 24px;
            }
            
            .back-button span {
                display: none;
            }
            
            .code-input {
                width: 40px;
                height: 50px;
                font-size: 20px;
            }
            
            .step-text {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <div class="recovery-header">
            <a href="login.html" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Volver</span>
            </a>
            <h1>Recuperar Contraseña</h1>
        </div>
        
        <form class="recovery-form" id="recovery-form" method="POST" action="controladores/olvido_password.php">
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
            <!-- Paso 1: Ingresar email -->
            <div class="step-content active" id="step1-content">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" class="form-input" name="email" placeholder="Ingresa tu correo electrónico" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="step1-button">Enviar Código de Verificación</button>
                </div>
            </div>
        </form>
        
        <div class="form-footer">
            <p>¿Recordaste tu contraseña? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>
</html>