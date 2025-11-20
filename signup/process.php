<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y limpiar los datos del formulario
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $countryCode = trim($_POST['countryCode'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $businessName = trim($_POST['businessName'] ?? '');
    $commercialName = trim($_POST['commercialName'] ?? '');
    $businessType = trim($_POST['businessType'] ?? '');
    $yearsOperation = trim($_POST['yearsOperation'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postalCode = trim($_POST['postalCode'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $captchaInput = trim($_POST['captchaInput'] ?? '');
    $terms = isset($_POST['terms']) ? true : false;
    $newsletter = isset($_POST['newsletter']) ? true : false;

    $errors = [];

    // Validaciones básicas
    if (empty($firstName)) $errors[] = "El nombre es requerido";
    if (empty($lastName)) $errors[] = "Los apellidos son requeridos";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email no válido";
    if (empty($phone)) $errors[] = "El teléfono es requerido";
    if (empty($businessName)) $errors[] = "El nombre legal del negocio es requerido";
    if (empty($commercialName)) $errors[] = "El nombre comercial es requerido";
    if (empty($businessType)) $errors[] = "El tipo de negocio es requerido";
    if (empty($country)) $errors[] = "El país es requerido";
    if (empty($region)) $errors[] = "La región es requerida";
    if (empty($city)) $errors[] = "La ciudad es requerida";
    if (empty($address)) $errors[] = "La dirección es requerida";
    if (empty($password)) $errors[] = "La contraseña es requerida";
    if ($password !== $confirmPassword) $errors[] = "Las contraseñas no coinciden";
    if (empty($captchaInput)) $errors[] = "El código de seguridad es requerido";
    if (!$terms) $errors[] = "Debes aceptar los términos y condiciones";

    // Validar CAPTCHA (aquí deberías comparar con el CAPTCHA generado en la sesión)
    // Por ahora, lo omitimos para pruebas, pero en producción debe ser validado.

    if (empty($errors)) {
        $database = new Database();
        $db = $database->getConnection();

        try {
            // Verificar si el email ya existe
            $check_query = "SELECT id FROM usuarios WHERE email = ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->execute([$email]);
            
            if ($check_stmt->rowCount() > 0) {
                $errors[] = "El email ya está registrado";
            } else {
                // Insertar usuario
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $user_query = "INSERT INTO usuarios (email, password, nombre, telefono, plan) VALUES (?, ?, ?, ?, 'basico')";
                $user_stmt = $db->prepare($user_query);
                
                $nombre_completo = $firstName . ' ' . $lastName;
                $telefono_completo = $countryCode . ' ' . $phone;
                
                if ($user_stmt->execute([$email, $hashed_password, $nombre_completo, $telefono_completo])) {
                    $user_id = $db->lastInsertId();
                    
                    // Crear negocio automáticamente
                    $business_query = "INSERT INTO negocios (usuario_id, nombre_negocio, descripcion, direccion, telefono, email_contacto) VALUES (?, ?, ?, ?, ?, ?)";
                    $business_stmt = $db->prepare($business_query);
                    
                    $descripcion_negocio = "Tipo: " . $businessType . (empty($yearsOperation) ? "" : " - Años de operación: " . $yearsOperation);
                    $direccion_completa = $address . ", " . $city . ", " . $region . ", " . $country . (empty($postalCode) ? "" : " CP: " . $postalCode);
                    
                    $business_stmt->execute([
                        $user_id, 
                        $commercialName, 
                        $descripcion_negocio,
                        $direccion_completa,
                        $telefono_completo,
                        $email
                    ]);
                    
                    $negocio_id = $db->lastInsertId();
                    
                    // Guardar datos en sesión para success.php
                    $_SESSION['registered_user'] = [
                        'name' => $nombre_completo,
                        'email' => $email,
                        'business' => $commercialName,
                        'business_type' => $businessType
                    ];
                    
                    // Iniciar sesión del usuario
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_name'] = $nombre_completo;
                    $_SESSION['negocio_id'] = $negocio_id;
                    
                    header("Location: success.php");
                    exit();
                } else {
                    $errors[] = "Error al crear el usuario";
                }
            }
        } catch(PDOException $e) {
            $errors[] = "Error en el registro: " . $e->getMessage();
        }
    }
    
    // Si hay errores, guardar en sesión y redirigir
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>