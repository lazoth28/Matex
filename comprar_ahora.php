<?php
session_start();
require 'conexion.php';

// Debug: Mostrar qué datos se están recibiendo (remover en producción)
// echo '<pre>POST data: '; print_r($_POST); echo '</pre>';
// echo '<pre>GET data: '; print_r($_GET); echo '</pre>';

// Verificar si se recibieron los datos del formulario (POST o GET)
$producto_id = null;
$cantidad = 1;

if (isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    // Datos vienen por POST
    $producto_id = $_POST['producto_id'];
    $cantidad = (int)$_POST['cantidad'];
} elseif (isset($_GET['producto_id']) && isset($_GET['cantidad'])) {
    // Datos vienen por GET (como respaldo)
    $producto_id = $_GET['producto_id'];
    $cantidad = (int)$_GET['cantidad'];
} else {
    // Si no se reciben datos, redirigir con mensaje de error
    header('Location: index.php?error=datos_faltantes');
    exit;
}

// Validar cantidad mínima
if ($cantidad < 1) {
    $cantidad = 1;
}

// Obtener información del producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: index.php?error=producto_no_encontrado');
    exit;
}

// Calcular el total
$subtotal = $producto['precio'] * $cantidad;
$impuestos = $subtotal * 0.21; // 21% de IVA
$total = $subtotal + $impuestos;

$page_title = "Comprar Ahora - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        .compra-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .compra-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .compra-header h1 {
            color: #27ae60;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .compra-header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        
        .producto-resumen {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .producto-info-compra {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .producto-imagen-compra {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            object-fit: cover;
        }
        
        .sin-imagen-compra {
            width: 120px;
            height: 120px;
            background: #27ae60;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #666;
        }
        
        .producto-detalles-compra h3 {
            color: #27ae60;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }
        
        .producto-detalles-compra p {
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .cantidad-precio {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #27ae60;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .resumen-precios {
            border-top: 2px solid #eee;
            padding-top: 1rem;
            color:rgb(255, 255, 255);
             padding: 1rem;
            background: #27ae60;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .precio-linea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .precio-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            font-weight: bold;
            color:rgb(255, 255, 255);
            border-top: 1px solid #eee;
            padding-top: 1rem;
            margin-top: 1rem;

        }
        
        .formulario-compra {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .formulario-compra h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4a90e2;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .metodo-pago {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .metodo-opcion {

            border: 2px solid #ddd;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background:rgba(39, 174, 95, 0.59);
            color:rgb(255, 255, 255);
        }
        
        .metodo-opcion:hover {
            border-color: #27ae60;
        }
        
        .metodo-opcion.selected {
            border-color: #4a90e2;
            background: #27ae60;
        }
        
        .metodo-opcion input[type="radio"] {
            display: none;
        }
        
        .btn-finalizar {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 2rem;
        }
        
        .btn-finalizar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
        }
        
        .btn-volver {
            background: #6c757d;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 1rem;
            transition: background 0.3s ease;
        }
        
        .btn-volver:hover {
            background: #5a6268;
        }
        
        .debug-info {
            background: #27ae60;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #007bff;
        }
        
        @media (max-width: 768px) {
            .producto-info-compra {
                flex-direction: column;
                text-align: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .metodo-pago {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="compra-container">
        <div class="producto-resumen">
            <div class="producto-info-compra">
                <?php if ($producto['imagen']): ?>
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="producto-imagen-compra">
                <?php else: ?>
                    <div class="sin-imagen-compra">Sin imagen</div>
                <?php endif; ?>
                
                <div class="producto-detalles-compra">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p><strong>Origen:</strong> <?= htmlspecialchars($producto['origen']) ?></p>
                    <p><strong>Precio unitario:</strong> $<?= number_format($producto['precio'], 2, ',', '.') ?></p>
                </div>
            </div>
            
            <div class="cantidad-precio">
                <span><strong>Cantidad:</strong> <?= $cantidad ?></span>
                <span><strong>Subtotal:</strong> $<?= number_format($subtotal, 2, ',', '.') ?></span>
            </div>
            
            <div class="resumen-precios">
                <div class="precio-linea">
                    <span>Subtotal:</span>
                    <span>$<?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
                <div class="precio-linea">
                    <span>IVA (21%):</span>
                    <span>$<?= number_format($impuestos, 2, ',', '.') ?></span>
                </div>
                <div class="precio-linea">
                    <span>Envío:</span>
                    <span>GRATIS</span>
                </div>
                <div class="precio-total">
                    <span>Total:</span>
                    <span>$<?= number_format($total, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
        
        <form action="procesar_compra.php" method="POST" class="formulario-compra">
            <h2>Información de Envío</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre Completo *</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono *</label>
                    <input type="tel" id="telefono" name="telefono" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección *</label>
                <input type="text" id="direccion" name="direccion" placeholder="Calle, número, piso, departamento" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ciudad">Ciudad *</label>
                    <input type="text" id="ciudad" name="ciudad" required>
                </div>
                <div class="form-group">
                    <label for="codigo_postal">Código Postal *</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notas">Notas adicionales</label>
                <textarea id="notas" name="notas" rows="3" placeholder="Instrucciones especiales para la entrega..."></textarea>
            </div>
            
            <h2>Método de Pago</h2>
            
            <div class="metodo-pago">
                <div class="metodo-opcion" onclick="selectPayment('tarjeta')">
                    <input type="radio" id="tarjeta" name="metodo_pago" value="tarjeta" required>
                    <div>💳</div>
                    <strong>Tarjeta de Crédito/Débito</strong>
                    <p>Visa, MasterCard, American Express</p>
                </div>
                
                <div class="metodo-opcion" onclick="selectPayment('mercadopago')">
                    <input type="radio" id="mercadopago" name="metodo_pago" value="mercadopago" required>
                    <div>💰</div>
                    <strong>MercadoPago</strong>
                    <p>Pago seguro con MercadoPago</p>
                </div>
                
                <div class="metodo-opcion" onclick="selectPayment('transferencia')">
                    <input type="radio" id="transferencia" name="metodo_pago" value="transferencia" required>
                    <div>🏦</div>
                    <strong>Transferencia Bancaria</strong>
                    <p>Transferencia directa</p>
                </div>
            </div>
            
            <!-- Campos ocultos para enviar datos del producto -->
            <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
            <input type="hidden" name="cantidad" value="<?= $cantidad ?>">
            <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
            <input type="hidden" name="impuestos" value="<?= $impuestos ?>">
            <input type="hidden" name="total" value="<?= $total ?>">
            
            <div style="margin-top: 2rem;">
                <a href="producto_detalle.php?id=<?= $producto['id'] ?>" class="btn-volver">← Volver al Producto</a>
                <button type="submit" class="btn-finalizar"><a href="tarjeta.php"></a>Finalizar Compra 💳</button>
            </div>
        </form>
    </div>
    
    <script>
        function selectPayment(method) {
            // Remover selección anterior
            document.querySelectorAll('.metodo-opcion').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Seleccionar nuevo método
            document.getElementById(method).checked = true;
            document.getElementById(method).parentElement.classList.add('selected');
        }
    </script>

<?php include 'footer.php'; ?>