<?php
// plans.php - Página de selección de planes
include 'plans-config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes - Massola Business</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #2ecc71;
            --warning: #f39c12;
            --premium: #9b59b6;
            --enterprise: #e74c3c;
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
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .header h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .header p {
            font-size: 1.2rem;
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .plan-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            border: 2px solid #e0e0e0;
            text-align: center;
        }
        
        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .plan-card.featured {
            border-color: var(--premium);
            transform: scale(1.05);
        }
        
        .plan-card.featured:hover {
            transform: scale(1.08);
        }
        
        .plan-card.enterprise {
            border-color: var(--enterprise);
        }
        
        .featured-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: var(--premium);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .enterprise-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: var(--enterprise);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .plan-header {
            margin-bottom: 25px;
        }
        
        .plan-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .price-mensual {
            font-size: 2.2rem;
            font-weight: 800;
            margin: 18px 0;
            color: inherit;
            text-shadow: 0 6px 18px rgba(0,0,0,0.25);
        }
        
        .plan-features {
            list-style: none;
            margin-bottom: 25px;
            display: inline-block;
            text-align: left;
        }
        
        .plan-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .plan-features li:last-child {
            border-bottom: none;
        }
        
        .plan-pricing {
            margin-top: 14px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        
        .discount-text {
            display: inline-block;
            text-align: left;
            max-width: 320px;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .btn-plan {
            display: inline-block;
            padding: 12px 30px;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            margin: 18px auto;
            border: none;
            cursor: pointer;
        }
        
        .btn-plan:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-basico {
            background: var(--secondary);
        }
        
        .btn-profesional {
            background: var(--premium);
        }
        
        .btn-empresa {
            background: var(--enterprise);
        }
        
        .guarantee {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .guarantee h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .plans-grid {
                grid-template-columns: 1fr;
            }
            
            .plan-card.featured {
                transform: none;
            }
            
            .plan-card.featured:hover {
                transform: translateY(-5px);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Planes Massola Business</h1>
            <p>Potencia tu negocio con nuestros planes semestrales en CUP</p>
        </div>
        
        <div class="plans-grid">
            <?php foreach($PLANES_CONFIG as $plan): ?>
            <div class="plan-card <?php echo $plan['id'] == 'profesional' ? 'featured' : ''; ?> <?php echo $plan['id'] == 'empresa' ? 'enterprise' : ''; ?>">
                <?php if($plan['id'] == 'profesional'): ?>
                <div class="featured-badge">MÁS POPULAR</div>
                <?php elseif($plan['id'] == 'empresa'): ?>
                <div class="enterprise-badge">EMPRESA</div>
                <?php endif; ?>
                
                <div class="plan-header">
                    <div class="plan-name"><?php echo $plan['nombre']; ?></div>
                    <p class="price-mensual">
                        <?php echo number_format($plan['precio_mensual'], 0); ?> <?php echo $plan['moneda']; ?>/mes
                    </p>
                </div>
                
                <ul class="plan-features">
                    <?php foreach($plan['caracteristicas'] as $feature): ?>
                    <li><?php echo $feature; ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="plan-pricing">
                    <div class="discount-text">
                        <?php echo $plan['texto_descuento']; ?>
                    </div>
                </div>
                
                <a href="checkout.php?plan=<?php echo $plan['id']; ?>" class="btn-plan btn-<?php echo $plan['id']; ?>">
                    <?php echo $plan['id'] == 'basico' ? 'Comenzar Ahora' : 'Elegir Plan'; ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="guarantee">
            <h3><i class="fas fa-shield-alt"></i> Plataforma Confiable</h3>
            <p>Hosting en Cuba con soporte local y certificado SSL incluido en todos los planes</p>
        </div>
    </div>
</body>
</html>