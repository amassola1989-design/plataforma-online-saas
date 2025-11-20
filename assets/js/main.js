/* Sistema de Diseño Massola Group - Basado en sitioamigrar.massolagroup.com */

:root {
    /* Colores corporativos Massola */
    --massola-primary: #2c3e50;
    --massola-secondary: #3498db;
    --massola-accent: #e74c3c;
    --massola-success: #27ae60;
    --massola-warning: #f39c12;
    --massola-danger: #e74c3c;
    --massola-light: #ecf0f1;
    --massola-dark: #2c3e50;
    
    /* Colores adicionales */
    --massola-blue-light: #3498db;
    --massola-blue-dark: #2980b9;
    --massola-green: #2ecc71;
    --massola-orange: #e67e22;
    --massola-purple: #9b59b6;
    
    /* Gradientes */
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    
    /* Sombras */
    --shadow-light: 0 2px 10px rgba(0,0,0,0.1);
    --shadow-medium: 0 5px 15px rgba(0,0,0,0.1);
    --shadow-heavy: 0 10px 30px rgba(0,0,0,0.15);
}

/* Estilos de burbujas Massola */
.bubble-bg {
    position: relative;
    overflow: hidden;
}

.bubble-bg::before {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(52, 152, 219, 0.1);
    top: -50px;
    left: -50px;
    z-index: 0;
}

.bubble-bg::after {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(231, 76, 60, 0.1);
    bottom: -30px;
    right: -30px;
    z-index: 0;
}

/* Botones Massola */
.btn-massola {
    background: var(--massola-secondary);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-massola:hover {
    background: var(--massola-primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
    color: white;
}

.btn-massola-primary {
    background: var(--massola-primary);
}

.btn-massola-success {
    background: var(--massola-success);
}

.btn-massola-warning {
    background: var(--massola-warning);
}

.btn-massola-danger {
    background: var(--massola-danger);
}

/* Tarjetas con diseño Massola */
.card-massola {
    background: white;
    border-radius: 15px;
    box-shadow: var(--shadow-light);
    overflow: hidden;
    transition: all 0.3s ease;
}

.card-massola:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-heavy);
}

.card-massola-header {
    background: var(--gradient-primary);
    color: white;
    padding: 20px;
    text-align: center;
}

.card-massola-body {
    padding: 25px;
}

/* Formularios Massola */
.form-massola .form-group {
    margin-bottom: 20px;
}

.form-massola label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--massola-dark);
}

.form-massola .form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s;
}

.form-massola .form-control:focus {
    border-color: var(--massola-secondary);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    outline: none;
}

/* Navegación Massola */
.nav-massola {
    background: white;
    box-shadow: var(--shadow-light);
    padding: 15px 0;
}

.nav-massola .logo {
    font-size: 24px;
    font-weight: 700;
    color: var(--massola-primary);
    text-decoration: none;
}

.nav-massola .logo span {
    color: var(--massola-secondary);
}

/* Alertas Massola */
.alert-massola {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid;
}

.alert-massola-success {
    background: #d4edda;
    border-color: var(--massola-success);
    color: #155724;
}

.alert-massola-danger {
    background: #f8d7da;
    border-color: var(--massola-danger);
    color: #721c24;
}

.alert-massola-warning {
    background: #fff3cd;
    border-color: var(--massola-warning);
    color: #856404;
}

/* Tablas Massola */
.table-massola {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-light);
}

.table-massola th,
.table-massola td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.table-massola th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--massola-dark);
}

.table-massola tr:hover {
    background: #f8f9fa;
}

/* Responsive */
@media (max-width: 768px) {
    .container-massola {
        padding: 0 15px;
    }
    
    .card-massola {
        margin-bottom: 20px;
    }
    
    .btn-massola {
        width: 100%;
        text-align: center;
    }
}