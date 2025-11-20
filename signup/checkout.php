<?php
// signup/checkout.php - Página de checkout/pago
session_start();

// Incluir configuración de planes
include 'plans-config.php';

// Verificar que vengan de la página de planes
$plan_id = $_GET['plan'] ?? '';
if (!isset($PLANES_CONFIG[$plan_id])) {
    header("Location: plans.php");
    exit();
}

$plan = $PLANES_CONFIG[$plan_id];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Massola Business</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #2ecc71;
            --warning: #f39c12;
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
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            grid-column: 1 / -1;
        }
        
        .header h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .plan-badge {
            background: var(--secondary);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .price-breakdown {
            margin: 20px 0;
        }
        
        .price-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .price-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 20px 0;
        }
        
        .payment-method {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method:hover {
            border-color: var(--secondary);
        }
        
        .payment-method.selected {
            border-color: var(--success);
            background: #e8f5e8;
        }
        
        .payment-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #7f8c8d;
        }
        
        .btn-pay {
            display: block;
            width: 100%;
            padding: 15px;
            background: var(--success);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .btn-pay:hover {
            background: #27ae60;
            transform: translateY(-2px);
        }
        
        .security-notice {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .security-notice i {
            color: var(--success);
            margin-right: 5px;
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .payment-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shopping-cart"></i> Finalizar Compra</h1>
            <p>Completa tu información para activar tu plan</p>
        </div>
        
        <div class="checkout-form">
            <form id="paymentForm" method="POST" action="payment-process.php">
                <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">
                
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user"></i> Información de Facturación</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo *</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">Teléfono *</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ciudad">Ciudad *</label>
                            <input type="text" id="ciudad" name="ciudad" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección Completa *</label>
                        <textarea id="direccion" name="direccion" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-credit-card"></i> Método de Pago</h2>
                    <div class="payment-methods">
                        <div class="payment-method" onclick="selectPayment('transferencia')">
                            <div class="payment-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>Transferencia Bancaria</div>
                        </div>
                        <div class="payment-method" onclick="selectPayment('efectivo')">
                            <div class="payment-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>Pago en Efectivo</div>
                        </div>
                    </div>
                    <input type="hidden" id="metodo_pago" name="metodo_pago" required>
                </div>
                
                <button type="submit" class="btn-pay">
                    <i class="fas fa-lock"></i> Confirmar y Pagar
                </button>
                
                <div class="security-notice">
                    <i class="fas fa-shield-alt"></i> Tus datos están protegidos con encriptación SSL
                </div>
            </form>
        </div>
        
        <div class="order-summary">
            <h2 class="section-title">Resumen del Pedido</h2>
            <div class="plan-badge"><?php echo $plan['nombre']; ?></div>
            
            <div class="price-breakdown">
                <div class="price-item">
                    <span>Plan <?php echo $plan['nombre']; ?> (6 meses)</span>
                    <span>$<?php echo number_format($plan['precio_normal'], 2); ?> CUP</span>
                </div>
                <div class="price-item">
                    <span>Descuento 15% Cuba</span>
                    <span style="color: var(--success);">-$<?php echo number_format($plan['ahorro'], 2); ?> CUP</span>
                </div>
                <div class="price-total">
                    <span>Total a Pagar</span>
                    <span>$<?php echo number_format($plan['precio_descuento'], 2); ?> CUP</span>
                </div>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <h4 style="margin-bottom: 10px; color: var(--primary);">¿Qué incluye?</h4>
                <ul style="list-style: none; padding-left: 0;">
                    <?php foreach(array_slice($plan['caracteristicas'], 0, 4) as $feature): ?>
                    <li style="padding: 5px 0; font-size: 0.9rem;">✓ <?php echo $feature; ?></li>
                    <?php endforeach; ?>
                    <li style="padding: 5px 0; font-size: 0.9rem; color: var(--secondary);">+ <?php echo count($plan['caracteristicas']) - 4; ?> características más</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function selectPayment(method) {
            document.getElementById('metodo_pago').value = method;
            
            // Remover selección anterior
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Agregar selección actual
            event.currentTarget.classList.add('selected');
        }
        
        // Validación del formulario
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const paymentMethod = document.getElementById('metodo_pago').value;
            if (!paymentMethod) {
                e.preventDefault();
                alert('Por favor, selecciona un método de pago');
                return false;
            }
        });
    </script>
</body>
</html>