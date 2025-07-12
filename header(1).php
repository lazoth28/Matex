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
        }

        .logo img {
            height: 40px;
            width: auto;
            border-radius: 8px; /* Si tu logo tiene bordes, si es png transparente no afecta */
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
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
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="img-MATEX.png" alt="Logo Matex" />
                Matex
            </div>
            <ul class="nav-links">
                <li><a href="index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">Inicio</a></li>
                <li><a href="mates.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'mates.php') ? 'active' : '' ?>">Mates</a></li>
                <li><a href="yerbas.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'yerbas.php') ? 'active' : '' ?>">Yerbas</a></li>
                <li><a href="bombillas.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'bombillas.php') ? 'active' : '' ?>">Bombillas</a></li>
                <li><a href="termos.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'termos.php') ? 'active' : '' ?>">Termos</a></li>
                <li><a href="accesorios.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'accesorios.php') ? 'active' : '' ?>">Accesorios</a></li>
                
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
                    <li><a href="login.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : '' ?>">Login</a></li>
                    <li><a href="registro.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'registro.php') ? 'active' : '' ?>">Registro</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main> 


</body>
</html>