<?php
// signup/payment-process.php - Procesamiento de pago (actualizado con Enzona y Transfermovil)
session_start();

// Incluir configuración de planes
include 'plans-config.php';

// Verificar que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: plans.php");
    exit();
}

// Recibir datos del formulario
$plan_id = $_POST['plan_id'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$metodo_pago = $_POST['metodo_pago'] ?? '';

// Validaciones básicas
$errors = [];

if (!isset($PLANES_CONFIG[$plan_id])) {
    $errors[] = "Plan no válido";
}

if (empty($nombre)) $errors[] = "El nombre es requerido";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email no válido";
if (empty($telefono)) $errors[] = "El teléfono es requerido";
if (empty($ciudad)) $errors[] = "La ciudad es requerida";
if (empty($direccion)) $errors[] = "La dirección es requerida";
if (empty($metodo_pago)) $errors[] = "Selecciona un método de pago";

// Si hay errores, volver al checkout
if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['checkout_data'] = $_POST;
    header("Location: checkout.php?plan=" . $plan_id);
    exit();
}

// Crear carpetas si no existen
$usuarios_dir = 'usuarios';
$suscripciones_dir = 'suscripciones';

if (!is_dir($usuarios_dir)) {
    mkdir($usuarios_dir, 0755, true);
}

if (!is_dir($suscripciones_dir)) {
    mkdir($suscripciones_dir, 0755, true);
}

// Guardar datos del usuario
$usuario_data = [
    'nombre' => $nombre,
    'email' => $email,
    'telefono' => $telefono,
    'ciudad' => $ciudad,
    'direccion' => $direccion,
    'fecha_registro' => date('Y-m-d H:i:s')
];

$usuario_file = $usuarios_dir . '/' . md5($email) . '.json';
file_put_contents($usuario_file, json_encode($usuario_data, JSON_PRETTY_PRINT));

// Preparar datos de suscripción
$plan = $PLANES_CONFIG[$plan_id];
$fecha_inicio = date('Y-m-d');
$fecha_vencimiento = date('Y-m-d', strtotime('+6 months'));
$transaction_id = uniqid('tx_'); // ID único para la transacción

$suscripcion_data = [
    'plan_id' => $plan_id,
    'plan_nombre' => $plan['nombre'],
    'usuario_email' => $email,
    'precio_normal' => $plan['precio_normal'],
    'precio_pagado' => $plan['precio_descuento'],
    'ahorro' => $plan['ahorro'],
    'metodo_pago' => $metodo_pago,
    'fecha_inicio' => $fecha_inicio,
    'fecha_vencimiento' => $fecha_vencimiento,
    'estado' => 'pendiente', // Cambia a 'activa' después de verificación
    'fecha_compra' => date('Y-m-d H:i:s'),
    'transaction_id' => $transaction_id
];

$suscripcion_file = $suscripciones_dir . '/' . md5($email) . '.json';
file_put_contents($suscripcion_file, json_encode($suscripcion_data, JSON_PRETTY_PRINT));

// Integración de pasarelas
switch ($metodo_pago) {
    case 'enzona':
        // Integración con Enzona (requiere Omnipay o API directa)
        // Instala Omnipay con Composer: composer require ynievespuntonetsurl/omnipay-enzona
        require_once 'vendor/autoload.php';
        
        $gateway = Omnipay::create('Enzona');
        $gateway->setApiKey('tu_api_key_enzona'); // Reemplaza con tu clave
        $gateway->setSecret('tu_secret_enzona'); // Reemplaza con tu secret
        $gateway->setTestMode(true); // Cambia a false en producción

        $response = $gateway->purchase([
            'amount' => $plan['precio_descuento'],
            'currency' => 'CUP',
            'transactionId' => $transaction_id,
            'description' => "Suscripción al plan {$plan['nombre']}",
            'returnUrl' => 'https://negocios.massolagroup.com/signup/payment-success.php?tx=' . $transaction_id,
            'cancelUrl' => 'https://negocios.massolagroup.com/signup/checkout.php?error=cancelled',
            'customer' => [
                'name' => $nombre,
                'email' => $email,
                'phone' => $telefono
            ]
        ])->send();

        if ($response->isRedirect()) {
            $response->redirect(); // Redirige al pago de Enzona
        } else {
            $errors[] = $response->getMessage();
            $_SESSION['checkout_errors'] = $errors;
            header("Location: checkout.php?plan=" . $plan_id);
            exit();
        }
        break;

    case 'transfermovil':
        // Transfermovil: Muestra instrucciones para transferencia manual
        // No tiene API web directa, así que generamos un código de referencia
        $reference_code = $transaction_id; // Código único para la transferencia
        $bank_account = '9224-9598-7909-5584'; // Reemplaza con tu cuenta bancaria para transferencias
        $amount = $plan['precio_descuento'];

        // Guardar estado pendiente
        $suscripcion_data['estado'] = 'pendiente_transferencia';
        file_put_contents($suscripcion_file, json_encode($suscripcion_data, JSON_PRETTY_PRINT));

        // Redirigir a una página con instrucciones (crea payment-instructions.php o muestra aquí)
        $_SESSION['payment_instructions'] = [
            'metodo' => 'transfermovil',
            'reference' => $reference_code,
            'amount' => $amount,
            'bank_account' => $bank_account,
            'instructions' => "Por favor, realiza la transferencia a través de la app Transfermóvil a la cuenta $bank_account por el monto de $amount CUP, usando el código de referencia $reference_code. Una vez realizada, envía el comprobante a soporte@massolagroup.com para activar tu suscripción."
        ];
        header("Location: payment-instructions.php"); // Crea este archivo con las instrucciones
        exit();
        break;

    default:
        $errors[] = "Método de pago no soportado.";
        $_SESSION['checkout_errors'] = $errors;
        header("Location: checkout.php?plan=" . $plan_id);
        exit();
}

// Guardar datos en sesión para la página de éxito (para casos no redirect)
$_SESSION['payment_success'] = [
    'nombre' => $nombre,
    'email' => $email,
    'plan_nombre' => $plan['nombre'],
    'precio_pagado' => $plan['precio_descuento'],
    'fecha_vencimiento' => $fecha_vencimiento,
    'metodo_pago' => $metodo_pago
];

// Redirigir a página de éxito (para casos que no redirijan)
header("Location: payment-success.php");
exit();
?>