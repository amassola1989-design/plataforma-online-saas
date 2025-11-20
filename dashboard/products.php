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

// Obtener información del usuario y límites
$user_query = "SELECT plan, max_productos FROM usuarios WHERE id = ?";
$user_stmt = $db->prepare($user_query);
$user_stmt->execute([$_SESSION['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Obtener negocio del usuario
$business_query = "SELECT id FROM negocios WHERE usuario_id = ?";
$business_stmt = $db->prepare($business_query);
$business_stmt->execute([$_SESSION['user_id']]);
$business = $business_stmt->fetch(PDO::FETCH_ASSOC);

// Contar productos actuales
$count_query = "SELECT COUNT(*) as total FROM productos WHERE negocio_id = ?";
$count_stmt = $db->prepare($count_query);
$count_stmt->execute([$business['id']]);
$current_products = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener productos
$products_query = "SELECT * FROM productos WHERE negocio_id = ? ORDER BY fecha_creacion DESC";
$products_stmt = $db->prepare($products_query);
$products_stmt->execute([$business['id']]);
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario para agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_product') {
    if ($current_products >= $user['max_productos']) {
        $error = "Has alcanzado el límite de " . $user['max_productos'] . " productos en tu plan " . $user['plan'];
    } else {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        
        if (!empty($nombre) && $precio > 0) {
            $insert_query = "INSERT INTO productos (negocio_id, nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $db->prepare($insert_query);
            
            if ($insert_stmt->execute([$business['id'], $nombre, $descripcion, $precio, $stock])) {
                header("Location: products.php?success=Producto agregado correctamente");
                exit();
            } else {
                $error = "Error al agregar el producto";
            }
        } else {
            $error = "Nombre y precio son requeridos";
        }
    }
}

// Procesar eliminación de producto
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Verificar que el producto pertenezca al negocio del usuario
    $verify_query = "SELECT id FROM productos WHERE id = ? AND negocio_id = ?";
    $verify_stmt = $db->prepare($verify_query);
    $verify_stmt->execute([$product_id, $business['id']]);
    
    if ($verify_stmt->rowCount() > 0) {
        $delete_query = "DELETE FROM productos WHERE id = ?";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->execute([$product_id]);
        
        header("Location: products.php?success=Producto eliminado correctamente");
        exit();
    } else {
        $error = "Producto no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Massola Business</title>
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
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
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
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
        .btn-danger { background: var(--danger); }
        .btn-warning { background: var(--warning); }
        
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
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .product-limit {
            background: #e8f4fd;
            border-left: 4px solid var(--secondary);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
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
                <li><a href="products.php" class="active"><i class="fas fa-box"></i> Productos</a></li>
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
                    <h1>Gestión de Productos</h1>
                    <p>Administra los productos de tu negocio</p>
                </div>
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-name"><?php echo $_SESSION['user_name']; ?></div>
                        <div class="user-plan">Plan <?php echo ucfirst($user['plan']); ?></div>
                    </div>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a>
                </div>
            </div>
            
            <!-- Límite de productos -->
            <div class="product-limit">
                <strong><i class="fas fa-info-circle"></i> Límite de Productos:</strong>
                Has usado <?php echo $current_products; ?> de <?php echo $user['max_productos']; ?> productos disponibles en tu plan <?php echo $user['plan']; ?>.
                <?php if ($current_products >= $user['max_productos']): ?>
                <strong style="color: var(--danger);"> ¡Has alcanzado el límite!</strong>
                <?php endif; ?>
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
            
            <!-- Formulario para agregar producto -->
            <?php if ($current_products < $user['max_productos']): ?>
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-plus"></i> Agregar Nuevo Producto</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add_product">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label for="nombre">Nombre del Producto *</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio ($) *</label>
                                <input type="number" id="precio" name="precio" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock Disponible</label>
                            <input type="number" id="stock" name="stock" class="form-control" min="0" value="0">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Producto
                        </button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Límite alcanzado:</strong> Has llegado al máximo de <?php echo $user['max_productos']; ?> productos en tu plan actual. 
                <a href="settings.php" style="color: var(--warning); font-weight: bold;">Actualiza tu plan</a> para agregar más productos.
            </div>
            <?php endif; ?>
            
            <!-- Lista de productos -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> Mis Productos (<?php echo count($products); ?>)</h2>
                </div>
                <div class="card-body">
                    <?php if (count($products) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($product['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($product['descripcion']); ?></td>
                                <td>$<?php echo number_format($product['precio'], 2); ?></td>
                                <td><?php echo $product['stock']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($product['fecha_creacion'])); ?></td>
                                <td>
                                    <a href="products.php?action=delete&id=<?php echo $product['id']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 20px;">
                        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 10px; display: block; color: #ddd;"></i>
                        No hay productos registrados
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>