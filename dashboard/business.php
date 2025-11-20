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

// Obtener información del negocio
$business_query = "SELECT * FROM negocios WHERE usuario_id = ?";
$business_stmt = $db->prepare($business_query);
$business_stmt->execute([$_SESSION['user_id']]);
$business = $business_stmt->fetch(PDO::FETCH_ASSOC);

// Procesar actualización del negocio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_business') {
    $nombre_negocio = trim($_POST['nombre_negocio'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email_contacto = trim($_POST['email_contacto'] ?? '');

    if (!empty($nombre_negocio)) {
        $update_query = "UPDATE negocios SET nombre_negocio = ?, descripcion = ?, direccion = ?, telefono = ?, email_contacto = ? WHERE usuario_id = ?";
        $update_stmt = $db->prepare($update_query);
        
        if ($update_stmt->execute([$nombre_negocio, $descripcion, $direccion, $telefono, $email_contacto, $_SESSION['user_id']])) {
            header("Location: business.php?success=Información del negocio actualizada");
            exit();
        } else {
            $error = "Error al actualizar la información del negocio";
        }
    } else {
        $error = "El nombre del negocio es requerido";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Negocio - Massola Business</title>
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
            padding: 12px 25px;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--primary);
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
        
        .business-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: var(--primary);
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
                <li><a href="products.php"><i class="fas fa-box"></i> Productos</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="business.php" class="active"><i class="fas fa-store"></i> Mi Negocio</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Configuración</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="welcome">
                    <h1>Mi Negocio</h1>
                    <p>Configura la información de tu negocio</p>
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
            
            <!-- Información actual del negocio -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-info-circle"></i> Información Actual del Negocio</h2>
                </div>
                <div class="card-body">
                    <div class="business-info">
                        <div class="info-item">
                            <span class="info-label">Nombre del Negocio:</span>
                            <span><?php echo htmlspecialchars($business['nombre_negocio']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Descripción:</span>
                            <span><?php echo htmlspecialchars($business['descripcion']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dirección:</span>
                            <span><?php echo htmlspecialchars($business['direccion']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Teléfono:</span>
                            <span><?php echo htmlspecialchars($business['telefono']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email de Contacto:</span>
                            <span><?php echo htmlspecialchars($business['email_contacto']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha de Creación:</span>
                            <span><?php echo date('d/m/Y', strtotime($business['fecha_creacion'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Formulario de actualización -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-edit"></i> Actualizar Información</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="update_business">
                        <div class="form-group">
                            <label for="nombre_negocio">Nombre del Negocio *</label>
                            <input type="text" id="nombre_negocio" name="nombre_negocio" class="form-control" 
                                   value="<?php echo htmlspecialchars($business['nombre_negocio']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($business['descripcion']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <textarea id="direccion" name="direccion" class="form-control" rows="2"><?php echo htmlspecialchars($business['direccion']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" 
                                   value="<?php echo htmlspecialchars($business['telefono']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email_contacto">Email de Contacto</label>
                            <input type="email" id="email_contacto" name="email_contacto" class="form-control" 
                                   value="<?php echo htmlspecialchars($business['email_contacto']); ?>">
                        </div>
                        <button type="submit" class="btn">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>