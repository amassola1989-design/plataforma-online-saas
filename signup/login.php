<?php
// signup/login.php - SISTEMA COMPLETO CON BASE DE DATOS
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_email'])) {
    header("Location: ../dashboard/");
    exit();
}

// INCLUIR CONEXIÓN BD
require_once '../config/database.php';

// Procesar el formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validaciones básicas
    $errors = [];
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email no válido";
    }
    
    if (empty($password)) {
        $errors[] = "La contraseña es requerida";
    }
    
    // VERIFICAR EN BASE DE DATOS
    if (empty($errors)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT id, email, password, nombre FROM usuarios WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña hasheada
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nombre'];
                
                header("Location: ../dashboard/");
                exit();
            } else {
                $errors[] = "Contraseña incorrecta";
            }
        } else {
            $errors[] = "Usuario no encontrado";
        }
    }
    
    if (!empty($errors)) {
        $error_message = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Iniciar Sesión - Massola Business</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: var(--primary);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.8;
        }
        
        .form-container {
            padding: 30px;
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
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .checkbox-group input {
            margin-right: 10px;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        
        .btn:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .login-options {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .login-options a {
            color: var(--secondary);
            text-decoration: none;
            font-size: 14px;
        }
        
        .login-options a:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: var(--dark);
        }
        
        .register-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: var(--danger);
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success-message {
            color: var(--success);
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .login-options {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h1>
            <p>Accede a tu cuenta de Massola Business</p>
        </div>
        
        <div class="form-container">
            <?php if (isset($error_message)): ?>
            <div class="error-message">
                <strong>Error:</strong> <?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="success-message">
                <strong>¡Sesión cerrada!</strong> Has cerrado sesión correctamente.
            </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label for="email">Correo Electrónico *</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordar mi sesión</label>
                </div>
                
                <button type="submit" class="btn">Iniciar Sesión</button>
                
                <div class="login-options">
                    <a href="#" id="forgotPassword">¿Olvidaste tu contraseña?</a>
                    <a href="#" id="resendCode">Reenviar código de verificación</a>
                </div>
                
                <div class="register-link">
                    ¿No tienes una cuenta? <a href="index.php">Regístrate aquí</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Funcionalidad para "Olvidé mi contraseña"
        document.getElementById('forgotPassword').addEventListener('click', function(e) {
            e.preventDefault();
            const email = prompt('Por favor, introduce tu correo electrónico para restablecer tu contraseña:');
            if (email) {
                alert('Se ha enviado un enlace de restablecimiento a: ' + email);
            }
        });

        // Funcionalidad para "Reenviar código de verificación"
        document.getElementById('resendCode').addEventListener('click', function(e) {
            e.preventDefault();
            const email = prompt('Por favor, introduce tu correo electrónico para reenviar el código de verificación:');
            if (email) {
                alert('Se ha reenviado el código de verificación a: ' + email);
            }
        });
    </script>
</body>
</html>