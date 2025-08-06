<?php
session_start();
require_once 'conexion.php';

// Verificar si hay información de pago
if (!isset($_SESSION['ultimo_pago'])) {
    header('Location: index.php');
    exit;
}

$pago = $_SESSION['ultimo_pago'];

$page_title = "Pago en Proceso - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        .pending-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }
        
        .pending-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .pending-icon {
            font-size: 4rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }
        
        .pending-title {
            font-size: 2.5rem;
            color: #f59e0b;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .pending-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .payment-details {
            background: #fffbeb;
            border: 1px solid #fed7aa;
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
            border-bottom: 1px solid #fed7aa;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #92400e;
        }
        
        .detail-value {
            color: #a16207;
        }
        
        .amount-highlight {
            color: #d97706;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .next-steps {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid #f59e0b;
        }
        
        .next-steps h3 {
            color: #d97706;
            margin-bottom: 1rem;
        }
        
        .next-steps ul {
            text-align: left;
            color: #92400e;
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
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e5e5e5;
        }
        
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 20px;
            color: #92400e;
            font-weight: 600;
            margin: 1rem 0;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .info-box {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            color: #1e40af;
        }
        
        .contact-info {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .pending-container {
                padding: 1rem;
            }
            
            .pending-card {
                padding: 2rem;
            }
            
            .pending-title {
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
    </style>
</head>
<body>
    <div class="pending-container">
        <div class="pending-card">
            <div class="pending-icon pulse">⏳</div>
            <h1 class="pending-title">Pago en Proceso</h1>
            <p class="pending-subtitle">
                Tu pago está siendo procesado. Te notificaremos cuando esté confirmado.
            </p>
            
            <div class="status-indicator">
                <span class="pulse">🔄</span>
                Procesando pago...
            </div>
            
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">ID de Pago:</span>
                    <span class="detail-value">#<?= htmlspecialchars($pago['id'] ?? 'MP-' . time()) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value" style="color: #f59e0b; font-weight: 600;">
                        PENDIENTE
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
                <h3>¿Qué está pasando?</h3>
                <ul>
                    <li><strong>Verificación:</strong> Estamos verificando tu pago con el banco</li>
                    <li><strong>Tiempo estimado:</strong> El proceso puede tomar hasta 48 horas</li>
                    <li><strong>Notificación:</strong> Te avisaremos por email cuando se confirme</li>
                    <li><strong>Seguimiento:</strong> Podés consultar el estado en "Mis Pedidos"</li>
                </ul>
            </div>
            
            <div class="info-box">
                <strong>🔍 ¿Por qué está pendiente mi pago?</strong>
                <br><br>
                Los pagos pueden quedar pendientes por varios motivos:
                <ul style="margin-top: 1rem;">
                    <li>Verificación de seguridad del banco</li>
                    <li>Límites de la tarjeta</li>
                    <li>Validación de datos</li>
                </ul>
            </div>
            
            <div class="contact-info">
                <strong>📞 ¿Necesitás ayuda?</strong>
                <br><br>
                Si tenés alguna duda sobre tu pago, contactanos:
                <br>
                📱 WhatsApp: +54 9 2252 45-6409
                <br>
                📧 Email: info@matex.com.ar
                <br>
                🕐 Horario: Lunes a Viernes 9:00-18:00
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
                <a href="mis_pedidos.php" class="btn btn-warning">Ver Estado del Pago</a>
                <a href="carrito.php" class="btn btn-secondary">Volver al Carrito</a>
            </div>
        </div>
    </div>
    
    <script>
        // Actualizar estado cada 30 segundos
        let checkCount = 0;
        const maxChecks = 10; // Máximo 5 minutos
        
        const statusChecker = setInterval(() => {
            checkCount++;
            console.log(`Verificando estado del pago... (${checkCount}/${maxChecks})`);
            
            // Aquí podrías hacer una llamada AJAX para verificar el estado
            // fetch('/check_payment_status.php?id=<?= $pago['id'] ?? '' ?>')
            
            if (checkCount >= maxChecks) {
                clearInterval(statusChecker);
                console.log('Verificación automática finalizada');
            }
        }, 30000);
        
        // Simulación de notificación de actualización
        setTimeout(() => {
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #fef3c7;
                    border: 1px solid #f59e0b;
                    padding: 1rem;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    z-index: 1000;
                    max-width: 300px;
                ">
                    <strong>💡 Recordatorio:</strong><br>
                    Te enviaremos un email cuando tu pago sea confirmado.
                    <button onclick="this.parentElement.parentElement.remove()" style="
                        float: right;
                        background: none;
                        border: none;
                        font-size: 1.2rem;
                        cursor: pointer;
                        margin-left: 10px;
                    ">×</button>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }, 3000);
    </script>

<?php include 'footer.php'; ?>