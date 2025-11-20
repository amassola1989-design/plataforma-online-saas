<?php
// signup/payment-instructions.php - Instrucciones para pagos manuales (Transfermóvil)
session_start();

// Verificar si hay instrucciones en sesión
if (!isset($_SESSION['payment_instructions']) || $_SESSION['payment_instructions']['metodo'] !== 'transfermovil') {
    header("Location: checkout.php");
    exit();
}

$instructions = $_SESSION['payment_instructions'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instrucciones de Pago - Massola Business</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Instrucciones de Pago con Transfermóvil</h1>
        <div class="instructions-box" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p><strong>Por favor, sigue estos pasos para completar tu pago:</strong></p>
            <ol>
                <li>Abre la aplicación Transfermóvil en tu dispositivo.</li>
                <li>Realiza una transferencia al siguiente número de cuenta: <strong><?php echo htmlspecialchars($instructions['bank_account']); ?></strong></li>
                <li>Indica el monto exacto: <strong><?php echo htmlspecialchars($instructions['amount']); ?> CUP</strong></li>
                <li>Usa este código de referencia: <strong><?php echo htmlspecialchars($instructions['reference']); ?></strong></li>
                <li>Guarda el comprobante de la transferencia.</li>
                <li>Envía el comprobante a <a href="mailto:soporte@massolagroup.com">soporte@massolagroup.com</a>.</li>
            </ol>
            <p>Tu suscripción se activará una vez que verifiquemos el pago. ¡Gracias por elegir Massola Business!</p>
        </div>
        <a href="../dashboard/index.php" class="btn" style="background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Volver al Dashboard</a>
        <a href="checkout.php" class="btn" style="background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancelar</a>
    </div>
</body>
</html>