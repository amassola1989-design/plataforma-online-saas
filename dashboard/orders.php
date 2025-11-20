<?php
session_start();
require_once '../config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signup/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Obtener negocio del usuario
$business_query = "SELECT id FROM negocios WHERE usuario_id = ?";
$business_stmt = $db->prepare($business_query);
$business_stmt->execute([$_SESSION['user_id']]);
$business = $business_stmt->fetch(PDO::FETCH_ASSOC);

// Obtener pedidos
$orders_query = "SELECT * FROM pedidos WHERE negocio_id = ? ORDER BY fecha_pedido DESC";
$orders_stmt = $db->prepare($orders_query);
$orders_stmt->execute([$business['id']]);
$orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar cambio de estado del pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $order_id = intval($_POST['order_id']);
    $nuevo_estado = $_POST['estado'];

    // Verificar que el pedido pertenezca al negocio del usuario
    $verify_query = "SELECT id FROM pedidos WHERE id = ? AND negocio_id = ?";
    $verify_stmt = $db->prepare($verify_query);
    $verify_stmt->execute([$order_id, $business['id']]);

    if ($verify_stmt->rowCount() > 0) {
        $update_query = "UPDATE pedidos SET estado = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_query);
        if ($update_stmt->execute([$nuevo_estado, $order_id])) {
            header("Location: orders.php?success=Estado del pedido actualizado");
            exit();
        } else {
            $error = "Error al actualizar el estado del pedido";
        }
    } else {
        $error = "Pedido no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Massola Business</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Mantenemos los mismos estilos del dashboard */
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar (mismo estilo que index.php) */
        .sidebar {
            width: 250px;
            background: var(--primary);
            color: white;
            padding: 20px 0;
        }
        
        .logo {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .logo h1 {
            font-size: 24px;
            color: white;
        }
        
        .logo span {
            color: var(--secondary);
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-links li {
            margin-bottom: 5px;
        }
        
        .nav-links a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--secondary);
        }
        
        .nav-links i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .welcome h1 {
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .welcome p {
            color: #666;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--primary);
        }
        
        .user-plan {
            font-size: 12px;
            color: var(--secondary);
            background: rgba(52, 152, 219, 0.1);
            padding: 2px 8px;
            border-radius: 10px;
        }
        
        .logout-btn {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        /* Cards y Forms */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .card-header h2 {
            color: var(--primary);
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--primary);
        }
        
        .btn-success { background: var(--success); }
        .btn-warning { background: var(--warning); }
        .btn-danger { background: var(--danger); }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pendiente { background: #fff3cd; color: #856404; }
        .status-confirmado { background: #d1ecf1; color: #0c5460; }
        .status-completado { background: #d4edda; color: #155724; }
        .status-cancelado { background: #f8d7da; color: #721c24; }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-control {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1>Massola<span>Business</span></h1>
            </div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Productos</a></li>
                <li><a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="business.php"><i class="fas fa-store"></i> Mi Negocio</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Configuración</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="welcome">
                    <h1>Gestión de Pedidos</h1>
                    <p>Administra los pedidos de tu negocio</p>
                </div>
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-name"><?php echo $_SESSION['user_name']; ?></div>
                        <div class="user-plan">Plan <?php echo ucfirst($user['plan']); ?></div>
                    </div>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a>
                </div>
            </div>
            
            <!-- Mensajes -->
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <!-- Lista de pedidos -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> Todos los Pedidos (<?php echo count($orders); ?>)</h2>
                </div>
                <div class="card-body">
                    <?php if (count($orders) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Contacto</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['cliente_nombre']); ?></td>
                                <td>
                                    <?php if (!empty($order['cliente_email'])): ?>
                                        <?php echo htmlspecialchars($order['cliente_email']); ?><br>
                                    <?php endif; ?>
                                    <?php if (!empty($order['cliente_telefono'])): ?>
                                        <?php echo htmlspecialchars($order['cliente_telefono']); ?>
                                    <?php endif; ?>
                                </td>
                                <td>$<?php echo number_format($order['total'], 2); ?></td>
                                <td>
                                    <span class="status status-<?php echo $order['estado']; ?>">
                                        <?php echo ucfirst($order['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['fecha_pedido'])); ?></td>
                                <td>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="estado" class="form-control" onchange="this.form.submit()">
                                            <option value="pendiente" <?php echo $order['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="confirmado" <?php echo $order['estado'] == 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                            <option value="completado" <?php echo $order['estado'] == 'completado' ? 'selected' : ''; ?>>Completado</option>
                                            <option value="cancelado" <?php echo $order['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 20px;">
                        <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 10px; display: block; color: #ddd;"></i>
                        No hay pedidos registrados
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>