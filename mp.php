<?php
session_start();

// Redirigir al login si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'mp.php?' . $_SERVER['QUERY_STRING'];
    header('Location: login.php');
    exit;
}

$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;

if ($total <= 0) {
    header('Location: carrito.php');
    exit;
}

$totalFormateado = number_format($total, 0, '', '.');

$page_title = "Pagar - MercadoPago";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilos específicos de mp.php */
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

        .payment-subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .method-group {
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .method-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .group-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
        }

        .payment-option:hover {
            border-color: #0066cc;
            background-color: #f8f9fa;
        }

        .payment-option:last-child {
            margin-bottom: 0;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #0066cc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: bold;
            color: white;
        }

        .payment-icon.card {
            background: #2d3436;
        }

        .payment-icon.cash {
            background: #00b894;
        }

        .payment-details {
            flex: 1;
        }

        .payment-name {
            font-size: 1rem;
            font-weight: 500;
            color: #000;
            margin-bottom: 0.25rem;
        }

        .payment-desc {
            font-size: 0.9rem;
            color: #666;
        }

        .payment-arrow {
            color: #666;
            font-size: 1.2rem;
        }

        .company-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .company-icon {
            width: 40px;
            height: 40px;
            background: #e8f4fd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
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

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #0066cc;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 2rem;
            padding: 0.5rem 0;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .back-arrow {
            margin-right: 0.5rem;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #999;
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e5e5;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-section">
            <h1 class="payment-title">¿Cómo querés pagar?</h1>
            
            <div class="method-group">
                <div class="group-title">Con tu cuenta de Mercado Pago</div>
                <div class="payment-option">
                    <div class="payment-icon">MP</div>
                    <div class="payment-details">
                        <div class="payment-name">Ingresar con mi cuenta</div>
                    </div>
                    <div class="payment-arrow">›</div>
                </div>
            </div>

            <div class="method-group">
                <div class="group-title">Sin cuenta de Mercado Pago</div>
                <a href="tarjeta.php?total=<?= $total ?>" style="text-decoration: none; color: inherit;">
                    <div class="payment-option">
                        <div class="payment-icon card">💳</div>
                        <div class="payment-details">
                            <div class="payment-name">Tarjeta</div>
                            <div class="payment-desc">Crédito, débito o prepaga</div>
                        </div>
                        <div class="payment-arrow">›</div>
                    </div>
                </a>
                
                <div class="payment-option">
                    <div class="payment-icon card">💳</div>
                    <div class="payment-details">
                        <div class="payment-name">2 tarjetas de crédito</div>
                    </div>
                    <div class="payment-arrow">›</div>
                </div>
            </div>

            <a href="carrito.php" class="back-link">
                <span class="back-arrow">←</span>
                Volver al sitio
            </a>

            <div class="footer-text">
                Procesado por Mercado Pago.
            </div>
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

<?php include 'footer.php'; ?>