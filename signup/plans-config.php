<?php
// plans-config.php - CONFIGURACIÓN ACTUALIZADA DE MASSOLA BUSINESS
$PLANES_CONFIG = [
    'basico' => [
        'id' => 'basico',
        'nombre' => 'Plan Básico',
        'precio_mensual' => 550,
        'precio_normal' => 3300,
        'precio_descuento' => 2805,
        'ahorro' => 495,
        'moneda' => 'CUP',
        'intervalo' => 'semestral',
        'caracteristicas' => [
            'Perfil de negocio',
            'Gestión de pedidos', 
            'Categorías colapsables',
            'Gestión de productos',
            'Extras personalizables',
            'Múltiples imágenes por elemento',
            'SSL Gratuito'
        ],
        'texto_descuento' => 'Suscripción semestral para residentes en Cuba con 15% de descuento. Por $2,805.00 y ahorras $495.00.'
    ],
    'profesional' => [
        'id' => 'profesional',
        'nombre' => 'Plan Profesional', 
        'precio_mensual' => 750,
        'precio_normal' => 4500,
        'precio_descuento' => 3825,
        'ahorro' => 675,
        'moneda' => 'CUP',
        'intervalo' => 'semestral',
        'caracteristicas' => [
            'Productos ilimitados',
            'Perfil de negocio',
            'Gestión de pedidos',
            'Categorías colapsables',
            'Gestión de productos',
            'Extras personalizables',
            'Múltiples imágenes por elemento', 
            'Soporte prioritario',
            'Estadísticas avanzadas',
            'Sub-Dominio personalizado',
            'SSL Gratuito'
        ],
        'texto_descuento' => 'Suscripción semestral para residentes en Cuba con 15% de descuento. Por $3,825.00 y ahorras $675.00.'
    ],
    'empresa' => [
        'id' => 'empresa',
        'nombre' => 'Plan Empresa',
        'precio_mensual' => 1500, 
        'precio_normal' => 9000,
        'precio_descuento' => 7650,
        'ahorro' => 1350,
        'moneda' => 'CUP',
        'intervalo' => 'semestral',
        'caracteristicas' => [
            'Productos ilimitados',
            'Almacenamiento ilimitado',
            'Perfil de negocio',
            'Gestión de pedidos',
            'Categorías colapsables',
            'Gestión de productos',
            'Extras personalizables',
            'Múltiples imágenes por elemento',
            'Soporte prioritario', 
            'Estadísticas avanzadas',
            'Sub-Dominio personalizado',
            'Reportes avanzados',
            'SSL Gratuito'
        ],
        'texto_descuento' => 'Suscripción semestral para residentes en Cuba con 15% de descuento. Por $7,650.00 y ahorras $1,350.00.'
    ]
];
?>