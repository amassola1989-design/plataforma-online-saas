<?php
session_start();
// Si el usuario está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Massola Business - Plataforma de Gestión de Negocios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--dark);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--secondary);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--secondary);
        }
        
        .auth-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-outline {
            border: 2px solid var(--secondary);
            color: var(--secondary);
        }
        
        .btn-outline:hover {
            background: var(--secondary);
            color: white;
        }
        
        .btn-primary {
            background: var(--secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            padding: 150px 0 100px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: var(--primary);
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
            background: white;
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
            color: #666;
            font-size: 1.1rem;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: var(--light);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 30px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        /* Plans Section */
        .plans {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .plan-card {
            background: white;
            padding: 40px 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .plan-card:hover {
            transform: translateY(-5px);
        }
        
        .plan-card.featured {
            border: 2px solid var(--secondary);
            position: relative;
        }
        
        .plan-card.featured:before {
            content: "Más Popular";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--secondary);
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .plan-name {
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .plan-price {
            font-size: 48px;
            color: var(--secondary);
            margin-bottom: 20px;
        }
        
        .plan-price span {
            font-size: 16px;
            color: #666;
        }
        
        .plan-features {
            list-style: none;
            margin-bottom: 30px;
        }
        
        .plan-features li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .plan-features li:last-child {
            border-bottom: none;
        }
        
        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
        }
        
        /* Burbujas de fondo */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.1);
            z-index: -1;
        }
        
        .bubble-1 {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 5%;
        }
        
        .bubble-2 {
            width: 150px;
            height: 150px;
            top: 50%;
            right: 10%;
        }
        
        .bubble-3 {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 20px;
            }
            
            .nav-links {
                gap: 15px;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 200px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Burbujas de fondo -->
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <div class="bubble bubble-3"></div>
    
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="#" class="logo">
                    Massola<span>Group</span>
                </a>
                <ul class="nav-links">
                    <li><a href="#features">Características</a></li>
                    <li><a href="#plans">Planes</a></li>
                    <li><a href="#contact">Contacto</a></li>
                </ul>
                <div class="auth-buttons">
                    <a href="signup/login.php" class="btn btn-outline">Iniciar Sesión</a>
                    <a href="signup/index.php" class="btn btn-primary">Registrarse</a>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Gestiona Tu Negocio de Forma Inteligente</h1>
            <p>La plataforma todo en uno para emprendedores y pequeñas empresas. Controla tus productos, pedidos y clientes en un solo lugar.</p>
            <div class="hero-buttons">
                <a href="signup/index.php" class="btn btn-primary">Comenzar Gratis</a>
                <a href="#features" class="btn btn-outline">Conocer Más</a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Todo lo que necesitas en un solo lugar</h2>
                <p>Descubre las herramientas que te ayudarán a impulsar tu negocio</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Gestión de Productos</h3>
                    <p>Administra tu inventario, precios y categorías de forma sencilla y ordenada.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Control de Pedidos</h3>
                    <p>Gestiona pedidos, envíos y estado de las ventas en tiempo real.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Reportes y Análisis</h3>
                    <p>Obtén insights valiosos sobre tu negocio con reportes detallados.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Plans Section -->
    <section class="plans" id="plans">
        <div class="container">
            <div class="section-title">
                <h2>Planes que se adaptan a tu negocio</h2>
                <p>Elige el plan perfecto para llevar tu emprendimiento al siguiente nivel</p>
            </div>
            <div class="plans-grid">
                <div class="plan-card">
                    <div class="plan-name">Básico</div>
                    <div class="plan-price">$550 <span>/mes</span></div>
                    <ul class="plan-features">
                        <li>Hasta 10 productos</li>
                        <li>Gestión de pedidos</li>
                        <li>Soporte por email</li>
                        <li>Reportes básicos</li>
                    </ul>
                    <a href="signup/index.php" class="btn btn-outline">Elegir Plan</a>
                </div>
                <div class="plan-card featured">
                    <div class="plan-name">Profesional</div>
                    <div class="plan-price">$750 <span>/mes</span></div>
                    <ul class="plan-features">
                        <li>Hasta 50 productos</li>
                        <li>Gestión de pedidos</li>
                        <li>Soporte prioritario</li>
                        <li>Reportes avanzados</li>
                        <li>Integración con WhatsApp</li>
                    </ul>
                    <a href="signup/index.php" class="btn btn-primary">Elegir Plan</a>
                </div>
                <div class="plan-card">
                    <div class="plan-name">Empresa</div>
                    <div class="plan-price">$1,500 <span>/mes</span></div>
                    <ul class="plan-features">
                        <li>Productos ilimitados</li>
                        <li>Gestión de pedidos</li>
                        <li>Soporte 24/7</li>
                        <li>Reportes personalizados</li>
                        <li>API de integración</li>
                        <li>Múltiples usuarios</li>
                    </ul>
                    <a href="signup/index.php" class="btn btn-outline">Elegir Plan</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Massola Business</h3>
                    <p>La plataforma de gestión empresarial diseñada para emprendedores y pequeñas empresas.</p>
                </div>
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="/terminos-servicios/">Términos de Servicios</a></li>
                        <li><a href="/privacidad/">Política de Privacidad</a></li>
                        <li><a href="/condiciones-uso/">Condiciones de Uso</a></li>
                        <li><a href="/cookies/">Política de Cookies</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contacto</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope"></i> negocios@massolagroup.com</li>
                        <li><i class="fas fa-phone"></i> +53 5354-6331</li>
                        <li><i class="fas fa-map-marker-alt"></i> Miami, Estados Unidos</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Massola Group. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>