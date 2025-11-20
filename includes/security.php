<?php
// Funciones de seguridad para Massola Business Platform

/**
 * Verificar si el usuario está autenticado
 */
function verificarAutenticacion() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../signup/login.php");
        exit();
    }
}

/**
 * Verificar que el usuario tiene acceso al negocio
 */
function verificarAccesoNegocio($negocio_id, $database) {
    $query = "SELECT id FROM negocios WHERE id = ? AND usuario_id = ?";
    $stmt = $database->prepare($query);
    $stmt->execute([$negocio_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() === 0) {
        header("Location: ../dashboard/");
        exit();
    }
}

/**
 * Prevenir ataques XSS
 */
function prevenirXSS($data) {
    if (is_array($data)) {
        return array_map('prevenirXSS', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar número de teléfono (formato básico)
 */
function validarTelefono($telefono) {
    return preg_match('/^[0-9\-\+\s\(\)]{10,20}$/', $telefono);
}

/**
 * Generar token CSRF
 */
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>