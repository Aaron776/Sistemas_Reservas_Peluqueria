<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleCut - Peluquería Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8a5a44;
            --secondary: #d4b8a5;
            --accent: #c17a4a;
            --dark: #3a2e26;
            --light: #f8f4f0;
            --text: #333333;
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
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header y Navegación */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo h1 {
            color: var(--primary);
            font-size: 28px;
            font-weight: 700;
        }
        
        .logo span {
            color: var(--accent);
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-login:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-register {
            background-color: var(--primary);
            border: 2px solid var(--primary);
            color: white;
        }
        
        .btn-register:hover {
            background-color: transparent;
            color: var(--primary);
        }
        
        .btn-book {
            background-color: var(--accent);
            border: 2px solid var(--accent);
            color: white;
        }
        
        .btn-book:hover {
            background-color: transparent;
            color: var(--accent);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1562322140-8baeececf3df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h2 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        /* Services Section */
        .services {
            padding: 80px 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: var(--text);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background-color: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
        }
        
        .service-img {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .service-content {
            padding: 20px;
        }
        
        .service-content h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .service-content p {
            margin-bottom: 15px;
        }
        
        .price {
            color: var(--accent);
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        /* Horarios */
        .schedule {
            padding: 80px 0;
            background-color: var(--light);
        }
        
        .schedule-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
        }
        
        .schedule-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
        }
        
        .schedule-card h3 {
            color: var(--primary);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .time-slots {
            list-style: none;
        }
        
        .time-slots li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .time-slots li:last-child {
            border-bottom: none;
        }
        
        /* Call to Action */
        .cta {
            background-color: var(--primary);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta p {
            max-width: 600px;
            margin: 0 auto 30px;
            font-size: 1.1rem;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-section h3 {
            margin-bottom: 20px;
            color: var(--secondary);
        }
        
        .footer-section p, .footer-section li {
            margin-bottom: 10px;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-section a:hover {
            color: var(--secondary);
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            transition: background-color 0.3s ease;
        }
        
        .social-icons a:hover {
            background-color: var(--accent);
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 20px;
            }
            
            .hero h2 {
                font-size: 2rem;
            }
            
            .nav-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <h1>Style<span>Cut</span></h1>
                </div>
                <div class="nav-buttons">
                    <a href="login.php" class="btn btn-login">Iniciar Sesión</a>
                    <a href="registro_usuario.php" class="btn btn-register">Registrarse</a>
                    <a href="login.php" class="btn btn-book">Reservar Cita</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Tu estilo, nuestra expertise</h2>
                <p>Descubre los mejores servicios de peluquería con profesionales dedicados a realzar tu belleza única.</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <div class="section-title">
                <h2>Nuestros Servicios</h2>
                <p>Ofrecemos una amplia gama de servicios de belleza y peluquería con los mejores estándares de calidad.</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-img" style="background-image: url('https://images.unsplash.com/photo-1599351431202-1e0f0137899a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="service-content">
                        <h3>Corte de Cabello</h3>
                        <p>Corte moderno y personalizado según tu estilo y preferencias.</p>
                        <p class="price">Desde $25.000</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-img" style="background-image: url('https://images.unsplash.com/photo-1558591710-4b4a1ae0f04d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="service-content">
                        <h3>Coloración</h3>
                        <p>Servicios de coloración profesional con productos de alta calidad.</p>
                        <p class="price">Desde $45.000</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-img" style="background-image: url('https://images.unsplash.com/photo-1622288432450-277d0fef5ed6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="service-content">
                        <h3>Tratamientos</h3>
                        <p>Tratamientos capilares para rejuvenecer y nutrir tu cabello.</p>
                        <p class="price">Desde $35.000</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section class="schedule">
        <div class="container">
            <div class="section-title">
                <h2>Nuestros Horarios</h2>
                <p>Estamos abiertos para servirte en horarios convenientes durante toda la semana.</p>
            </div>
            <div class="schedule-container">
                <div class="schedule-card">
                    <h3>Horario de Atención</h3>
                    <ul class="time-slots">
                        <li><span>Lunes - Viernes</span> <span>9:00 AM - 7:00 PM</span></li>
                        <li><span>Sábados</span> <span>9:00 AM - 5:00 PM</span></li>
                        <li><span>Domingos</span> <span>10:00 AM - 2:00 PM</span></li>
                    </ul>
                </div>
                <div class="schedule-card">
                    <h3>Servicios Express</h3>
                    <ul class="time-slots">
                        <li><span>Lunes - Miércoles</span> <span>9:00 AM - 12:00 PM</span></li>
                        <li><span>Jueves - Viernes</span> <span>4:00 PM - 7:00 PM</span></li>
                        <li><span>Sábados</span> <span>9:00 AM - 11:00 AM</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
        <div class="container">
            <h2>¿Qué esperas para tu nuevo look?</h2>
            <p>Reserva ahora mismo una cita con nuestros estilistas profesionales y luce espectacular.</p>
            <a href="cita.php" class="btn btn-book">Reservar Cita</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>StyleCut</h3>
                    <p>Tu peluquería de confianza con los mejores servicios y profesionales dedicados a realzar tu belleza.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#">Inicio</a></li>
                        <li><a href="#">Servicios</a></li>
                        <li><a href="#">Promociones</a></li>
                        <li><a href="#">Sobre Nosotros</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contacto</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Calle Principal #123, Ciudad</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@stylecut.com</p>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 StyleCut - Todos los derechos reservados</p>
            </div>
        </div>
    </footer>
</body>
</html>