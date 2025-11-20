<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Internacional de Negocios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/6.6.6/css/flag-icons.min.css">
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
            max-width: 1000px;
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
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .section-title {
            font-size: 20px;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary);
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: var(--secondary);
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .form-group {
            flex: 1 0 300px;
            padding: 0 10px;
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
        
        .phone-group {
            display: flex;
        }
        
        .country-code {
            flex: 0 0 120px;
            margin-right: 10px;
        }
        
        .phone-number {
            flex: 1;
        }
        
        .country-select {
            position: relative;
        }
        
        .country-select select {
            padding-left: 40px;
        }
        
        .country-flag {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .checkbox-group input {
            margin-right: 10px;
        }
        
        .captcha-container {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .captcha-text {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding: 5px 10px;
            border-radius: 5px;
        }
        
        .captcha-refresh {
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .captcha-refresh:hover {
            background: var(--primary);
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--dark);
        }
        
        .login-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .success-message {
            color: var(--success);
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;
        }
        
        @media (max-width: 768px) {
            .form-group {
                flex: 1 0 100%;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-globe-americas"></i> Registro Internacional de Negocios</h1>
            <p>Únete a nuestra plataforma global de servicios y comercio</p>
        </div>
        
        <div class="form-container">
            <!-- FORMULARIO MODIFICADO - AHORA APUNTA A process.php -->
            <form id="registrationForm" method="POST" action="process.php">
                <!-- Información del Propietario -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user-tie"></i> Información del Propietario</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Nombre(s) *</label>
                            <input type="text" id="firstName" name="firstName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Apellidos *</label>
                            <input type="text" id="lastName" name="lastName" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Correo Electrónico *</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono *</label>
                            <div class="phone-group">
                                <div class="form-group country-code">
                                    <div class="country-select">
                                        <span class="country-flag fi fi-us" id="flagIcon"></span>
                                        <select id="countryCode" name="countryCode" class="form-control">
                                            <!-- Los códigos de país se cargarán con JavaScript -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group phone-number">
                                    <input type="tel" id="phone" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información del Negocio -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-building"></i> Información del Negocio</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="businessName">Nombre Legal del Negocio *</label>
                            <input type="text" id="businessName" name="businessName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="commercialName">Nombre Comercial *</label>
                            <input type="text" id="commercialName" name="commercialName" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="businessType">Tipo de Negocio *</label>
                            <select id="businessType" name="businessType" class="form-control" required>
                                <option value="">Seleccione un tipo de negocio</option>
                                <optgroup label="Servicios Automotrices">
                                    <option value="taller-mecanica">Taller de Mecánica</option>
                                    <option value="venta-piezas">Venta de Piezas de Auto</option>
                                    <option value="venta-autos">Venta de Autos</option>
                                    <option value="lubricentro">Lubricentro</option>
                                    <option value="chapisteria">Chapistería y Pintura</option>
                                    <option value="electrico-autos">Servicios Eléctricos para Autos</option>
                                </optgroup>
                                <optgroup label="Tecnología e Informática">
                                    <option value="taller-informatico">Taller Informático</option>
                                    <option value="servicios-informaticos">Servicios Informáticos</option>
                                    <option value="telecomunicaciones">Servicios de Telecomunicaciones</option>
                                    <option value="programadores">Programadores/Desarrolladores</option>
                                    <option value="freelancer">Trabajadores Freelancer</option>
                                    <option value="soporte-tecnico">Soporte Técnico</option>
                                    <option value="desarrollo-web">Desarrollo Web</option>
                                    <option value="aplicaciones-moviles">Aplicaciones Móviles</option>
                                </optgroup>
                                <!-- Más opciones... -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="yearsOperation">Años de Operación</label>
                            <input type="number" id="yearsOperation" name="yearsOperation" class="form-control" min="0" max="100">
                        </div>
                    </div>
                </div>
                
                <!-- Ubicación -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Ubicación</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="country">País *</label>
                            <select id="country" name="country" class="form-control" required>
                                <!-- Los países se cargarán con JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="region">Región/Estado/Provincia *</label>
                            <input type="text" id="region" name="region" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">Ciudad *</label>
                            <input type="text" id="city" name="city" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="postalCode">Código Postal</label>
                            <input type="text" id="postalCode" name="postalCode" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address">Dirección Completa *</label>
                            <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Seguridad -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-shield-alt"></i> Seguridad</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <div class="error-message" id="passwordError">La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial.</div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Repetir Contraseña *</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                            <div class="error-message" id="confirmPasswordError">Las contraseñas no coinciden.</div>
                        </div>
                    </div>
                    
                    <div class="captcha-container">
                        <div class="captcha-text" id="captchaText">A1B2C3</div>
                        <button type="button" class="captcha-refresh" id="refreshCaptcha">
                            <i class="fas fa-redo"></i> Actualizar
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="captchaInput">Ingrese el código de seguridad mostrado arriba *</label>
                        <input type="text" id="captchaInput" name="captchaInput" class="form-control" required>
                        <div class="error-message" id="captchaError">El código de seguridad no coincide.</div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Acepto las <a href="/condiciones-uso/index.php">Condiciones de Uso</a> y la <a href="/privacidad/index.php">Política de Privacidad</a> *</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="newsletter" name="newsletter">
                        <label for="newsletter">Deseo recibir información sobre novedades y ofertas</label>
                    </div>
                </div>
                
                <button type="submit" class="btn">Registrar Negocio</button>
                
                <div class="login-link">
                    ¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Datos de países con códigos telefónicos y banderas
        const countries = [
            { name: "Afganistán", code: "AF", phoneCode: "+93", flag: "af" },
            { name: "Albania", code: "AL", phoneCode: "+355", flag: "al" },
            { name: "Alemania", code: "DE", phoneCode: "+49", flag: "de" },
            { name: "Andorra", code: "AD", phoneCode: "+376", flag: "ad" },
            { name: "Angola", code: "AO", phoneCode: "+244", flag: "ao" },
            { name: "Antigua y Barbuda", code: "AG", phoneCode: "+1-268", flag: "ag" },
            { name: "Arabia Saudita", code: "SA", phoneCode: "+966", flag: "sa" },
            { name: "Argelia", code: "DZ", phoneCode: "+213", flag: "dz" },
            { name: "Argentina", code: "AR", phoneCode: "+54", flag: "ar" },
            { name: "Armenia", code: "AM", phoneCode: "+374", flag: "am" },
            { name: "Australia", code: "AU", phoneCode: "+61", flag: "au" },
            { name: "Austria", code: "AT", phoneCode: "+43", flag: "at" },
            { name: "Azerbaiyán", code: "AZ", phoneCode: "+994", flag: "az" },
            { name: "Bahamas", code: "BS", phoneCode: "+1-242", flag: "bs" },
            { name: "Bangladés", code: "BD", phoneCode: "+880", flag: "bd" },
            { name: "Barbados", code: "BB", phoneCode: "+1-246", flag: "bb" },
            { name: "Baréin", code: "BH", phoneCode: "+973", flag: "bh" },
            { name: "Bélgica", code: "BE", phoneCode: "+32", flag: "be" },
            { name: "Belice", code: "BZ", phoneCode: "+501", flag: "bz" },
            { name: "Benín", code: "BJ", phoneCode: "+229", flag: "bj" },
            { name: "Bielorrusia", code: "BY", phoneCode: "+375", flag: "by" },
            { name: "Birmania", code: "MM", phoneCode: "+95", flag: "mm" },
            { name: "Bolivia", code: "BO", phoneCode: "+591", flag: "bo" },
            { name: "Bosnia y Herzegovina", code: "BA", phoneCode: "+387", flag: "ba" },
            { name: "Botsuana", code: "BW", phoneCode: "+267", flag: "bw" },
            { name: "Brasil", code: "BR", phoneCode: "+55", flag: "br" },
            { name: "Brunéi", code: "BN", phoneCode: "+673", flag: "bn" },
            { name: "Bulgaria", code: "BG", phoneCode: "+359", flag: "bg" },
            { name: "Burkina Faso", code: "BF", phoneCode: "+226", flag: "bf" },
            { name: "Burundi", code: "BI", phoneCode: "+257", flag: "bi" },
            { name: "Bután", code: "BT", phoneCode: "+975", flag: "bt" },
            { name: "Cabo Verde", code: "CV", phoneCode: "+238", flag: "cv" },
            { name: "Camboya", code: "KH", phoneCode: "+855", flag: "kh" },
            { name: "Camerún", code: "CM", phoneCode: "+237", flag: "cm" },
            { name: "Canadá", code: "CA", phoneCode: "+1", flag: "ca" },
            { name: "Catar", code: "QA", phoneCode: "+974", flag: "qa" },
            { name: "Chad", code: "TD", phoneCode: "+235", flag: "td" },
            { name: "Chile", code: "CL", phoneCode: "+56", flag: "cl" },
            { name: "China", code: "CN", phoneCode: "+86", flag: "cn" },
            { name: "Chipre", code: "CY", phoneCode: "+357", flag: "cy" },
            { name: "Ciudad del Vaticano", code: "VA", phoneCode: "+379", flag: "va" },
            { name: "Colombia", code: "CO", phoneCode: "+57", flag: "co" },
            { name: "Comoras", code: "KM", phoneCode: "+269", flag: "km" },
            { name: "Congo", code: "CG", phoneCode: "+242", flag: "cg" },
            { name: "Corea del Norte", code: "KP", phoneCode: "+850", flag: "kp" },
            { name: "Corea del Sur", code: "KR", phoneCode: "+82", flag: "kr" },
            { name: "Costa de Marfil", code: "CI", phoneCode: "+225", flag: "ci" },
            { name: "Costa Rica", code: "CR", phoneCode: "+506", flag: "cr" },
            { name: "Croacia", code: "HR", phoneCode: "+385", flag: "hr" },
            { name: "Cuba", code: "CU", phoneCode: "+53", flag: "cu" },
            { name: "Dinamarca", code: "DK", phoneCode: "+45", flag: "dk" },
            { name: "Dominica", code: "DM", phoneCode: "+1-767", flag: "dm" },
            { name: "Ecuador", code: "EC", phoneCode: "+593", flag: "ec" },
            { name: "Egipto", code: "EG", phoneCode: "+20", flag: "eg" },
            { name: "El Salvador", code: "SV", phoneCode: "+503", flag: "sv" },
            { name: "Emiratos Árabes Unidos", code: "AE", phoneCode: "+971", flag: "ae" },
            { name: "Eritrea", code: "ER", phoneCode: "+291", flag: "er" },
            { name: "Eslovaquia", code: "SK", phoneCode: "+421", flag: "sk" },
            { name: "Eslovenia", code: "SI", phoneCode: "+386", flag: "si" },
            { name: "España", code: "ES", phoneCode: "+34", flag: "es" },
            { name: "Estados Unidos", code: "US", phoneCode: "+1", flag: "us" },
            { name: "Estonia", code: "EE", phoneCode: "+372", flag: "ee" },
            { name: "Etiopía", code: "ET", phoneCode: "+251", flag: "et" },
            { name: "Filipinas", code: "PH", phoneCode: "+63", flag: "ph" },
            { name: "Finlandia", code: "FI", phoneCode: "+358", flag: "fi" },
            { name: "Fiyi", code: "FJ", phoneCode: "+679", flag: "fj" },
            { name: "Francia", code: "FR", phoneCode: "+33", flag: "fr" },
            { name: "Gabón", code: "GA", phoneCode: "+241", flag: "ga" },
            { name: "Gambia", code: "GM", phoneCode: "+220", flag: "gm" },
            { name: "Georgia", code: "GE", phoneCode: "+995", flag: "ge" },
            { name: "Ghana", code: "GH", phoneCode: "+233", flag: "gh" },
            { name: "Granada", code: "GD", phoneCode: "+1-473", flag: "gd" },
            { name: "Grecia", code: "GR", phoneCode: "+30", flag: "gr" },
            { name: "Guatemala", code: "GT", phoneCode: "+502", flag: "gt" },
            { name: "Guinea", code: "GN", phoneCode: "+224", flag: "gn" },
            { name: "Guinea-Bisáu", code: "GW", phoneCode: "+245", flag: "gw" },
            { name: "Guinea Ecuatorial", code: "GQ", phoneCode: "+240", flag: "gq" },
            { name: "Guyana", code: "GY", phoneCode: "+592", flag: "gy" },
            { name: "Haití", code: "HT", phoneCode: "+509", flag: "ht" },
            { name: "Honduras", code: "HN", phoneCode: "+504", flag: "hn" },
            { name: "Hungría", code: "HU", phoneCode: "+36", flag: "hu" },
            { name: "India", code: "IN", phoneCode: "+91", flag: "in" },
            { name: "Indonesia", code: "ID", phoneCode: "+62", flag: "id" },
            { name: "Irak", code: "IQ", phoneCode: "+964", flag: "iq" },
            { name: "Irán", code: "IR", phoneCode: "+98", flag: "ir" },
            { name: "Irlanda", code: "IE", phoneCode: "+353", flag: "ie" },
            { name: "Islandia", code: "IS", phoneCode: "+354", flag: "is" },
            { name: "Islas Marshall", code: "MH", phoneCode: "+692", flag: "mh" },
            { name: "Islas Salomón", code: "SB", phoneCode: "+677", flag: "sb" },
            { name: "Israel", code: "IL", phoneCode: "+972", flag: "il" },
            { name: "Italia", code: "IT", phoneCode: "+39", flag: "it" },
            { name: "Jamaica", code: "JM", phoneCode: "+1-876", flag: "jm" },
            { name: "Japón", code: "JP", phoneCode: "+81", flag: "jp" },
            { name: "Jordania", code: "JO", phoneCode: "+962", flag: "jo" },
            { name: "Kazajistán", code: "KZ", phoneCode: "+7", flag: "kz" },
            { name: "Kenia", code: "KE", phoneCode: "+254", flag: "ke" },
            { name: "Kirguistán", code: "KG", phoneCode: "+996", flag: "kg" },
            { name: "Kiribati", code: "KI", phoneCode: "+686", flag: "ki" },
            { name: "Kuwait", code: "KW", phoneCode: "+965", flag: "kw" },
            { name: "Laos", code: "LA", phoneCode: "+856", flag: "la" },
            { name: "Lesoto", code: "LS", phoneCode: "+266", flag: "ls" },
            { name: "Letonia", code: "LV", phoneCode: "+371", flag: "lv" },
            { name: "Líbano", code: "LB", phoneCode: "+961", flag: "lb" },
            { name: "Liberia", code: "LR", phoneCode: "+231", flag: "lr" },
            { name: "Libia", code: "LY", phoneCode: "+218", flag: "ly" },
            { name: "Liechtenstein", code: "LI", phoneCode: "+423", flag: "li" },
            { name: "Lituania", code: "LT", phoneCode: "+370", flag: "lt" },
            { name: "Luxemburgo", code: "LU", phoneCode: "+352", flag: "lu" },
            { name: "Macedonia del Norte", code: "MK", phoneCode: "+389", flag: "mk" },
            { name: "Madagascar", code: "MG", phoneCode: "+261", flag: "mg" },
            { name: "Malasia", code: "MY", phoneCode: "+60", flag: "my" },
            { name: "Malaui", code: "MW", phoneCode: "+265", flag: "mw" },
            { name: "Maldivas", code: "MV", phoneCode: "+960", flag: "mv" },
            { name: "Malí", code: "ML", phoneCode: "+223", flag: "ml" },
            { name: "Malta", code: "MT", phoneCode: "+356", flag: "mt" },
            { name: "Marruecos", code: "MA", phoneCode: "+212", flag: "ma" },
            { name: "Mauricio", code: "MU", phoneCode: "+230", flag: "mu" },
            { name: "Mauritania", code: "MR", phoneCode: "+222", flag: "mr" },
            { name: "México", code: "MX", phoneCode: "+52", flag: "mx" },
            { name: "Micronesia", code: "FM", phoneCode: "+691", flag: "fm" },
            { name: "Moldavia", code: "MD", phoneCode: "+373", flag: "md" },
            { name: "Mónaco", code: "MC", phoneCode: "+377", flag: "mc" },
            { name: "Mongolia", code: "MN", phoneCode: "+976", flag: "mn" },
            { name: "Montenegro", code: "ME", phoneCode: "+382", flag: "me" },
            { name: "Mozambique", code: "MZ", phoneCode: "+258", flag: "mz" },
            { name: "Namibia", code: "NA", phoneCode: "+264", flag: "na" },
            { name: "Nauru", code: "NR", phoneCode: "+674", flag: "nr" },
            { name: "Nepal", code: "NP", phoneCode: "+977", flag: "np" },
            { name: "Nicaragua", code: "NI", phoneCode: "+505", flag: "ni" },
            { name: "Níger", code: "NE", phoneCode: "+227", flag: "ne" },
            { name: "Nigeria", code: "NG", phoneCode: "+234", flag: "ng" },
            { name: "Noruega", code: "NO", phoneCode: "+47", flag: "no" },
            { name: "Nueva Zelanda", code: "NZ", phoneCode: "+64", flag: "nz" },
            { name: "Omán", code: "OM", phoneCode: "+968", flag: "om" },
            { name: "Países Bajos", code: "NL", phoneCode: "+31", flag: "nl" },
            { name: "Pakistán", code: "PK", phoneCode: "+92", flag: "pk" },
            { name: "Palaos", code: "PW", phoneCode: "+680", flag: "pw" },
            { name: "Palestina", code: "PS", phoneCode: "+970", flag: "ps" },
            { name: "Panamá", code: "PA", phoneCode: "+507", flag: "pa" },
            { name: "Papúa Nueva Guinea", code: "PG", phoneCode: "+675", flag: "pg" },
            { name: "Paraguay", code: "PY", phoneCode: "+595", flag: "py" },
            { name: "Perú", code: "PE", phoneCode: "+51", flag: "pe" },
            { name: "Polonia", code: "PL", phoneCode: "+48", flag: "pl" },
            { name: "Portugal", code: "PT", phoneCode: "+351", flag: "pt" },
            { name: "Reino Unido", code: "GB", phoneCode: "+44", flag: "gb" },
            { name: "República Centroafricana", code: "CF", phoneCode: "+236", flag: "cf" },
            { name: "República Checa", code: "CZ", phoneCode: "+420", flag: "cz" },
            { name: "República del Congo", code: "CD", phoneCode: "+243", flag: "cd" },
            { name: "República Dominicana", code: "DO", phoneCode: "+1-809", flag: "do" },
            { name: "Ruanda", code: "RW", phoneCode: "+250", flag: "rw" },
            { name: "Rumania", code: "RO", phoneCode: "+40", flag: "ro" },
            { name: "Rusia", code: "RU", phoneCode: "+7", flag: "ru" },
            { name: "Samoa", code: "WS", phoneCode: "+685", flag: "ws" },
            { name: "San Cristóbal y Nieves", code: "KN", phoneCode: "+1-869", flag: "kn" },
            { name: "San Marino", code: "SM", phoneCode: "+378", flag: "sm" },
            { name: "San Vicente y las Granadinas", code: "VC", phoneCode: "+1-784", flag: "vc" },
            { name: "Santa Lucía", code: "LC", phoneCode: "+1-758", flag: "lc" },
            { name: "Santo Tomé y Príncipe", code: "ST", phoneCode: "+239", flag: "st" },
            { name: "Senegal", code: "SN", phoneCode: "+221", flag: "sn" },
            { name: "Serbia", code: "RS", phoneCode: "+381", flag: "rs" },
            { name: "Seychelles", code: "SC", phoneCode: "+248", flag: "sc" },
            { name: "Sierra Leona", code: "SL", phoneCode: "+232", flag: "sl" },
            { name: "Singapur", code: "SG", phoneCode: "+65", flag: "sg" },
            { name: "Siria", code: "SY", phoneCode: "+963", flag: "sy" },
            { name: "Somalia", code: "SO", phoneCode: "+252", flag: "so" },
            { name: "Sri Lanka", code: "LK", phoneCode: "+94", flag: "lk" },
            { name: "Suazilandia", code: "SZ", phoneCode: "+268", flag: "sz" },
            { name: "Sudáfrica", code: "ZA", phoneCode: "+27", flag: "za" },
            { name: "Sudán", code: "SD", phoneCode: "+249", flag: "sd" },
            { name: "Sudán del Sur", code: "SS", phoneCode: "+211", flag: "ss" },
            { name: "Suecia", code: "SE", phoneCode: "+46", flag: "se" },
            { name: "Suiza", code: "CH", phoneCode: "+41", flag: "ch" },
            { name: "Surinam", code: "SR", phoneCode: "+597", flag: "sr" },
            { name: "Tailandia", code: "TH", phoneCode: "+66", flag: "th" },
            { name: "Tanzania", code: "TZ", phoneCode: "+255", flag: "tz" },
            { name: "Tayikistán", code: "TJ", phoneCode: "+992", flag: "tj" },
            { name: "Timor Oriental", code: "TL", phoneCode: "+670", flag: "tl" },
            { name: "Togo", code: "TG", phoneCode: "+228", flag: "tg" },
            { name: "Tonga", code: "TO", phoneCode: "+676", flag: "to" },
            { name: "Trinidad y Tobago", code: "TT", phoneCode: "+1-868", flag: "tt" },
            { name: "Túnez", code: "TN", phoneCode: "+216", flag: "tn" },
            { name: "Turkmenistán", code: "TM", phoneCode: "+993", flag: "tm" },
            { name: "Turquía", code: "TR", phoneCode: "+90", flag: "tr" },
            { name: "Tuvalu", code: "TV", phoneCode: "+688", flag: "tv" },
            { name: "Ucrania", code: "UA", phoneCode: "+380", flag: "ua" },
            { name: "Uganda", code: "UG", phoneCode: "+256", flag: "ug" },
            { name: "Uruguay", code: "UY", phoneCode: "+598", flag: "uy" },
            { name: "Uzbekistán", code: "UZ", phoneCode: "+998", flag: "uz" },
            { name: "Vanuatu", code: "VU", phoneCode: "+678", flag: "vu" },
            { name: "Venezuela", code: "VE", phoneCode: "+58", flag: "ve" },
            { name: "Vietnam", code: "VN", phoneCode: "+84", flag: "vn" },
            { name: "Yemen", code: "YE", phoneCode: "+967", flag: "ye" },
            { name: "Yibuti", code: "DJ", phoneCode: "+253", flag: "dj" },
            { name: "Zambia", code: "ZM", phoneCode: "+260", flag: "zm" },
            { name: "Zimbabue", code: "ZW", phoneCode: "+263", flag: "zw" }
        ];

        // Cargar países en los selectores
        const countrySelect = document.getElementById('country');
        const countryCodeSelect = document.getElementById('countryCode');
        const flagIcon = document.getElementById('flagIcon');

        countries.forEach(country => {
            // Para el selector de país
            const option = document.createElement('option');
            option.value = country.code;
            option.textContent = country.name;
            countrySelect.appendChild(option);
            
            // Para el selector de código de teléfono
            const codeOption = document.createElement('option');
            codeOption.value = country.phoneCode;
            codeOption.textContent = `${country.phoneCode} ${country.name}`;
            codeOption.setAttribute('data-flag', country.flag);
            countryCodeSelect.appendChild(codeOption);
        });

        // Actualizar bandera cuando cambia el código de país
        countryCodeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const flag = selectedOption.getAttribute('data-flag');
            flagIcon.className = `country-flag fi fi-${flag}`;
        });

        // Generar CAPTCHA
        function generateCaptcha() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let captcha = '';
            for (let i = 0; i < 6; i++) {
                captcha += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('captchaText').textContent = captcha;
            return captcha;
        }

        let currentCaptcha = generateCaptcha();

        // Actualizar CAPTCHA
        document.getElementById('refreshCaptcha').addEventListener('click', function() {
            currentCaptcha = generateCaptcha();
        });

        // Validación de formulario
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar contraseña
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            
            if (!passwordRegex.test(password)) {
                passwordError.style.display = 'block';
                isValid = false;
            } else {
                passwordError.style.display = 'none';
            }
            
            // Validar confirmación de contraseña
            const confirmPassword = document.getElementById('confirmPassword').value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            
            if (password !== confirmPassword) {
                confirmPasswordError.style.display = 'block';
                isValid = false;
            } else {
                confirmPasswordError.style.display = 'none';
            }
            
            // Validar CAPTCHA
            const captchaInput = document.getElementById('captchaInput').value;
            const captchaError = document.getElementById('captchaError');
            
            if (captchaInput !== currentCaptcha) {
                captchaError.style.display = 'block';
                isValid = false;
            } else {
                captchaError.style.display = 'none';
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });

        // Validación en tiempo real para la confirmación de contraseña
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            
            if (this.value !== password) {
                confirmPasswordError.style.display = 'block';
            } else {
                confirmPasswordError.style.display = 'none';
            }
        });
    </script>
</body>
</html>