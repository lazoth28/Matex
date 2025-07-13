<?php
require 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteger esta página: Solo el rol 'jefe' puede acceder
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'jefe') {
    header('Location: index.php'); // Redirigir si no tiene permisos de jefe
    exit;
}

$mensaje = '';

// Si hay un mensaje almacenado en la sesión (por ejemplo, desde modificar_rol.php o eliminar_usuario.php)
if (isset($_SESSION['admin_message'])) {
    $mensaje = $_SESSION['admin_message'];
    unset($_SESSION['admin_message']); // Limpiar el mensaje después de mostrarlo
}

// Obtener todos los usuarios y sus roles
try {
    $stmt_usuarios = $pdo->query("SELECT u.id, u.nombre_usuario, u.email, r.nombre AS rol_nombre 
                                   FROM usuarios u
                                   JOIN roles r ON u.rol_id = r.id
                                   ORDER BY u.nombre_usuario");
    $usuarios = $stmt_usuarios->fetchAll();

    // Obtener todos los roles disponibles
    $stmt_roles = $pdo->query("SELECT id, nombre FROM roles");
    $roles_disponibles = $stmt_roles->fetchAll(PDO::FETCH_KEY_PAIR); // Para usar id => nombre
} catch (PDOException $e) {
    $mensaje = "Error al cargar datos: " . $e->getMessage();
    $usuarios = [];
    $roles_disponibles = [];
}

$page_title = "Administrar Usuarios - Matex";
include 'header.php'; // Incluye el header
?>
    <title><?= $page_title ?></title>
    <style>
        .admin-container {
            max-width: 1000px;
            margin: 2rem auto 4rem;
            padding: 2rem;
            background: rgba(30, 41, 59, 0.9);
            border-radius: 15px;
            color: #f1f5f9;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .admin-container h2 {
            color: #22c55e;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.2rem;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .admin-table th, .admin-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-table th {
            background-color: #22c55e;
            color: #1e293b;
            font-weight: bold;
        }

        .admin-table tbody tr {
            background-color: #1e293b;
        }

        .admin-table tbody tr:nth-child(even) {
            background-color: #1a2434; /* Ligeramente diferente para filas pares */
        }

        .admin-table tbody tr:hover {
            background-color: #2b3a50;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap; /* Para que los botones se envuelvan en pantallas pequeñas */
        }
        
        .action-buttons form {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-modificar-rol {
            background-color: #2563eb;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .btn-modificar-rol:hover {
            background-color: #3b82f6;
        }
        
        .btn-eliminar-usuario {
            background-color: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .btn-eliminar-usuario:hover {
            background-color: #f87171;
        }

        .admin-table select {
            padding: 0.5rem;
            border-radius: 5px;
            border: 1px solid #334155;
            background-color: #1e293b;
            color: #f1f5f9;
            font-size: 0.9rem;
        }

        .admin-message {
            color: #f87171;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: bold;
            background-color: rgba(248, 113, 129, 0.2);
            padding: 10px;
            border-radius: 8px;
        }
        .admin-success {
            color: #22c55e;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: bold;
            background-color: rgba(34, 197, 94, 0.2);
            padding: 10px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .admin-container {
                margin: 1rem;
                padding: 1rem;
            }
            .admin-table th, .admin-table td {
                font-size: 0.8rem;
                padding: 8px;
            }
            .admin-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            .btn-modificar-rol, .btn-eliminar-usuario {
                padding: 0.4rem 0.7rem;
                font-size: 0.8rem;
            }
            .action-buttons form {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            .admin-table select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <section class="admin-container">
        <h2>Administrar Usuarios</h2>
        <?php if ($mensaje): ?>
            <p class="<?= strpos($mensaje, 'Error') !== false ? 'admin-message' : 'admin-success' ?>"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Rol Actual</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['rol_nombre']) ?></td>
                        <td class="action-buttons">
                            <form action="modificar_rol.php" method="POST">
                                <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                                <select name="new_rol_id">
                                    <?php foreach ($roles_disponibles as $id_rol => $nombre_rol): ?>
                                        <option value="<?= $id_rol ?>" <?= ($usuario['rol_nombre'] == $nombre_rol) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($nombre_rol) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-modificar-rol">Cambiar Rol</button>
                            </form>
                            <?php 
                            // No permitir que el propio jefe logueado se elimine a sí mismo
                            // No permitir que un jefe elimine a otro jefe sin una validación más compleja
                            if ($usuario['id'] !== $_SESSION['user_id'] && $usuario['rol_nombre'] !== 'Jefe'): 
                            ?>
                                <form action="eliminar_usuario.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este usuario (<?= htmlspecialchars($usuario['nombre_usuario']) ?>)? Esta acción es irreversible.');">
                                    <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                                    <button type="submit" class="btn-eliminar-usuario">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

<?php include 'footer.php'; ?>