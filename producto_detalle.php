<?php
session_start();
require 'conexion.php';

// Verificar si se proporcionó un ID de producto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$producto_id = $_GET['id'];

// Obtener información del producto
$stmt = $pdo->prepare("SELECT p.*, c.nombre as categoria_nombre FROM productos p 
                       LEFT JOIN categorias c ON p.categoria_id = c.id 
                       WHERE p.id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: index.php');
    exit;
}

// Obtener productos relacionados de la misma categoría
$stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria_id = ? AND id != ? LIMIT 4");
$stmt->execute([$producto['categoria_id'], $producto_id]);
$productos_relacionados = $stmt->fetchAll();

$page_title = $producto['nombre'] . " - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        .producto-detalle {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .producto-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .imagen-principal {
            position: relative;
        }
        
        .imagen-principal img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        
        .sin-imagen-detalle {
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #666;
            border-radius: 12px;
            border: 2px dashed #ddd;
        }
        
        .detalles-producto {
            padding: 1rem 0;
        }
        
        .categoria-badge {
            background: #4a90e2;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .producto-titulo {
            font-size: 2.5rem;
            margin: 0 0 1rem 0;
            color: #2c3e50;
            font-weight: 700;
        }
        
        .origen-detalle {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .durabilidad-detalle {
            margin: 1.5rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .durabilidad-detalle .label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .barras-durabilidad {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .barra-durabilidad {
            width: 30px;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        .barra-durabilidad.activa {
            background: #4a90e2;
        }
        
        .descripcion-completa {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: white;
            border-left: 4px solid #4a90e2;
            border-radius: 0 8px 8px 0;
        }
        
        .precio-detalle {
            font-size: 2.5rem;
            font-weight: 700;
            color: #27ae60;
            margin: 2rem 0;
        }
        
        .acciones-compra {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-comprar-ahora {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-comprar-ahora:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
        }
        
        .btn-agregar-carrito {
            background: #4a90e2;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-agregar-carrito:hover {
            background: #357abd;
            transform: translateY(-2px);
        }
        
        .cantidad-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .cantidad-input {
            width: 80px;
            padding: 0.5rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-size: 1rem;
        }
        
        .productos-relacionados {
            margin-top: 4rem;
        }
        
        .productos-relacionados h3 {
            font-size: 1.8rem;
            margin-bottom: 2rem;
            color: #2c3e50;
        }
        
        .relacionados-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .producto-relacionado {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .producto-relacionado:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .producto-relacionado img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .volver-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #4a90e2;
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        
        .volver-link:hover {
            color: #357abd;
        }
        
        @media (max-width: 768px) {
            .producto-info {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .producto-titulo {
                font-size: 2rem;
            }
            
            .precio-detalle {
                font-size: 2rem;
            }
            
            .acciones-compra {
                flex-direction: column;
            }
            
            .relacionados-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="producto-detalle">
        <a href="javascript:history.back()" class="volver-link">
            ← Volver atrás
        </a>
        
        <div class="producto-info">
            <div class="imagen-principal">
                <?php if ($producto['imagen']): ?>
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <?php else: ?>
                    <div class="sin-imagen-detalle">Sin imagen disponible</div>
                <?php endif; ?>
            </div>
            
            <div class="detalles-producto">
                <span class="categoria-badge"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
                <h1 class="producto-titulo"><?= htmlspecialchars($producto['nombre']) ?></h1>
                <div class="origen-detalle">Origen: <?= htmlspecialchars($producto['origen']) ?></div>
                
                <div class="durabilidad-detalle">
                    <span class="label">Durabilidad:</span>
                    <div class="barras-durabilidad">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="barra-durabilidad <?= $i <= $producto['durabilidad'] ? 'activa' : '' ?>"></div>
                        <?php endfor; ?>
                        <span>(<?= $producto['durabilidad'] ?>/5)</span>
                    </div>
                </div>
                
                <div class="descripcion-completa">
                    <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
                </div>
                
                <div class="precio-detalle">
                    $<?= number_format($producto['precio'], 2, ',', '.') ?>
                </div>
                
                <div class="cantidad-selector">
                    <label for="cantidad"><strong>Cantidad:</strong></label>
                    <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="10" class="cantidad-input">
                </div>
                
                <div class="acciones-compra">
                    <form action="comprar_ahora.php" method="POST" style="display:inline;">
                        <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                        <input type="hidden" name="cantidad" id="cantidad-compra" value="1">
                        <button type="submit" class="btn-comprar-ahora">
                            💳 Comprar Ahora
                        </button>
                    </form>
                    
                    <form action="agregar_al_carrito.php" method="POST" style="display:inline;">
                        <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                        <input type="hidden" name="cantidad" id="cantidad-carrito" value="1">
                        <button type="submit" class="btn-agregar-carrito">
                            🛒 Agregar al Carrito
                        </button>
                    </form>
                </div>
                
                <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'jefe')): ?>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
                        <h4>Acciones de Administrador:</h4>
                        <button class="btn-editar" onclick="location.href='editar_producto.php?id=<?= $producto['id'] ?>'">
                            ✏️ Editar Producto
                        </button>
                        <form action="eliminar_producto.php" method="POST" style="display:inline; margin-left: 1rem;">
                            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                            <button type="submit" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($productos_relacionados)): ?>
            <div class="productos-relacionados">
                <h3>Productos Relacionados</h3>
                <div class="relacionados-grid">
                    <?php foreach ($productos_relacionados as $relacionado): ?>
                        <a href="producto_detalle.php?id=<?= $relacionado['id'] ?>" class="producto-relacionado">
                            <?php if ($relacionado['imagen']): ?>
                                <img src="<?= htmlspecialchars($relacionado['imagen']) ?>" alt="<?= htmlspecialchars($relacionado['nombre']) ?>">
                            <?php else: ?>
                                <div class="sin-imagen" style="height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-bottom: 1rem;">
                                    Sin imagen
                                </div>
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($relacionado['nombre']) ?></h4>
                            <p class="precio">$<?= number_format($relacionado['precio'], 2, ',', '.') ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Sincronizar cantidad entre ambos formularios
        document.getElementById('cantidad').addEventListener('change', function() {
            const cantidad = this.value;
            document.getElementById('cantidad-compra').value = cantidad;
            document.getElementById('cantidad-carrito').value = cantidad;
        });
    </script>

<?php include 'footer.php'; ?>