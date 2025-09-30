<?php
include __DIR__ . "/../autorizacion/auth.php"; // valida login y arranca sesión

// Verificar que tenga rol de admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); // si no lo mandamos al login
    exit();
}

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../conexion/bd.php";
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

    .services-card {
        background: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .services-actions {
        padding: 20px;
        background: var(--light);
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(138, 90, 68, 0.3);
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 300px;
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

    .services-stats {
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

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        padding: 25px;
    }

    .service-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary);
    }

    .service-card.featured {
        border-color: var(--accent);
        position: relative;
    }

    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent);
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        z-index: 2;
    }

    .service-image {
        height: 180px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .service-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60%;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.1));
    }

    .service-content {
        padding: 20px;
    }

    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .service-category {
        color: var(--accent);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .service-title {
        color: var(--primary);
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .service-price {
        font-size: 22px;
        font-weight: 700;
        color: var(--accent);
        text-align: right;
    }

    .service-description {
        color: #6c757d;
        margin-bottom: 15px;
        line-height: 1.5;
        font-size: 14px;
    }

    .service-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 12px;
        background: var(--light);
        border-radius: 8px;
    }

    .service-duration {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--dark);
        font-weight: 600;
        font-size: 14px;
    }

    .service-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 600;
    }

    .status-active {
        color: var(--success);
    }

    .status-inactive {
        color: var(--error);
    }

    .service-features {
        list-style: none;
        margin-bottom: 20px;
    }

    .service-features li {
        padding: 6px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .service-features li:last-child {
        border-bottom: none;
    }

    .service-features i {
        color: var(--success);
        font-size: 12px;
    }

    .service-actions {
        display: flex;
        gap: 10px;
    }

    .btn-edit {
        background: var(--info);
        color: white;
        flex: 1;
    }

    .btn-edit:hover {
        background: #138496;
    }

    .btn-delete {
        background: var(--error);
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .no-services {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        grid-column: 1 / -1;
    }

    .no-services i {
        font-size: 60px;
        margin-bottom: 20px;
        color: #dee2e6;
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
        .services-grid {
            grid-template-columns: 1fr;
        }

        .services-actions {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .services-stats {
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

        .services-stats {
            grid-template-columns: 1fr;
        }

        .service-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-scissors"></i> Gestión de Servicios</h1>
            <p>Administra los servicios ofrecidos por la peluquería</p>
        </div>

        <div class="services-card">
            <div class="services-actions">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Servicio
                </button>

                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Buscar servicios...">
                </div>
            </div>

            <div class="services-stats">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-scissors"></i></div>
                    <div class="stat-number">12</div>
                    <div class="stat-label">Servicios Activos</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number">8</div>
                    <div class="stat-label">Servicios Populares</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-number">45min</div>
                    <div class="stat-label">Duración Promedio</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-number">$35.000</div>
                    <div class="stat-label">Precio Promedio</div>
                </div>
            </div>

            <div class="services-grid">
                <!-- Servicio 1 -->
                <div class="service-card featured">
                    <div class="featured-badge">Popular</div>
                    <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');"></div>
                    <div class="service-content">
                        <div class="service-header">
                            <div>
                                <div class="service-category">Cortes & Peinados</div>
                                <h3 class="service-title">Corte de Cabello Premium</h3>
                            </div>
                            <div class="service-price">$25.000</div>
                        </div>

                        <p class="service-description">
                            Corte personalizado según tu tipo de rostro y estilo de vida. Incluye lavado, secado y acabado profesional.
                        </p>

                        <div class="service-details">
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> 45 minutos
                            </div>
                            <div class="service-status status-active">
                                <i class="fas fa-circle"></i> Activo
                            </div>
                        </div>

                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Asesoría de estilo personalizada</li>
                            <li><i class="fas fa-check"></i> Productos premium incluidos</li>
                            <li><i class="fas fa-check"></i> Técnicas de corte actualizadas</li>
                        </ul>

                        <div class="service-actions">
                            <button class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-delete" onclick="openDeleteModal(1, 'Corte de Cabello Premium')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Servicio 2 -->
                <div class="service-card">
                    <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1559599101-f09722fb4948?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');"></div>
                    <div class="service-content">
                        <div class="service-header">
                            <div>
                                <div class="service-category">Coloración</div>
                                <h3 class="service-title">Coloración Profesional</h3>
                            </div>
                            <div class="service-price">$45.000</div>
                        </div>

                        <p class="service-description">
                            Desde reflejos sutiles hasta cambios radicales de color. Usamos tintes de alta calidad que cuidan tu cabello.
                        </p>

                        <div class="service-details">
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> 2 horas
                            </div>
                            <div class="service-status status-active">
                                <i class="fas fa-circle"></i> Activo
                            </div>
                        </div>

                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Tintes libres de amoníaco</li>
                            <li><i class="fas fa-check"></i> Diagnóstico capilar gratuito</li>
                            <li><i class="fas fa-check"></i> Tratamiento hidratante incluido</li>
                        </ul>

                        <div class="service-actions">
                            <button class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-delete" onclick="openDeleteModal(2, 'Coloración Profesional')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Servicio 3 -->
                <div class="service-card">
                    <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');"></div>
                    <div class="service-content">
                        <div class="service-header">
                            <div>
                                <div class="service-category">Tratamientos</div>
                                <h3 class="service-title">Tratamiento Capilar Reconstituyente</h3>
                            </div>
                            <div class="service-price">$35.000</div>
                        </div>

                        <p class="service-description">
                            Recupera la salud de tu cabello con nuestro tratamiento reconstructor con keratina y proteínas.
                        </p>

                        <div class="service-details">
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> 1.5 horas
                            </div>
                            <div class="service-status status-active">
                                <i class="fas fa-circle"></i> Activo
                            </div>
                        </div>

                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Recuperación de cabello dañado</li>
                            <li><i class="fas fa-check"></i> Brillo y suavidad inmediatos</li>
                            <li><i class="fas fa-check"></i> Efecto duradero hasta 6 semanas</li>
                        </ul>

                        <div class="service-actions">
                            <button class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-delete" onclick="openDeleteModal(3, 'Tratamiento Capilar Reconstituyente')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Servicio 4 -->
                <div class="service-card">
                    <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');"></div>
                    <div class="service-content">
                        <div class="service-header">
                            <div>
                                <div class="service-category">Spa & Bienestar</div>
                                <h3 class="service-title">Manicura & Pedicura Spa</h3>
                            </div>
                            <div class="service-price">$30.000</div>
                        </div>

                        <p class="service-description">
                            Servicio completo de belleza para manos y pies. Incluye masaje relajante y esmaltado de larga duración.
                        </p>

                        <div class="service-details">
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> 1 hora
                            </div>
                            <div class="service-status status-inactive">
                                <i class="fas fa-circle"></i> Inactivo
                            </div>
                        </div>

                        <ul class="service-features">
                            <li><i class="fas fa-check"></i> Limpieza y exfoliación profunda</li>
                            <li><i class="fas fa-check"></i> Masaje con aceites esenciales</li>
                            <li><i class="fas fa-check"></i> Esmaltado semipermanente opcional</li>
                        </ul>

                        <div class="service-actions">
                            <button class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-delete" onclick="openDeleteModal(4, 'Manicura & Pedicura Spa')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeDeleteModal()">&times;</button>
            <h2><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h2>
            <p id="modalServiceInfo">¿Estás seguro de que deseas eliminar este servicio?</p>
            <p style="color: var(--error); font-weight: 600;">
                <i class="fas fa-info-circle"></i> Esta acción no se puede deshacer y afectará las citas futuras.
            </p>
            <div class="modal-buttons">
                <button class="btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <button class="btn btn-delete" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Eliminar Definitivamente
                </button>
            </div>
        </div>
    </div>

    <script>
        let serviceToDelete = null;

        function openDeleteModal(serviceId, serviceName) {
            serviceToDelete = serviceId;
            document.getElementById('modalServiceInfo').textContent =
                `¿Estás seguro de que deseas eliminar el servicio "${serviceName}"?`;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            serviceToDelete = null;
        }

        function confirmDelete() {
            if (serviceToDelete) {
                // Simular eliminación
                const deleteBtn = document.querySelector('#deleteModal .btn-delete');
                const originalText = deleteBtn.innerHTML;

                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
                deleteBtn.disabled = true;

                setTimeout(() => {
                    alert(`Servicio con ID ${serviceToDelete} eliminado exitosamente.`);
                    closeDeleteModal();
                    // Aquí iría la actualización de la lista
                    // window.location.reload();
                }, 1500);
            }
        }

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        });

        // Búsqueda en tiempo real
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const services = document.querySelectorAll('.service-card');

            services.forEach(service => {
                const title = service.querySelector('.service-title').textContent.toLowerCase();
                const description = service.querySelector('.service-description').textContent.toLowerCase();
                const category = service.querySelector('.service-category').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm) || category.includes(searchTerm)) {
                    service.style.display = 'block';
                } else {
                    service.style.display = 'none';
                }
            });
        });
    </script>
<?php include __DIR__ . "/../templates/footer.php"; ?>