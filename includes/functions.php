<?php
// Funciones globales para Massola Business Platform

/**
 * Verificar límite de productos según plan
 */
function verificarLimiteProductos($user_id, $database) {
    $query = "SELECT u.plan, u.max_productos, COUNT(p.id) as productos_actuales 
              FROM usuarios u 
              LEFT JOIN negocios n ON u.id = n.usuario_id 
              LEFT JOIN productos p ON n.id = p.negocio_id 
              WHERE u.id = ? 
              GROUP BY u.id";
    
    $stmt = $database->prepare($query);
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return [
        'plan' => $result['plan'],
        'max_productos' => $result['max_productos'],
        'productos_actuales' => $result['productos_actuales'],
        'puede_agregar' => $result['productos_actuales'] < $result['max_productos']
    ];
}

/**
 * Obtener estadísticas del negocio
 */
function obtenerEstadisticasNegocio($negocio_id, $database) {
    $stats = [];
    
    // Total productos
    $query = "SELECT COUNT(*) as total FROM productos WHERE negocio_id = ?";
    $stmt = $database->prepare($query);
    $stmt->execute([$negocio_id]);
    $stats['total_productos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total pedidos
    $query = "SELECT COUNT(*) as total FROM pedidos WHERE negocio_id = ?";
    $stmt = $database->prepare($query);
    $stmt->execute([$negocio_id]);
    $stats['total_pedidos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Pedidos por estado
    $query = "SELECT estado, COUNT(*) as total FROM pedidos WHERE negocio_id = ? GROUP BY estado";
    $stmt = $database->prepare($query);
    $stmt->execute([$negocio_id]);
    $stats['pedidos_por_estado'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ingresos totales
    $query = "SELECT COALESCE(SUM(total), 0) as ingresos FROM pedidos WHERE negocio_id = ? AND estado = 'completado'";
    $stmt = $database->prepare($query);
    $stmt->execute([$negocio_id]);
    $stats['ingresos_totales'] = $stmt->fetch(PDO::FETCH_ASSOC)['ingresos'];
    
    return $stats;
}

/**
 * Validar y sanitizar datos de entrada
 */
function sanitizarInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Generar código único para pedidos
 */
function generarCodigoPedido($longitud = 8) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

/**
 * Formatear moneda
 */
function formatearMoneda($cantidad, $moneda = '$') {
    return $moneda . number_format($cantidad, 2);
}

/**
 * Redirigir con mensaje
 */
function redirigirConMensaje($url, $tipo, $mensaje) {
    $_SESSION['mensaje_flash'] = [
        'tipo' => $tipo,
        'mensaje' => $mensaje
    ];
    header("Location: $url");
    exit();
}

/**
 * Mostrar mensaje flash
 */
function mostrarMensajeFlash() {
    if (isset($_SESSION['mensaje_flash'])) {
        $mensaje = $_SESSION['mensaje_flash'];
        $clase = $mensaje['tipo'] == 'error' ? 'alert-danger' : 'alert-success';
        echo "<div class='alert $clase'><i class='fas fa-info-circle'></i> {$mensaje['mensaje']}</div>";
        unset($_SESSION['mensaje_flash']);
    }
}
?>