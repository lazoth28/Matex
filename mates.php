<?php
require 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$categoria_id = 1; // Mates

$stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria_id = ?");
$stmt->execute([$categoria_id]);
$productos = $stmt->fetchAll();

$page_title = "Mates - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilo para productos clickeables - COMPLETO del index.php */
        .producto-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 400px; /* Altura fija más pequeña */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .producto-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        .producto-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .producto-link:hover {
            text-decoration: none;
            color: inherit;
        }
        
        /* Imagen más pequeña */
        .imagen-container {
            width: 100%;
            height: 300px;
            overflow: hidden;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #232F41;
        }
        
        .imagen-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .sin-imagen {
            color: #6c757d;
            font-size: 0.9rem;
            text-align: center;
        }
        
        /* Información del producto simplificada */
        .producto-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .producto-card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
            color: #2c3e50;
            line-height: 1.2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .origen {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            font-style: italic;
        }
        
        /* Durabilidad simplificada */
        .intensidad {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .intensidad-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .intensidad-barras {
            display: flex;
            gap: 2px;
        }
        
        .barra {
            width: 12px;
            height: 4px;
            background-color: #232F41;
            border-radius: 2px;
        }
        
        .barra.activa {
            background-color: #28a745;
        }
        
        /* Precio destacado */
        .precio {
            font-size: 1.1rem;
            font-weight: 700;
            color: #27ae60;
            text-align: center;
            margin-top: auto;
            padding-top: 0.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        /* Grid responsive ajustado */
        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        /* Estilos para acciones */
        .acciones {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            z-index: 10;
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .producto-card:hover .acciones {
            opacity: 1;
        }
        
        .acciones form {
            display: inline-block;
        }
        
        .acciones button {
            position: relative;
            z-index: 11;
            padding: 0.25rem 0.5rem;
            border: none;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
        }
        
        .btn-editar {
            background-color: #007bff;
            color: white;
        }
        
        .btn-carrito {
            background-color: #28a745;
            color: white;
            font-size: 1rem;
        }
        
        .btn-eliminar {
            background-color: #dc3545;
            color: white;
        }
        
        .acciones button:hover {
            transform: none;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .productos-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 1rem;
            }
            
            .producto-card {
                height: 260px;
            }
            
            .imagen-container {
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <section class="productos-container">
        <h2>Mates</h2>
        <div class="productos-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <a href="producto_detalle.php?id=<?= $producto['id'] ?>" class="producto-link">
                        <div class="imagen-container">
                            <?php if ($producto['imagen']): ?>
                                <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            <?php else: ?>
                                <div class="sin-imagen">Sin imagen</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="producto-info">
                            <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                            <div class="origen"><?= htmlspecialchars($producto['origen']) ?></div>
                            
                            <div class="intensidad">
                                <span class="intensidad-label">Durabilidad:</span>
                                <div class="intensidad-barras">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="barra <?= $i <= $producto['durabilidad'] ? 'activa' : '' ?>"></div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="precio">$<?= number_format($producto['precio'], 2, ',', '.') ?></div>
                        </div>
                    </a>

                    <div class="acciones">
                        <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'jefe')): ?>
                            <button class="btn-editar" onclick="event.stopPropagation(); location.href='editar_producto.php?id=<?= $producto['id'] ?>'">Editar</button>
                        <?php endif; ?>

                        <form action="agregar_al_carrito.php" method="POST" style="display:inline;" onsubmit="event.stopPropagation();">
                            <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                            <button type="submit" class="btn-carrito" onclick="event.stopPropagation();">🛒</button>
                        </form>

                        <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'jefe')): ?>
                            <form action="eliminar_producto.php" method="POST" style="display:inline;" onsubmit="event.stopPropagation();">
                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                <button type="submit" class="btn-eliminar" onclick="event.stopPropagation(); return confirm('¿Estás seguro de que quieres eliminar este producto?')">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

<?php include 'footer.php'; ?>