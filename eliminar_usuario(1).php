<?php
require 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteger esta página: Solo el rol 'jefe' puede ejecutar esto
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'jefe') {
    $_SESSION['admin_message'] = "Acceso denegado: No tienes permisos para realizar esta acción.";
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // No permitir que el jefe logueado se elimine a sí mismo
    if ($user_id === $_SESSION['user_id']) {
        $_SESSION['admin_message'] = "Error: No puedes eliminar tu propia cuenta.";
        header('Location: admin_usuarios.php');
        exit;
    }

    try {
        // Obtener el rol del usuario que se va a eliminar
        $stmt_check_rol = $pdo->prepare("SELECT r.nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id WHERE u.id = ?");
        $stmt_check_rol->execute([$user_id]);
        $target_user_rol = $stmt_check_rol->fetchColumn();

        // Evitar que un jefe elimine a otro jefe sin una validación más compleja (por ejemplo, si debe haber al menos 1 jefe)
        if ($target_user_rol === 'jefe') {
             $_SESSION['admin_message'] = "Error: No puedes eliminar otra cuenta de jefe directamente.";
             header('Location: admin_usuarios.php');
             exit;
        }

        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            $_SESSION['admin_message'] = "Usuario eliminado con éxito.";
        } else {
            $_SESSION['admin_message'] = "Error al eliminar el usuario.";
        }
    } catch (PDOException $e) {
        $_SESSION['admin_message'] = "Error de base de datos: " . $e->getMessage();
    }
} else {
    $_SESSION['admin_message'] = "Petición inválida para eliminar usuario.";
}

header('Location: admin_usuarios.php');
exit;
?>