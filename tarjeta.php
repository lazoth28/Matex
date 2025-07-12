<?php
session_start();

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

$totalFormateado = number_format($total, 0, '', '.');

$page_title = "Pagar con Tarjeta - MercadoPago";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilos específicos de tarjeta.php */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 2rem;
            padding: 2rem;
        }

        .payment-section {
            flex: 1;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .summary-section {
            width: 350px;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .payment-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #000;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.1);
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        .card-icons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .card-icon {
            width: 30px;
            height: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            color: white;
        }

        .visa { background: #1A1F71; }
        .mastercard { background: #EB001B; }
        .amex { background: #006FCF; }

        .installments-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e5e5;
        }

        .installments-title {
            font-size: 1rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 1rem;
        }

        .installment-option {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
        }

        .installment-option:hover {
            border-color: #0066cc;
            background-color: #f8f9fa;
        }

        .installment-option input[type="radio"] {
            margin-right: 1rem;
        }

        .installment-details {
            flex: 1;
        }

        .installment-text {
            font-size: 0.9rem;
            color: #333;
        }

        .installment-amount {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .pay-button {
            width: 100%;
            padding: 1rem;
            background: #00a650;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 2rem;
        }

        .pay-button:hover {
            background: #008f44;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #0066cc;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 1rem;
            padding: 0.5rem 0;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .back-arrow {
            margin-right: 0.5rem;
        }

        .company-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .logo {
            margin-right: 1rem;
        }

        .logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 4px;
        }

        .company-name {
            font-size: 1rem;
            font-weight: 600;
            color: #000;
        }

        .purchase-details {
            margin-bottom: 2rem;
        }

        .details-title {
            font-size: 1rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 1rem;
        }

        .purchase-item {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 600;
            color: #000;
            text-align: right;
        }

        .security-info {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: #666;
            margin-top: 1rem;
        }

        .security-icon {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 1rem;
            }
            
            .summary-section {
                width: 100%;
                order: -1;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-section">
            <h1 class="payment-title">Pagar con tarjeta</h1>
            
            <form id="payment-form" method="POST" action="procesar_pago.php">
                <input type="hidden" name="total" value="<?= $total ?>">
                <input type="hidden" name="metodo_pago" value="tarjeta">
                
                <div class="form-group">
                    <label class="form-label" for="card-number">Número de tarjeta</label>
                    <input type="text" id="card-number" name="card_number" class="form-input" 
                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                    <div class="card-icons">
                        <div class="card-icon visa">VISA</div>
                        <div class="card-icon mastercard">MC</div>
                        <div class="card-icon amex">AMEX</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="card-holder">Nombre y apellido</label>
                    <input type="text" id="card-holder" name="card_holder" class="form-input" 
                           placeholder="Como aparece en la tarjeta" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="expiry-date">Vencimiento</label>
                        <input type="text" id="expiry-date" name="expiry_date" class="form-input" 
                               placeholder="MM/AA" maxlength="5" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" class="form-input" 
                               placeholder="123" maxlength="4" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="tu@email.com" required>
                </div>

                <div class="installments-section">
                    <div class="installments-title">Cuotas</div>
                    
                    <div class="installment-option">
                        <input type="radio" id="cuotas-1" name="cuotas" value="1" checked>
                        <label for="cuotas-1" class="installment-details">
                            <div class="installment-text">1 cuota sin interés</div>
                            <div class="installment-amount">$ <?= $totalFormateado ?></div>
                        </label>
                    </div>
                    
                    <div class="installment-option">
                        <input type="radio" id="cuotas-3" name="cuotas" value="3">
                        <label for="cuotas-3" class="installment-details">
                            <div class="installment-text">3 cuotas sin interés</div>
                            <div class="installment-amount">$ <?= number_format($total/3, 0, '', '.') ?> por mes</div>
                        </label>
                    </div>
                    
                    <div class="installment-option">
                        <input type="radio" id="cuotas-6" name="cuotas" value="6">
                        <label for="cuotas-6" class="installment-details">
                            <div class="installment-text">6 cuotas sin interés</div>
                            <div class="installment-amount">$ <?= number_format($total/6, 0, '', '.') ?> por mes</div>
                        </label>
                    </div>
                    
                    <div class="installment-option">
                        <input type="radio" id="cuotas-12" name="cuotas" value="12">
                        <label for="cuotas-12" class="installment-details">
                            <div class="installment-text">12 cuotas con interés</div>
                            <div class="installment-amount">$ <?= number_format(($total * 1.15)/12, 0, '', '.') ?> por mes</div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="pay-button">
                    Pagar $ <?= $totalFormateado ?>
                </button>
                
                <div class="security-info">
                    <span class="security-icon">🔒</span>
                    Tus datos están protegidos por Mercado Pago
                </div>
            </form>

            <a href="mp.php?total=<?= $total ?>" class="back-link">
                <span class="back-arrow">←</span>
                Cambiar forma de pago
            </a>
        </div>

        <div class="summary-section">
            <div class="company-info">
                <div class="logo">
                    <img src="img-MATEX.png" alt="MATEX Logo"/>
                </div>
                <div class="company-name">MATEX</div>
            </div>

            <div class="purchase-details">
                <div class="details-title">Detalles del pago</div>
                <div class="purchase-item">
                    Productos de tu carrito Matex
                </div>
            </div>

            <div class="total-amount">
                $ <?= $totalFormateado ?>
            </div>
        </div>
    </div>

    <script>
        // Formatear número de tarjeta
        document.getElementById('card-number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = formattedValue;
        });

        // Formatear fecha de vencimiento
        document.getElementById('expiry-date').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Solo números en CVV
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Actualizar total al cambiar cuotas
        document.querySelectorAll('input[name="cuotas"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const payButton = document.querySelector('.pay-button');
                const cuotas = parseInt(this.value);
                let total = <?= $total ?>;
                
                if (cuotas === 12) {
                    total = total * 1.15;
                }
                
                const totalFormatted = new Intl.NumberFormat('es-AR').format(total);
                payButton.textContent = `Pagar $ ${totalFormatted}`;
            });
        });
    </script>

<?php include 'footer.php'; ?>