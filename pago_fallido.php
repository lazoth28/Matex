<?php
session_start();
require_once 'conexion.php';

$page_title = "Pago Rechazado - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        .error-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }
        
        .error-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .error-icon {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-size: 2.5rem;
            color: #ef4444;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .error-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .error-reasons {
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .error-reasons h3 {
            color: #dc2626;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .error-reasons ul {
            color: #991b1b;
            margin-left: 1rem;
        }
        
        .error-reasons li {
            margin-bottom: 0.5rem;
        }
        
        .solutions {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid #22c55e;
        }
        
        .solutions h3 {
            color: #16a34a;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .solutions ul {
            text-align: left;
            color: #166534;
        }
        
        .solutions li {
            margin-bottom: 0.8rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
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
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e5e5e5;
        }
        
        .contact-help {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            color: #1e40af;
        }
        
        .contact-help h4 {
            color: #1d4ed8;
            margin-bottom: 1rem;
        }
        
        .payment-tips {
            background: #fefce8;
            border: 1px solid #fde047;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .payment-tips h4 {
            color: #ca8a04;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 1rem;
            color: #713f12;
        }
        
        .tip-icon {
            color: #eab308;
            font-size: 1.2rem;
            margin-top: 2px;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 1rem;
            }
            
            .error-card {
                padding: 2rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">❌</div>
            <h1 class="error-title">Pago Rechazado</h1>
            <p class="error-subtitle">
                No pudimos procesar tu pago. No te preocupes, podés intentar nuevamente.
            </p>
            
            <div class="error-reasons">
                <h3>🔍 Posibles causas del rechazo:</h3>
                <ul>
                    <li><strong>Datos incorrectos:</strong> Número de tarjeta, fecha de vencimiento o CVV incorrectos</li>
                    <li><strong>Fondos insuficientes:</strong> La tarjeta no tiene saldo disponible</li>
                    <li><strong>Límites excedidos:</strong> Se superó el límite de compra diario/mensual</li>
                    <li><strong>Tarjeta vencida:</strong> La tarjeta está fuera de fecha de vigencia</li>
                    <li><strong>Bloqueo de seguridad:</strong> El banco bloqueó la transacción por seguridad</li>
                    <li><strong>Problemas de conectividad:</strong> Error temporal en la comunicación</li>
                </ul>
            </div>
            
            <div class="solutions">
                <h3>💡 ¿Qué podés hacer?</h3>
                <ul>
                    <li><strong>Verificar datos:</strong> Revisá que todos los datos de la tarjeta sean correctos</li>
                    <li><strong>Contactar al banco:</strong> Consultá si hay algún bloqueo en tu tarjeta</li>
                    <li><strong>Probar otra tarjeta:</strong> Utilizá una tarjeta diferente si tenés disponible</li>
                    <li><strong>Verificar saldo:</strong> Asegurate de tener fondos suficientes</li>
                    <li><strong>Intentar más tarde:</strong> A veces es un problema temporal</li>
                </ul>
            </div>
            
            <div class="payment-tips">
                <h4>💳 Consejos para un pago exitoso:</h4>
                
                <div class="tip-item">
                    <span class="tip-icon">✓</span>
                    <div>
                        <strong>Datos exactos:</strong> Ingresá los datos tal como aparecen en tu tarjeta física
                    </div>
                </div>
                
                <div class="tip-item">
                    <span class="tip-icon">✓</span>
                    <div>
                        <strong>Conexión estable:</strong> Asegurate de tener buena conexión a internet
                    </div>
                </div>
                
                <div class="tip-item">
                    <span class="tip-icon">✓</span>
                    <div>
                        <strong>Navegador actualizado:</strong> Usá la versión más reciente de tu navegador
                    </div>
                </div>
                
                <div class="tip-item">
                    <span class="tip-icon">✓</span>
                    <div>
                        <strong>Sin bloqueos:</strong> Desactivá temporalmente bloqueadores de publicidad
                    </div>
                </div>
            </div>
            
            <div class="contact-help">
                <h4>🆘 ¿Necesitás ayuda personalizada?</h4>
                <p>
                    Nuestro equipo está disponible para ayudarte con tu compra:
                </p>
                <p>
                    📱 <strong>WhatsApp:</strong> +54 9 2252 45-6409<br>
                    📧 <strong>Email:</strong> info@matex.com.ar<br>
                    🕐 <strong>Horario:</strong> Lunes a Viernes 9:00-18:00
                </p>
                <p style="margin-top: 1rem; font-size: 0.9rem;">
                    <strong>Tip:</strong> Si nos escribís, mencioná el problema que tuviste para poder ayudarte mejor.
                </p>
            </div>
            
            <div class="action-buttons">
                <a href="carrito.php" class="btn btn-danger">🔄 Reintentar Pago</a>
                <a href="mp.php?total=<?= $_GET['total'] ?? '0' ?>" class="btn btn-primary">💳 Cambiar Método</a>
                <a href="index.php" class="btn btn-secondary">🏠 Volver al Inicio</a>
            </div>
        </div>
        
        <div style="text-align: center; color: #666; margin-top: 2rem; background: white; padding: 2rem; border-radius: 12px;">
            <h3 style="color: #22c55e; margin-bottom: 1rem;">🛡️ Comprás 100% Seguro</h3>
            <p>
                Todos nuestros pagos están protegidos por <strong>MercadoPago</strong> con encriptación 
                de nivel bancario. Tus datos están completamente seguros.
            </p>
            <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1rem; flex-wrap: wrap;">
                <span style="color: #22c55e;">🔒 SSL Certificado</span>
                <span style="color: #22c55e;">🛡️ Protección Antifraude</span>
                <span style="color: #22c55e;">💯 Garantía Total</span>
            </div>
        </div>
    </div>
    
    <script>
        // Mostrar mensaje de ayuda después de unos segundos
        setTimeout(() => {
            const helpMessage = document.createElement('div');
            helpMessage.innerHTML = `
                <div style="
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: linear-gradient(135deg, #22c55e, #16a34a);
                    color: white;
                    padding: 1rem;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    z-index: 1000;
                    max-width: 300px;
                    cursor: pointer;
                " onclick="window.open('https://wa.me/5492252456409?text=Hola, tuve problemas con mi pago en Matex y necesito ayuda', '_blank')">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.2rem;">💬</span>
                        <div>
                            <strong>¿Necesitás ayuda?</strong><br>
                            <small>Hablá con nosotros por WhatsApp</small>
                        </div>
                    </div>
                    <button onclick="event.stopPropagation(); this.parentElement.parentElement.remove()" style="
                        position: absolute;
                        top: 5px;
                        right: 5px;
                        background: rgba(255,255,255,0.2);
                        border: none;
                        color: white;
                        border-radius: 50%;
                        width: 25px;
                        height: 25px;
                        cursor: pointer;
                        font-size: 0.8rem;
                    ">×</button>
                </div>
            `;
            document.body.appendChild(helpMessage);
        }, 5000);
        
        // Registrar el evento de pago fallido para analytics
        console.log('Pago rechazado - Usuario en página de error');
    </script>

<?php include 'footer.php'; ?>