<?php
session_start();

// Verificar que el usuario venga del proceso de registro
if (!isset($_SESSION['registered_user'])) {
    header("Location: index.php");
    exit();
}

// Obtener datos del usuario registrado
$user = $_SESSION['registered_user'];

// Limpiar la sesión (opcional)
unset($_SESSION['registered_user']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Massola Business</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #2ecc71;
            --light: #ecf0f1;
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }
        
        .header {
            background: var(--success);
            color: white;
            padding: 30px;
        }
        
        .header i {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 40px;
        }
        
        .success-icon {
            font-size: 80px;
            color: var(--success);
            margin-bottom: 20px;
        }
        
        .user-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--primary);
        }
        
        .next-steps {
            background: #e8f5e8;
            border-left: 4px solid var(--success);
            padding: 20px;
            margin: 25px 0;
            text-align: left;
        }
        
        .next-steps h3 {
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .next-steps h3 i {
            margin-right: 10px;
            color: var(--success);
        }
        
        .next-steps ul {
            list-style: none;
            padding-left: 0;
        }
        
        .next-steps li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        
        .next-steps li:before {
            content: "✓";
            color: var(--success);
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--success);
        }
        
        .btn-success:hover {
            background: #27ae60;
        }
        
        .welcome-message {
            font-size: 18px;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        
        .cta-section h3 {
            margin-bottom: 10px;
            font-size: 22px;
        }
        
        .action-buttons {
            margin: 30px 0;
        }
        
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
            }
            
            .content {
                padding: 20px;
            }
            
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-check-circle"></i>
            <h1>¡Registro Exitoso!</h1>
            <p>Bienvenido a Massola Business Platform</p>
        </div>
        
        <div class="content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <div class="welcome-message">
                <strong>¡Felicidades <?php echo htmlspecialchars($user['name']); ?>!</strong><br>
                Tu negocio <strong><?php echo htmlspecialchars($user['business']); ?></strong> ha sido registrado exitosamente.<br>
                <strong style="color: var(--success);">Ahora elige el plan perfecto para impulsar tu negocio.</strong>
            </div>
            
            <div class="user-info">
                <div class="info-item">
                    <span class="info-label">Nombre completo:</span>
                    <span><?php echo htmlspecialchars($user['name']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Negocio:</span>
                    <span><?php echo htmlspecialchars($user['business']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tipo de negocio:</span>
                    <span><?php echo htmlspecialchars($user['business_type']); ?></span>
                </div>
            </div>
            
            <div class="cta-section">
                <h3><i class="fas fa-rocket"></i> ¡Impulsa Tu Negocio!</h3>
                <p>Elige entre nuestros planes semestrales en CUP con descuento especial para Cuba</p>
            </div>
            
            <div class="next-steps">
                <h3><i class="fas fa-list-alt"></i> Próximos Pasos:</h3>
                <ul>
                    <li><strong>Selecciona tu plan</strong> según las necesidades de tu negocio</li>
                    <li><strong>Plan Básico:</strong> $550 CUP/mes - Ideal para empezar</li>
                    <li><strong>Plan Profesional:</strong> $750 CUP/mes - Crecimiento acelerado</li>
                    <li><strong>Plan Empresa:</strong> $1,500 CUP/mes - Máxima escalabilidad</li>
                    <li><strong>15% de descuento</strong> en suscripción semestral para Cuba</li>
                    <li><strong>Configuración inmediata</strong> después del pago</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="../dashboard/" class="btn btn-success">
                    <i class="fas fa-rocket"></i> Ir al Dashboard
                </a>
                <a href="login.php" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            </div>
            
            <div style="margin-top: 25px; font-size: 14px; color: #777;">
                <p>¿Necesitas ayuda? <a href="mailto:soporte@massolagroup.com" style="color: var(--secondary);">Contáctanos</a></p>
            </div>
        </div>
    </div>
</body>
</html>