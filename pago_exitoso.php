<?php
session_start();
require_once 'conexion.php';

// Verificar si hay información de pago
if (!isset($_SESSION['ultimo_pago'])) {
    header('Location: index.php');
    exit;
}

$pago = $_SESSION['ultimo_pago'];

$page_title = "¡Pago Exitoso! - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        .success-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }
        
        .success-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #22c55e;
            margin-bottom: 1rem;
        }
        
        .success-title {
            font-size: 2.5rem;
            color: #22c55e;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .success-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .payment-details {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .detail-value {
            color: #666;
        }
        
        .amount-highlight {
            color: #22c55e;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .next-steps {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid #22c55e;
        }
        
        .next-steps h3 {
            color: #16a34a;
            margin-bottom: 1rem;
        }
        
        .next-steps ul {
            text-align: left;
            color: #166534;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e5e5e5;
        }
        
        .email-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            color: #1e40af;
        }
        
        @media (max-width: 768px) {
            .success-container {
                padding: 1rem;
            }
            
            .success-card {
                padding: 2rem;
            }
            
            .success-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
        
        .mate-celebration {
            font-size: 2rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">✅</div>
            <h1 class="success-title">¡Pago Exitoso!</h1>
            <div class="mate-celebration">🧉 ¡Ya podés preparar tu mate! 🧉</div>
            <p class="success-subtitle">
                Tu compra se procesó correctamente y pronto recibirás tus productos Matex
            </p>
            
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">ID de Pago:</span>
                    <span class="detail-value">#<?= htmlspecialchars($pago['id'] ?? 'MP-' . time()) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value" style="color: #22c55e; font-weight: 600;">
                        <?= $pago['status'] === 'approved' ? 'APROBADO' : strtoupper($pago['status'] ?? 'APROBADO') ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Monto Total:</span>
                    <span class="detail-value amount-highlight">
                        $<?= number_format($pago['transaction_amount'] ?? 0, 0, '', '.') ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Método de Pago:</span>
                    <span class="detail-value">
                        <?= strtoupper($pago['payment_method_id'] ?? 'Tarjeta') ?>
                        <?php if (isset($pago['card'])): ?>
                            **** <?= substr($pago['card']['last_four_digits'] ?? '0000', -4) ?>
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Fecha:</span>
                    <span class="detail-value">
                        <?= date('d/m/Y H:i:s') ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Referencia:</span>
                    <span class="detail-value"><?= htmlspecialchars($pago['external_reference'] ?? 'MATEX-' . time()) ?></span>
                </div>
            </div>
            
            <div class="next-steps">
                <h3>¿Qué sigue ahora?</h3>
                <ul>
                    <li><strong>Confirmación por email:</strong> Te enviaremos un comprobante a tu correo electrónico</li>
                    <li><strong>Preparación del pedido:</strong> Procesaremos tu pedido en 1-2 días hábiles</li>
                    <li><strong>Envío:</strong> Te notificaremos cuando tu pedido esté en camino</li>
                    <li><strong>Seguimiento:</strong> Podrás rastrear tu envío con el código que te enviaremos</li>
                </ul>
            </div>
            
            <div class="email-info">
                📧 <strong>Importante:</strong> Revisá tu email (incluyendo spam) para el comprobante de pago y detalles del envío
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn btn-primary">Seguir Comprando</a>
                <a href="mis_pedidos.php" class="btn btn-secondary">Ver Mis Pedidos</a>
            </div>
        </div>
        
        <div style="text-align: center; color: #666; margin-top: 2rem;">
            <p>¿Tenés alguna consulta? Contactanos:</p>
            <p>
                📱 WhatsApp: +54 9 2252 45-6409 | 📧 Email: info@matex.com.ar
            </p>
        </div>
    </div>
    
    <script>
        // Limpiar la información del pago después de mostrarla
        setTimeout(() => {
            <?php unset($_SESSION['ultimo_pago']); ?>
        }, 1000);
        
        // Confeti de celebración
        function createConfetti() {
            const colors = ['#22c55e', '#16a34a', '#15803d', '#166534'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.cssText = `
                        position: fixed;
                        top: -10px;
                        left: ${Math.random() * 100}vw;
                        width: 10px;
                        height: 10px;
                        background: ${colors[Math.floor(Math.random() * colors.length)]};
                        z-index: 1000;
                        pointer-events: none;
                        border-radius: 50%;
                        animation: fall ${2 + Math.random() * 3}s linear forwards;
                    `;
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 5000);
                }, i * 100);
            }
        }
        
        // CSS para la animación de caída
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                0% { transform: translateY(-100vh) rotate(0deg); }
                100% { transform: translateY(100vh) rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
        
        // Ejecutar confeti al cargar
        createConfetti();
    </script>

<?php include 'footer.php'; ?>