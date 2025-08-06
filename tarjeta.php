<?php
session_start();
require_once 'conexion.php';
require_once 'mercadopago_config.php';

// Redirigir al login si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'tarjeta.php?' . $_SERVER['QUERY_STRING'];
    header('Location: login.php');
    exit;
}

$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;
if ($total <= 0) {
    header('Location: carrito.php');
    exit;
}

$mensaje = '';
$error = '';

// Procesar el pago si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = preg_replace('/\D/', '', $_POST['card_number']);
    $expirationMonth = intval($_POST['expiration_month']);
    $expirationYear = intval($_POST['expiration_year']);
    $securityCode = $_POST['security_code'];
    $cardholderName = $_POST['cardholder_name'];
    $cardholderEmail = $_POST['cardholder_email'];
    $identificationType = $_POST['identification_type'];
    $identificationNumber = $_POST['identification_number'];
    
    // Crear token de tarjeta
    $cardData = [
        'card_number' => $cardNumber,
        'expiration_month' => $expirationMonth,
        'expiration_year' => $expirationYear,
        'security_code' => $securityCode,
        'cardholder' => [
            'name' => $cardholderName,
            'identification' => [
                'type' => $identificationType,
                'number' => $identificationNumber
            ]
        ]
    ];
    
    $cardToken = MercadoPagoConfig::createCardToken($cardData);
    
    if ($cardToken) {
        // Obtener productos del carrito
        $carritoItems = [];
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto_id => $cantidad) {
                $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
                $stmt->execute([$producto_id]);
                $producto = $stmt->fetch();
                
                if ($producto) {
                    $carritoItems[] = [
                        'id' => $producto['id'],
                        'title' => $producto['nombre'],
                        'quantity' => $cantidad,
                        'unit_price' => floatval($producto['precio'])
                    ];
                }
            }
        }
        
        // Crear el pago
        $paymentData = [
            'transaction_amount' => $total,
            'token' => $cardToken['id'],
            'description' => 'Compra en Matex - Productos de mate',
            'installments' => 1,
            'payment_method_id' => $_POST['payment_method_id'] ?? 'visa',
            'payer' => [
                'email' => $cardholderEmail,
                'identification' => [
                    'type' => $identificationType,
                    'number' => $identificationNumber
                ]
            ],
            'external_reference' => 'MATEX-' . $_SESSION['user_id'] . '-' . time(),
            'metadata' => [
                'items' => $carritoItems
            ]
        ];
        
        $payment = MercadoPagoConfig::createPayment($paymentData);
        
        if ($payment) {
            if ($payment['status'] === 'approved') {
                // Pago aprobado - limpiar carrito y redirigir
                $_SESSION['carrito'] = [];
                $_SESSION['ultimo_pago'] = $payment;
                header('Location: pago_exitoso.php');
                exit;
            } elseif ($payment['status'] === 'pending') {
                $_SESSION['ultimo_pago'] = $payment;
                header('Location: pago_pendiente.php');
                exit;
            } else {
                $error = 'El pago fue rechazado. Motivo: ' . ($payment['status_detail'] ?? 'Desconocido');
            }
        } else {
            $error = 'Error al procesar el pago. Intente nuevamente.';
        }
    } else {
        $error = 'Error con los datos de la tarjeta. Verifique la información ingresada.';
    }
}

$totalFormateado = number_format($total, 0, '', '.');
$page_title = "Pagar con Tarjeta - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 2rem;
            padding: 2rem;
        }

        .payment-form {
            flex: 2;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .summary-section {
            flex: 1;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: #1a1a1a;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #22c55e;
        }

        .card-input {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        .pay-button {
            width: 100%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 16px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .pay-button:hover {
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #22c55e;
            text-decoration: none;
            margin-bottom: 2rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .security-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        .card-brands {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .card-brand {
            padding: 4px 8px;
            background: #f1f5f9;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 1rem;
            }
            
            .form-row {
                flex-direction: column;
            }
        }
    </style>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <div class="container">
        <div class="payment-form">
            <a href="mp.php?total=<?= $total ?>" class="back-link">
                ← Volver a métodos de pago
            </a>
            
            <h1 class="form-title">Datos de tu tarjeta</h1>
            
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" id="payment-form">
                <div class="form-group">
                    <label for="card_number">Número de tarjeta</label>
                    <input type="text" id="card_number" name="card_number" class="card-input" 
                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                    <div class="card-brands">
                        <span class="card-brand">Visa</span>
                        <span class="card-brand">Mastercard</span>
                        <span class="card-brand">American Express</span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expiration_month">Mes</label>
                        <select id="expiration_month" name="expiration_month" required>
                            <option value="">Mes</option>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= sprintf('%02d', $i) ?>"><?= sprintf('%02d', $i) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expiration_year">Año</label>
                        <select id="expiration_year" name="expiration_year" required>
                            <option value="">Año</option>
                            <?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="security_code">CVV</label>
                        <input type="text" id="security_code" name="security_code" 
                               placeholder="123" maxlength="4" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cardholder_name">Nombre del titular</label>
                    <input type="text" id="cardholder_name" name="cardholder_name" 
                           placeholder="Como aparece en la tarjeta" required>
                </div>
                
                <div class="form-group">
                    <label for="cardholder_email">Email del titular</label>
                    <input type="email" id="cardholder_email" name="cardholder_email" 
                           placeholder="email@ejemplo.com" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="identification_type">Tipo de documento</label>
                        <select id="identification_type" name="identification_type" required>
                            <option value="DNI">DNI</option>
                            <option value="CI">Cédula de Identidad</option>
                            <option value="LC">Libreta Cívica</option>
                            <option value="LE">Libreta de Enrolamiento</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="identification_number">Número de documento</label>
                        <input type="text" id="identification_number" name="identification_number" 
                               placeholder="12345678" required>
                    </div>
                </div>
                
                <input type="hidden" name="payment_method_id" id="payment_method_id" value="visa">
                
                <button type="submit" class="pay-button">
                    Pagar $<?= $totalFormateado ?>
                </button>
                
                <div class="security-info">
                    🔒 Tu información está protegida con encriptación de nivel bancario. 
                    MercadoPago nunca comparte tus datos financieros.
                </div>
            </form>
        </div>
        
        <div class="summary-section">
            <h3>Resumen de compra</h3>
            
            <?php if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])): ?>
                <?php foreach ($_SESSION['carrito'] as $producto_id => $cantidad): ?>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
                    $stmt->execute([$producto_id]);
                    $producto = $stmt->fetch();
                    if ($producto):
                    ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span><?= htmlspecialchars($producto['nombre']) ?> (x<?= $cantidad ?>)</span>
                        <span>$<?= number_format($producto['precio'] * $cantidad, 0, '', '.') ?></span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <hr style="margin: 1rem 0;">
                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 600;">
                    <span>Total:</span>
                    <span>$<?= $totalFormateado ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Formatear número de tarjeta
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value;
            
            // Detectar tipo de tarjeta
            if (value.startsWith('4')) {
                document.getElementById('payment_method_id').value = 'visa';
            } else if (value.startsWith('5') || value.startsWith('2')) {
                document.getElementById('payment_method_id').value = 'master';
            } else if (value.startsWith('3')) {
                document.getElementById('payment_method_id').value = 'amex';
            }
        });
        
        // Solo números en CVV
        document.getElementById('security_code').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
        
        // Solo números en documento
        document.getElementById('identification_number').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>

<?php include 'footer.php'; ?>