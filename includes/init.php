<?php
// Inicialización del sistema Massola Business Platform

// Configuración de zona horaria
date_default_timezone_set('America/Havana');

// Iniciar sesión segura
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configuración de seguridad básica
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// Incluir archivos necesarios
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/security.php';

// Manejo de errores (en desarrollo)
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Verificar instalación de tablas
function verificarInstalacion($database) {
    $tablas_requeridas = ['usuarios', 'negocios', 'productos', 'pedidos'];
    $db = $database->getConnection();
    
    foreach ($tablas_requeridas as $tabla) {
        if (!$database->tableExists($tabla)) {
            return false;
        }
    }
    return true;
}
?>