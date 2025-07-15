<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Obtener ID del usuario
$usuario_id = $_SESSION['usuario_id'];

// Validar datos requeridos
$campos_requeridos = [
    'producto_id', 'cantidad', 'subtotal', 'impuestos', 'total',
    'nombre', 'telefono', 'email', 'direccion', 'ciudad', 'codigo_postal',
    'metodo_pago'
];

foreach ($campos_requeridos as $campo) {
    if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
        $error = "El campo $campo es requerido";
        header("Location: compra.php?error=" . urlencode($error));
        exit;
    }
}

// Sanitizar y validar datos
$producto_id = (int)$_POST['producto_id'];
$cantidad = (int)$_POST['cantidad'];
$subtotal = (float)$_POST['subtotal'];
$impuestos = (float)$_POST['impuestos'];
$total = (float)$_POST['total'];

// Datos de envío
$nombre = trim($_POST['nombre']);
$telefono = trim($_POST['telefono']);
$email = trim($_POST['email']);
$direccion = trim($_POST['direccion']);
$ciudad = trim($_POST['ciudad']);
$codigo_postal = trim($_POST['codigo_postal']);
$notas = trim($_POST['notas'] ?? '');

// Método de pago
$metodo_pago = $_POST['metodo_pago'];

// Validaciones adicionales
if ($cantidad < 1) {
    header("Location: compra.php?error=cantidad_invalida");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: compra.php?error=email_invalido");
    exit;
}

if (!in_array($metodo_pago, ['tarjeta', 'mercadopago', 'transferencia'])) {
    header("Location: compra.php?error=metodo_pago_invalido");
    exit;
}

// Verificar que el producto existe
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

if (!$producto) {
    header("Location: index.php?error=producto_no_encontrado");
    exit;
}

// Verificar que los cálculos sean correctos
$subtotal_calculado = $producto['precio'] * $cantidad;
$impuestos_calculados = $subtotal_calculado * 0.21;
$total_calculado = $subtotal_calculado + $impuestos_calculados;

if (abs($total - $total_calculado) > 0.01) {
    header("Location: compra.php?error=calculo_incorrecto");
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Generar número de pedido único
    $numero_pedido = 'PED-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    
    // Verificar que el número de pedido no exista
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE numero_pedido = ?");
    $stmt->execute([$numero_pedido]);
    while ($stmt->fetchColumn() > 0) {
        $numero_pedido = 'PED-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $stmt->execute([$numero_pedido]);
    }

    // Insertar pedido principal
    $stmt = $pdo->prepare("
        INSERT INTO pedidos (
            numero_pedido, usuario_id, producto_id, cantidad, 
            subtotal, impuestos, total, estado, metodo_pago,
            nombre_cliente, telefono, email, direccion, ciudad, 
            codigo_postal, notas, fecha_creacion
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )
    ");

    $stmt->execute([
        $numero_pedido, $usuario_id, $producto_id, $cantidad,
        $subtotal, $impuestos, $total, $metodo_pago,
        $nombre, $telefono, $email, $direccion, $ciudad,
        $codigo_postal, $notas
    ]);

    $pedido_id = $pdo->lastInsertId();

    // Insertar detalle del pedido (para futuras expansiones con múltiples productos)
    $stmt = $pdo->prepare("
        INSERT INTO pedido_detalles (
            pedido_id, producto_id, cantidad, precio_unitario, subtotal
        ) VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $pedido_id, $producto_id, $cantidad, $producto['precio'], $subtotal
    ]);

    // Actualizar stock del producto (si tienes campo stock)
    $stmt = $pdo->prepare("
        UPDATE productos 
        SET stock = stock - ? 
        WHERE id = ? AND stock >= ?
    ");
    $stmt->execute([$cantidad, $producto_id, $cantidad]);

    // Verificar si se actualizó el stock (opcional, depende de tu lógica de negocio)
    if ($stmt->rowCount() === 0) {
        // Si no se pudo actualizar el stock, podrías manejar esto según tu lógica
        // Por ahora, continuamos con el pedido
    }

    // Confirmar transacción
    $pdo->commit();

    // Enviar email de confirmación (opcional)
    enviarEmailConfirmacion($email, $numero_pedido, $nombre, $total, $producto['nombre']);

    // Redirigir a página de confirmación
    header("Location: confirmacion_compra.php?pedido=" . $numero_pedido);
    exit;

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $pdo->rollBack();
    
    // Log del error (en producción, usar un sistema de logs apropiado)
    error_log("Error en procesar_compra.php: " . $e->getMessage());
    
    // Redirigir con error
    header("Location: compra.php?error=error_procesamiento");
    exit;
}

// Función para enviar email de confirmación
function enviarEmailConfirmacion($email, $numero_pedido, $nombre, $total, $producto_nombre) {
    $asunto = "Confirmación de pedido - $numero_pedido";
    $mensaje = "
    <html>
    <head>
        <title>Confirmación de Pedido</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; }
            .header { background: #27ae60; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .footer { background: #f8f9fa; padding: 15px; text-align: center; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>¡Gracias por tu compra!</h1>
            </div>
            <div class='content'>
                <h2>Hola $nombre,</h2>
                <p>Tu pedido ha sido procesado exitosamente.</p>
                
                <h3>Detalles del pedido:</h3>
                <ul>
                    <li><strong>Número de pedido:</strong> $numero_pedido</li>
                    <li><strong>Producto:</strong> $producto_nombre</li>
                    <li><strong>Total:</strong> $" . number_format($total, 2, ',', '.') . "</li>
                </ul>
                
                <p>Recibirás un email adicional cuando tu pedido sea enviado.</p>
                
                <p>¡Gracias por elegirnos!</p>
            </div>
            <div class='footer'>
                <p>Matex - Tu tienda de confianza</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = [
        'MIME-Version' => '1.0',
        'Content-type' => 'text/html; charset=UTF-8',
        'From' => 'noreply@matex.com',
        'Reply-To' => 'contacto@matex.com',
        'X-Mailer' => 'PHP/' . phpversion()
    ];
    
    $headers_string = '';
    foreach ($headers as $key => $value) {
        $headers_string .= "$key: $value\r\n";
    }
    
    // Enviar email (asegúrate de que tu servidor tenga configurado el envío de emails)
    @mail($email, $asunto, $mensaje, $headers_string);
}
?>