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
    <style>
        .carousel-container {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 0px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .carousel-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0%;
            transition: opacity 0.5s ease-in-out;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        
        .carousel-slide.active {
            opacity: 5;
        }
        
        .carousel-slide h2 {
            font-size: 2.5rem;
            margin: 0;
            text-align: center;
            background: rgba(0,0,0,0.5);
            padding: 1rem 2rem;
            border-radius: 10px;
        }
        
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            padding: 1rem;
            cursor: pointer;
            font-size: 1.5rem;
            border-radius: 50%;
            transition: background 0.3s ease;
        }
        
        .carousel-nav:hover {
            background: rgba(0,0,0,0.7);
        }
        
        .carousel-nav.prev {
            left: 5px;
        }
        
        .carousel-nav.next {
            right: 5px;
        }
        
        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }
        
        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .carousel-indicator.active {
            background: white;
        }
        
        @media (max-width: 768px) {
            .carousel-container {
                height: 300px;
            }
            
            .carousel-slide h2 {
                font-size: 1.8rem;
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Carrusel -->
    <section class="carousel-container">
        <div class="carousel-slide active" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://imgs.search.brave.com/XVPH4jNWGkMdne2TIwNK-5yReQwO5_X8RENUBkY6PuU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tYXRl/c2Zsb3JpZGEuY29t/L2Nkbi9zaG9wL2Zp/bGVzL01hdGVzRmxv/cmlkYS03MTEuanBn/P3Y9MTcyMzQ5ODc2/MyZ3aWR0aD01MzM');">
            <h2>Los Mejores Mates Artesanales 🧉</h2>
        </div>
        
        <div class="carousel-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://imgs.search.brave.com/SjfOcJWgjA52JuwLFsua-BmfDV4xJVLBxT_uwCcPFZI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbnlt/Lm9yZy5hci9pbWFn/ZW5lcy9hcmNoaXZv/cy9ub3RpY2lhcy84/MDYwNl9pbWFnZW5f/MTA4OHg2NTB4cmVj/b3J0YXJ4YWdyYW5k/YXIuanBnP3JhbmRv/bT0xNzQzMTczODk3');">
            <h2>Yerbas Premium Seleccionadas 🌿</h2>
        </div>
        
        <div class="carousel-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80');">
            <h2>Accesorios para el Mate Perfecto ✨</h2>
        </div>
        
        <button class="carousel-nav prev" onclick="changeSlide(-1)">❮</button>
        <button class="carousel-nav next" onclick="changeSlide(1)">❯</button>
        
        <div class="carousel-indicators">
            <span class="carousel-indicator active" onclick="goToSlide(0)"></span>
            <span class="carousel-indicator" onclick="goToSlide(1)"></span>
            <span class="carousel-indicator" onclick="goToSlide(2)"></span>
        </div>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const totalSlides = slides.length;
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));
            
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
        }
        
        function changeSlide(direction) {
            currentSlide += direction;
            
            if (currentSlide >= totalSlides) {
                currentSlide = 0;
            } else if (currentSlide < 0) {
                currentSlide = totalSlides - 1;
            }
            
            showSlide(currentSlide);
        }
        
        function goToSlide(index) {
            currentSlide = index;
            showSlide(currentSlide);
        }
        
        // Auto-advance carousel every 5 seconds
        setInterval(() => {
            changeSlide(1);
        }, 5000);
    </script>

    <section class="hero-section" style="text-align:center; padding: 2rem;">
        <h2>¡Bienvenido a MATEX! 🧉</h2>
        <br>
        <p style="text-align: center;">
            <h3>Estamos re agradecidos y contentos de que seas parte de nuestra familia matera.
            Acá vas a encontrar todo lo necesario para que tengas una excelente cebada, desde una variedad aplia de mates artesanales, nuestra selecta colección de yerbas internacionales🌿, bombillas, termos y accesorios premium que van a hacer que tus mates sean los mejores de todos.
            ¡Así que prepara la pava, armate la montañita y termina cebando tu MATEX!
        </p></h3>
    </section>

    <?php foreach ($categorias as $id => $nombreCategoria): ?>
        <?php if (!empty($agrupados[$id])): ?>
            <section class="productos-container">
                <h2><?= htmlspecialchars($nombreCategoria) ?> Destacados</h2>
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