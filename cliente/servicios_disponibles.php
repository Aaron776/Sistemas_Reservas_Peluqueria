<?php
include_once __DIR__ . '/../autorizacion/auth.php';

// Verificar que tenga rol de cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: " . __DIR__ . "/../index.php"); // si no lo mandamos al index
    exit();
}

include_once __DIR__ . '/../templates/header.php';
include_once __DIR__ . '/../conexion/bd.php';

// Obtenemos los servicios disponibles
$sql = $conexion->prepare("SELECT nombre,descripcion,precio,duracion FROM servicios");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);
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
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: linear-gradient(135deg, #f8f4f0 0%, #f0e6dc 100%);
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
        text-align: center;
        margin-bottom: 40px;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .header h1 {
        color: var(--primary);
        font-size: 36px;
        margin-bottom: 10px;
    }

    .header p {
        color: #6c757d;
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto;
    }

    .filters {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 20px;
        background: white;
        border: 2px solid var(--secondary);
        border-radius: 25px;
        color: var(--dark);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .service-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .service-card.featured {
        border: 3px solid var(--accent);
    }

    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        z-index: 2;
    }

    .service-image {
        height: 200px;
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
        padding: 25px;
    }

    .service-category {
        color: var(--accent);
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .service-title {
        color: var(--primary);
        font-size: 22px;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .service-description {
        color: #6c757d;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .service-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: var(--light);
        border-radius: 10px;
    }

    .service-price {
        font-size: 24px;
        font-weight: 700;
        color: var(--accent);
    }

    .service-duration {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--dark);
        font-weight: 600;
    }

    .service-features {
        list-style: none;
        margin-bottom: 25px;
    }

    .service-features li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .service-features li:last-child {
        border-bottom: none;
    }

    .service-features i {
        color: var(--success);
    }

    .btn-book {
        display: block;
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
    }

    .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(138, 90, 68, 0.3);
    }

    .btn-book i {
        margin-right: 8px;
    }

    .testimonials {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
    }

    .testimonials h2 {
        text-align: center;
        color: var(--primary);
        margin-bottom: 30px;
        font-size: 28px;
    }

    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .testimonial {
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid var(--accent);
    }

    .testimonial-text {
        font-style: italic;
        margin-bottom: 15px;
        color: var(--dark);
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .author-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-weight: bold;
    }

    .author-info h4 {
        color: var(--primary);
        margin-bottom: 2px;
    }

    .author-info p {
        color: #6c757d;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .services-grid {
            grid-template-columns: 1fr;
        }

        .filters {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 28px;
        }

        .testimonials {
            padding: 25px;
        }
    }

    .service-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
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
</style>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-scissors"></i> Nuestros Servicios</h1>
        <p>Descubre todos los tratamientos de belleza y cuidado personal que tenemos para ti</p>
    </div>

    <div class="filters">
        <button class="filter-btn active" data-filter="all">Todos los Servicios</button>
        <button class="filter-btn" data-filter="hair">Cortes & Peinados</button>
        <button class="filter-btn" data-filter="color">Coloración</button>
        <button class="filter-btn" data-filter="treatment">Tratamientos</button>
        <button class="filter-btn" data-filter="spa">Spa & Bienestar</button>
    </div>

    <div class="services-grid">
        <!-- Servicio 1 -->
        <?php foreach ($servicios as $item) : ?>
            <div class="service-card featured" data-category="hair">
                <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');"></div>
                <div class="service-content">
                    <h3 class="service-title"><?php echo $item->nombre; ?></h3>
                    <p class="service-description"><?php echo $item->descripcion; ?>.</p>

                    <div class="service-details">
                        <div class="service-price">$<?php echo $item->precio; ?></div>
                        <div class="service-duration"><i class="fas fa-clock"></i> <?php echo $item->duracion; ?> min</div>
                    </div>

                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Asesoría de estilo personalizada</li>
                        <li><i class="fas fa-check"></i> Productos premium incluidos</li>
                        <li><i class="fas fa-check"></i> Técnicas de corte actualizadas</li>
                    </ul>

                    <a href="reservar_cita.php" class="btn-book">
                        <i class="fas fa-calendar-plus"></i> Agendar Cita
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Filtros de servicios
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remover clase active de todos los botones
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Agregar clase active al botón clickeado
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                const services = document.querySelectorAll('.service-card');

                services.forEach(service => {
                    if (filter === 'all' || service.getAttribute('data-category') === filter) {
                        service.style.display = 'block';
                    } else {
                        service.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <?php include_once __DIR__ . '/../templates/footer.php'; ?>