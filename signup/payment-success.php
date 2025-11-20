<?php
// signup/payment-success.php - Página de pago exitoso
session_start();

// Verificar que vengan del proceso de pago
if (!isset($_SESSION['payment_success'])) {
    header("Location: plans.php");
    exit();
}

$payment_data = $_SESSION['payment_success'];

// Limpiar sesión
unset($_SESSION['payment_success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso - Massola Business</title>
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
        
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            text-align: left;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .detail-label {
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
        
        .action-buttons {
            margin: 30px 0;
        }
        
        .payment-method-badge {
            display: inline-block;
            background: var(--secondary);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
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
            <h1>¡Pago Exitoso!</h1>
            <p>Tu plan ha sido activado correctamente</p>
        </div>
        
        <div class="content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <div class="welcome-message">
                <strong>¡Felicidades <?php echo htmlspecialchars($payment_data['nombre']); ?>!</strong><br>
                Tu plan <strong><?php echo htmlspecialchars($payment_data['plan_nombre']); ?></strong> ha sido activado exitosamente.
            </div>
            
            <div class="order-details">
                <div class="detail-item">
                    <span class="detail-label">Plan contratado:</span>
                    <span><?php echo htmlspecialchars($payment_data['plan_nombre']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Monto pagado:</span>
                    <span>$<?php echo number_format($payment_data['precio_pagado'], 2); ?> CUP</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Método de pago:</span>
                    <span>
                        <?php 
                        $metodo_text = ($payment_data['metodo_pago'] == 'transferencia') ? 'Transferencia Bancaria' : 'Pago en Efectivo';
                        echo $metodo_text; 
                        ?>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Válido hasta:</span>
                    <span><?php echo date('d/m/Y', strtotime($payment_data['fecha_vencimiento'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span><?php echo htmlspecialchars($payment_data['email']); ?></span>
                </div>
            </div>
            
            <div class="next-steps">
                <h3><i class="fas fa-list-alt"></i> Próximos Pasos:</h3>
                <ul>
                    <li><strong>Accede a tu panel de control</strong> para configurar tu negocio</li>
                    <li><strong>Recibirás un email</strong> con las instrucciones de acceso</li>
                    <li><strong>Configura tu perfil de negocio</strong> y añade tus productos</li>
                    <li><strong>Personaliza tu tienda</strong> con tu logo y colores</li>
                    <li><strong>Comienza a recibir pedidos</strong> de inmediato</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="../dashboard/" class="btn btn-success">
                    <i class="fas fa-tachometer-alt"></i> Ir al Dashboard
                </a>
                <a href="plans.php" class="btn">
                    <i class="fas fa-eye"></i> Ver Detalles del Plan
                </a>
            </div>
            
            <div style="margin-top: 25px; font-size: 14px; color: #777;">
                <p>¿Necesitas ayuda? <a href="mailto:soporte@massolagroup.com" style="color: var(--secondary);">Contáctanos</a></p>
            </div>
        </div>
    </div>
</body>
</html>