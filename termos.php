<?php
session_start();
require 'conexion.php';

$categoria_id = 4; // Termos

$stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria_id = ?");
$stmt->execute([$categoria_id]);
$productos = $stmt->fetchAll();

$page_title = "Termos - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
</head>
<body>
    <section class="productos-container">
        <h2>Termos</h2>
        <div class="productos-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <div class="imagen-container">
                        <?php if ($producto['imagen']): ?>
                            <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <?php else: ?>
                            <div class="sin-imagen">Sin imagen</div>
                        <?php endif; ?>
                    </div>
                    <div class="brand-logo"><?= strtoupper(substr($producto['nombre'], 0, 1)) ?></div>
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
                    <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                    <div class="precio">$<?= number_format($producto['precio'], 2, ',', '.') ?></div>

                    <div class="acciones">
                        <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'jefe')): ?>
                            <button class="btn-editar" onclick="location.href='editar_producto.php?id=<?= $producto['id'] ?>'">Editar</button>
                        <?php endif; ?>

                        <form action="agregar_al_carrito.php" method="POST" style="display:inline;">
                            <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                            <button type="submit" class="btn-carrito">🛒</button>
                        </form>

                        <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'jefe')): ?>
                            <form action="eliminar_producto.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                <button type="submit" class="btn-eliminar">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

<?php include 'footer.php'; ?>