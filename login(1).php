<?php
session_start();
require 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $mensaje = "Por favor, introduce tu email y contraseña.";
    } else {
        $stmt = $pdo->prepare("SELECT u.id, u.nombre_usuario, u.password, r.nombre AS rol_nombre
                               FROM usuarios u
                               JOIN roles r ON u.rol_id = r.id
                               WHERE u.email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol_nombre'];

            if ($_SESSION['rol'] === 'jefe') {
                header('Location: admin_usuarios.php');
            } elseif ($_SESSION['rol'] === 'administrador') {
                header('Location: agregar_producto.php');
            } else {
                $redirect_url = $_SESSION['redirect_after_login'] ?? 'index.php';
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirect_url);
            }
            exit;
        } else {
            $mensaje = "Email o contraseña incorrectos.";
        }
    }
}

$page_title = "Iniciar Sesión - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilos específicos del formulario de login */
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 3rem 1rem;
        }

        .form-card {
            background-color: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(34, 197, 94, 0.3);
            padding: 3rem;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            text-align: center;
        }

        h2 {
            color: #22c55e;
            font-size: 2.2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.8rem;
            text-align: left;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.6rem;
            color: #f1f5f9;
            font-size: 1.1rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            font-size: 1rem;
            border-radius: 12px;
            border: none;
            background-color: #1e293b;
            color: #f1f5f9;
            font-family: inherit;
            box-shadow: inset 0 0 6px rgba(0,0,0,0.6);
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #22c55e;
            color: #1e293b;
            font-weight: bold;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1.2rem;
            width: 100%;
            margin-top: 2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #16a34a;
        }

        .error {
            color: #f87171;
            margin-bottom: 1.5rem;
            font-weight: bold;
            background-color: rgba(248, 113, 129, 0.2);
            padding: 10px;
            border-radius: 8px;
        }

        .register-link {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: #f1f5f9;
        }

        .register-link a {
            color: #22c55e;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main>
        <div class="form-card">
            <h2>Iniciar Sesión</h2>
            <?php if ($mensaje): ?>
                <p class="error"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <input type="submit" value="Iniciar Sesión">
            </form>
            <p class="register-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </main>

<?php include 'footer.php'; ?>