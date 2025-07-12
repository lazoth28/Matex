<?php
// Siempre iniciar sesión al principio de cualquier archivo que la necesite
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* Estilos específicos del header que pueden ser sobrescritos o movidos a styles.css si son comunes */
        header {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            padding: 1.2rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            gap: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            font-size: 1.8rem;
            background: linear-gradient(45deg, #22c55e, #16a34a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            cursor: pointer;
            flex-shrink: 0;
        }

        .logo img {
            height: 40px;
            width: auto;
            border-radius: 8px;
        }

        /* Estilos del buscador */
        .search-container {
            display: flex;
            align-items: center;
            flex-grow: 1;
            max-width: 400px;
            margin: 0 2rem;
        }

        .search-box {
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 0.8rem 1rem;
            padding-right: 3rem;
            border: 2px solid #374151;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .search-input:focus {
            border-color: #22c55e;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: #22c55e;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: #16a34a;
            transform: translateY(-50%) scale(1.1);
        }

        .search-btn svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

        /* Dropdown de categorías */
        .category-dropdown {
            position: relative;
            margin-right: 1rem;
        }

        .category-btn {
            background: linear-gradient(45deg, #22c55e, #16a34a);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .category-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .category-dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #374151;
            border-radius: 10px;
            min-width: 200px;
            z-index: 1001;
            margin-top: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .category-dropdown-content.show {
            display: block;
        }

        .category-dropdown-content a {
            color: #ffffff;
            padding: 0.8rem 1.2rem;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
        }

        .category-dropdown-content a:hover {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
            flex-shrink: 0;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            padding: 0.5rem 0;
            position: relative;
        }

        .nav-links a:hover {
            color: #22c55e;
            transform: translateY(-2px);
        }

        .nav-links a.active {
            color: #22c55e;
            font-weight: bold;
        }

        /* Estilos para el usuario logueado en el nav */
        .user-info {
            color: #f1f5f9;
            font-size: 1.1rem;
            margin-right: 1rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 1rem;
                padding: 0 1rem;
            }

            .search-container {
                margin: 0;
                max-width: none;
                width: 100%;
            }

            .nav-links {
                gap: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .category-dropdown {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="img-MATEX.png" alt="Logo Matex" />
                Matex
            </div>

            <!-- Dropdown de categorías -->
            <div class="category-dropdown">
                <button class="category-btn" onclick="toggleDropdown()">
                    Categorías
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
                        <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="category-dropdown-content" id="categoryDropdown">
                    <a href="mates.php">🧉 Mates</a>
                    <a href="yerbas.php">🌿 Yerbas</a>
                    <a href="bombillas.php">🥤 Bombillas</a>
                    <a href="termos.php">🍶 Termos</a>
                    <a href="accesorios.php">🎯 Accesorios</a>
                </div>
            </div>


            <ul class="nav-links">
                <li><a href="index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">Inicio</a></li>
                
                <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'jefe')): ?>
                    <li><a href="agregar_producto.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'agregar_producto.php') ? 'active' : '' ?>">Agregar Producto</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'jefe'): ?>
                    <li><a href="admin_usuarios.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'admin_usuarios.php') ? 'active' : '' ?>">Admin Usuarios</a></li>
                <?php endif; ?>

                <li><a href="carrito.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'carrito.php') ? 'active' : '' ?>">Carrito 🛒</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="user-info">Hola, <?= htmlspecialchars($_SESSION['username']) ?>!</li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : '' ?>">Inicio</a></li>
                    <li><a href="registro.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'registro.php') ? 'active' : '' ?>">Registro</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('categoryDropdown');
            dropdown.classList.toggle('show');
        }

        // Cerrar dropdown cuando se hace clic fuera
        window.onclick = function(event) {
            if (!event.target.matches('.category-btn')) {
                const dropdown = document.getElementById('categoryDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        // Mejorar UX del buscador
        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    </script>

    <main> 

</body>
</html>