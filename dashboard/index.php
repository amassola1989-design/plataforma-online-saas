<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';

// Verificar autenticación usando función de seguridad
verificarAutenticacion();

$database = new Database();
$db = $database->getConnection();

// Obtener información del usuario
$user_query = "SELECT nombre, email, plan, max_productos FROM usuarios WHERE id = ?";
$user_stmt = $db->prepare($user_query);
$user_stmt->execute([$_SESSION['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Obtener información del negocio
$business_query = "SELECT id, nombre_negocio, descripcion FROM negocios WHERE usuario_id = ?";
$business_stmt = $db->prepare($business_query);
$business_stmt->execute([$_SESSION['user_id']]);
$business = $business_stmt->fetch(PDO::FETCH_ASSOC);

// Obtener estadísticas usando función global
$estadisticas = obtenerEstadisticasNegocio($business['id'], $db);

// Contar productos (para compatibilidad)
$products_count = $estadisticas['total_productos'];

// Contar pedidos (para compatibilidad) 
$orders_count = $estadisticas['total_pedidos'];

// Obtener pedidos recientes
$recent_orders_query = "SELECT * FROM pedidos WHERE negocio_id = ? ORDER BY fecha_pedido DESC LIMIT 5";
$recent_orders_stmt = $db->prepare($recent_orders_query);
$recent_orders_stmt->execute([$business['id']]);
$recent_orders = $recent_orders_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Massola Business</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        
        /* Sidebar */
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
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        
        .icon-primary { background: rgba(52, 152, 219, 0.1); color: var(--secondary); }
        .icon-success { background: rgba(46, 204, 113, 0.1); color: var(--success); }
        .icon-warning { background: rgba(243, 156, 18, 0.1); color: var(--warning); }
        .icon-danger { background: rgba(231, 76, 60, 0.1); color: var(--danger); }
        
        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .stat-info p {
            color: #666;
            font-size: 14px;
        }
        
        /* Recent Orders */
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
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--primary);
        }
        
        .btn-success { background: var(--success); }
        .btn-warning { background: var(--warning); }
        
        /* Mensajes Flash */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #d4edda;
            border-color: var(--success);
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            border-color: var(--danger);
            color: #721c24;
        }
        
        .alert-warning {
            background: #fff3cd;
            border-color: var(--warning);
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
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
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Productos</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
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
                    <h1>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?></h1>
                    <p>Gestiona tu negocio <?php echo htmlspecialchars($business['nombre_negocio']); ?> desde aquí</p>
                </div>
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($user['nombre']); ?></div>
                        <div class="user-plan">Plan <?php echo ucfirst($user['plan']); ?></div>
                    </div>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a>
                </div>
            </div>
            
            <!-- Mensajes Flash -->
            <?php mostrarMensajeFlash(); ?>
            
            <!-- Stats Grid - MEJORADO CON INGRESOS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon icon-primary">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $products_count; ?></h3>
                        <p>Productos Activos</p>
                        <small>Límite: <?php echo $user['max_productos']; ?> productos</small>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $orders_count; ?></h3>
                        <p>Total Pedidos</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo formatearMoneda($estadisticas['ingresos_totales']); ?></h3>
                        <p>Ingresos Totales</p>
                        <small>Pedidos completados</small>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo ($user['max_productos'] - $products_count); ?></h3>
                        <p>Productos Disponibles</p>
                        <small>Puedes agregar <?php echo ($user['max_productos'] - $products_count); ?> productos más</small>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-clock"></i> Pedidos Recientes</h2>
                    <a href="orders.php" class="btn">Ver Todos</a>
                </div>
                <div class="card-body">
                    <?php if (count($recent_orders) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['cliente_nombre']); ?></td>
                                <td><?php echo formatearMoneda($order['total']); ?></td>
                                <td>
                                    <span class="status status-<?php echo $order['estado']; ?>">
                                        <?php echo ucfirst($order['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['fecha_pedido'])); ?></td>
                                <td>
                                    <a href="orders.php?action=view&id=<?php echo $order['id']; ?>" class="btn">Ver</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 20px;">
                        <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 10px; display: block; color: #ddd;"></i>
                        No hay pedidos recientes
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-bolt"></i> Acciones Rápidas</h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <a href="products.php?action=new" class="btn btn-success">
                            <i class="fas fa-plus"></i> Agregar Producto
                        </a>
                        <a href="business.php" class="btn">
                            <i class="fas fa-store"></i> Configurar Negocio
                        </a>
                        <a href="orders.php" class="btn">
                            <i class="fas fa-eye"></i> Ver Todos los Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>