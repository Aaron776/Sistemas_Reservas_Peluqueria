<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: /Sistemas_Web_PHP/Sistema_Web_Citas_Peluqueria/index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";

// Obtener el servicio por ID
$id_servicio = $_GET['id_servicio'];

if(!isset($id_servicio) || empty($id_servicio)){
    die("No se proporcionó un ID de servicio válido.");
}

// Obtener el servicio que queremos editar
$sql = $conexion->prepare("SELECT id,nombre,descripcion,precio,duracion FROM servicios WHERE id = :id_servicio");
$sql->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
$sql->execute();
$servicio = $sql->fetch(PDO::FETCH_OBJ);

if (!$servicio) {
    die("Servicio no encontrado.");
}
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
        max-width: 800px;
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

    .service-form-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-content {
        padding: 40px;
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

    .form-group label .required {
        color: var(--error);
        margin-left: 4px;
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
        min-height: 120px;
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

    /* Image Upload */
    .image-upload {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .image-upload:hover {
        border-color: var(--accent);
        background: #f8f9fa;
    }

    .image-upload-icon {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .image-upload-text {
        margin-bottom: 15px;
    }

    .image-upload-text h4 {
        color: var(--dark);
        margin-bottom: 5px;
    }

    .image-upload-text p {
        color: #6c757d;
        font-size: 14px;
    }

    .btn-upload {
        background: var(--primary);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-upload:hover {
        background: var(--primary-dark);
    }

    .image-preview {
        display: none;
        margin-top: 15px;
        text-align: center;
    }

    .image-preview img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    /* Features Section */
    .features-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .feature-item input[type="text"] {
        flex: 1;
        border: none;
        background: transparent;
        padding: 8px;
        font-size: 14px;
    }

    .feature-item input[type="text"]:focus {
        outline: none;
    }

    .btn-add-feature {
        background: var(--success);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-add-feature:hover {
        background: #218838;
    }

    .btn-remove-feature {
        background: var(--error);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-remove-feature:hover {
        background: #c82333;
    }

    /* Status Toggle */
    .status-toggle {
        display: flex;
        gap: 20px;
        margin-top: 10px;
    }

    .toggle-option {
        flex: 1;
    }

    .toggle-option input {
        display: none;
    }

    .toggle-label {
        display: block;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .toggle-option input:checked+.toggle-label {
        border-color: var(--success);
        background: #e6f7ee;
        color: var(--success);
        font-weight: 600;
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

    /* Preview Section */
    .preview-section {
        background: var(--light);
        border-radius: 10px;
        padding: 25px;
        margin-top: 30px;
        border-left: 4px solid var(--accent);
    }

    .preview-section h4 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .preview-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
        align-items: start;
    }

    .preview-image {
        width: 100%;
        height: 150px;
        background: #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    .preview-details h5 {
        color: var(--dark);
        margin-bottom: 10px;
        font-size: 18px;
    }

    .preview-details p {
        color: #6c757d;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .preview-price {
        font-size: 20px;
        font-weight: bold;
        color: var(--accent);
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .preview-content {
            grid-template-columns: 1fr;
        }

        .features-list {
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

        .form-content {
            padding: 25px 20px;
        }

        .status-toggle {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Editar Servicio</h1>
            <p>Edita los detalles del servicio</p>
        </div>

        <div class="service-form-card">
            <form class="form-content" id="service-form" action="../controladores/editar_servicio.php" method="POST" enctype="multipart/form-data">
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
                <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Información Básica</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-tag"></i> Nombre del Servicio <span class="required">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo htmlspecialchars($servicio->nombre); ?>" placeholder="Ej: Corte de Cabello Premium" required>
                            <div class="form-help">
                                <i class="fas fa-lightbulb"></i> El nombre debe ser claro y atractivo para los clientes
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">
                                <i class="fas fa-align-left"></i> Descripción <span class="required">*</span>
                            </label>
                            <textarea id="descripcion" name="descripcion" class="form-textarea" placeholder="Describe detalladamente el servicio, beneficios y características..." required><?php echo htmlspecialchars($servicio->descripcion); ?></textarea>
                            <div class="form-help">
                                <i class="fas fa-lightbulb"></i> Una buena descripción ayuda a los clientes a entender el servicio
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Precios y Duración -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-money-bill-wave"></i> Precios y Duración</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="precio">
                                <i class="fas fa-dollar-sign"></i> Precio <span class="required">*</span>
                            </label>
                            <input type="number" id="precio" name="precio" value="<?php echo htmlspecialchars($servicio->precio); ?>" class="form-input" placeholder="0" min="0" step="0.01" required>
                            <div class="form-help">Precio en dolares ($)</div>
                        </div>

                        <div class="form-group">
                            <label for="duracion">
                                <i class="fas fa-clock"></i> Duración <span class="required">*</span>
                            </label>
                            <input type="number" id="duracion" name="duracion" value="<?php echo htmlspecialchars($servicio->duracion); ?>" class="form-input" placeholder="0" min="0" required>
                            <div class="form-help">Duración en minutos</div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="gestion_servicios.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar Servicio
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Vista previa en tiempo real
        document.getElementById('nombre').addEventListener('input', updatePreview);
        document.getElementById('descripcion').addEventListener('input', updatePreview);
        document.getElementById('precio').addEventListener('input', updatePreview);
        document.getElementById('duracion').addEventListener('change', updatePreview);
        document.getElementById('categoria').addEventListener('change', updatePreview);
        document.getElementById('imagen').addEventListener('change', handleImageUpload);

        function updatePreview() {
            document.getElementById('previewNombre').textContent =
                document.getElementById('nombre').value || 'Nombre del Servicio';

            document.getElementById('previewDescripcion').textContent =
                document.getElementById('descripcion').value || 'Descripción del servicio aparecerá aquí...';

            const precio = document.getElementById('precio').value;
            document.getElementById('previewPrecio').textContent =
                precio ? `$${parseInt(precio).toLocaleString()}` : '$0';

            const duracion = document.getElementById('duracion').value;
            document.getElementById('previewDuracion').textContent =
                duracion ? `${duracion} min` : '0 min';

            const categoria = document.getElementById('categoria');
            const categoriaText = categoria.options[categoria.selectedIndex]?.text || 'Categoría';
            document.getElementById('previewCategoria').textContent = categoriaText;
        }

        // Manejo de subida de imagen
        function handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    const previewPlaceholder = document.getElementById('previewImagePlaceholder');
                    const imagePreview = document.getElementById('imagePreview');

                    preview.src = e.target.result;
                    previewPlaceholder.style.display = 'none';
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        // Gestión de características
        function addFeature() {
            const featuresList = document.getElementById('featuresList');
            const newFeature = document.createElement('div');
            newFeature.className = 'feature-item';
            newFeature.innerHTML = `
                <input type="text" name="caracteristicas[]" placeholder="Ej: Productos premium incluidos">
                <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            featuresList.appendChild(newFeature);
        }

        function removeFeature(button) {
            const features = document.querySelectorAll('.feature-item');
            if (features.length > 1) {
                button.parentElement.remove();
            } else {
                button.previousElementSibling.value = '';
            }
        }

        // Validación del formulario
        document.getElementById('service-form').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value;
            const precio = document.getElementById('precio').value;
            const duracion = document.getElementById('duracion').value;

            if (!nombre || !precio || !duracion) {
                e.preventDefault();
                alert('Por favor completa todos los campos requeridos (*)');
                return;
            }

            // Mostrar loading
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            submitBtn.disabled = true;
        });

        // Inicializar vista previa
        updatePreview();
    </script>
    <?php include __DIR__ . "/../templates/footer.php"; ?>