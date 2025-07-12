<?php
session_start();
require 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nombre_usuario) || empty($email) || empty($password) || empty($confirm_password)) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Formato de email inválido.";
    } elseif ($password !== $confirm_password) {
        $mensaje = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? OR email = ?");
        $stmt_check->execute([$nombre_usuario, $email]);
        if ($stmt_check->fetch()) {
            $mensaje = "El nombre de usuario o email ya está registrado.";
        } else {
            $stmt_rol = $pdo->prepare("SELECT id FROM roles WHERE nombre = 'comprador'");
            $stmt_rol->execute();
            $rol_comprador_id = $stmt_rol->fetchColumn();

            if ($rol_comprador_id) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt_insert = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, email, password, rol_id) VALUES (?, ?, ?, ?)");
                if ($stmt_insert->execute([$nombre_usuario, $email, $hashed_password, $rol_comprador_id])) {
                    $mensaje = "¡Registro exitoso! Ahora puedes iniciar sesión.";
                } else {
                    $mensaje = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
                }
            } else {
                $mensaje = "Error: El rol 'comprador' no se encontró en la base de datos. Contacta al administrador.";
            }
        }
    }
}

$page_title = "Registro - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilos específicos del formulario de registro */
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

        input[type="text"],
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

        .success {
            color: #22c55e;
            margin-bottom: 1.5rem;
            font-weight: bold;
            background-color: rgba(34, 197, 94, 0.2);
            padding: 10px;
            border-radius: 8px;
        }

        .login-link {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: #f1f5f9;
        }

        .login-link a {
            color: #22c55e;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main>
        <div class="form-card">
            <h2>Crear una Cuenta</h2>
            <?php if ($mensaje): ?>
                <p class="<?= (strpos($mensaje, 'Error') !== false || strpos($mensaje, 'inválido') !== false || strpos($mensaje, 'no coinciden') !== false || strpos($mensaje, 'obligatorios') !== false || strpos($mensaje, 'registrado') !== false) ? 'error' : 'success' ?>"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>
            <form action="registro.php" method="POST">
                <div class="form-group">
                    <label for="nombre_usuario">Nombre de Usuario:</label>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" required value="<?= isset($_POST['nombre_usuario']) ? htmlspecialchars($_POST['nombre_usuario']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <input type="submit" value="Registrarse">
            </form>
            <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia Sesión aquí</a></p>
        </div>
    </main>

<?php include 'footer.php'; ?>