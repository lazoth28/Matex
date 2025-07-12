<?php
require 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$stmt = $pdo->prepare("SELECT * FROM productos WHERE destacado = 1 ORDER BY categoria_id, id DESC");
$stmt->execute();
$productos = $stmt->fetchAll();

$categorias = [
    1 => 'Mates',
    2 => 'Yerbas',
    3 => 'Bombillas',
    4 => 'Termos',
    5 => 'Accesorios',
];

$agrupados = [];
foreach ($productos as $prod) {
    $agrupados[$prod['categoria_id']][] = $prod;
}

$page_title = "Inicio - Matex";
include 'header.php'; // Incluye el header
?>
    <title><?= $page_title ?></title>
</head>
<body>
    <section class="hero-section" style="text-align:center; padding: 2rem;">
        <h2>¡Capo, bienvenido a MATEX! 🧉</h2>
        <br>
        <p style="text-align: center;">
            <h3>Estamos re agradecidos y nos alegra un montón que seas parte de nuestra comunidad matera.
            Acá vas a encontrar todo lo necesario para que tengas una excelente cebada: desde los mejores mates artesanales y nuestra selecta colección de yerbas 🌿, hasta bombillas, termos y accesorios top que van a hacer que tus mates sean los mejores de todos.
            ¡Así que preparate la pava, hacete tu montañita y arrancá una buena cebada de mate con MATEX!
        </p></h3>
    </section>

    <?php foreach ($categorias as $id => $nombreCategoria): ?>
        <?php if (!empty($agrupados[$id])): ?>
            <section class="productos-container">
                <h2><?= htmlspecialchars($nombreCategoria) ?> destacados</h2>
                <div class="productos-grid">
                    <?php foreach ($agrupados[$id] as $producto): ?>
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
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>

<?php include 'footer.php'; ?>