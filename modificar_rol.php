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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['new_rol_id'])) {
    $user_id = intval($_POST['user_id']);
    $new_rol_id = intval($_POST['new_rol_id']);

    // Evitar que el jefe logueado intente cambiar su propio rol desde esta interfaz
    if ($user_id === $_SESSION['user_id']) {
        $_SESSION['admin_message'] = "Error: No puedes cambiar tu propio rol directamente desde esta interfaz.";
        header('Location: admin_usuarios.php');
        exit;
    }

    try {
        // Opcional: Validar que el new_rol_id realmente existe en la tabla roles
        $stmt_check_rol_exists = $pdo->prepare("SELECT id FROM roles WHERE id = ?");
        $stmt_check_rol_exists->execute([$new_rol_id]);
        if (!$stmt_check_rol_exists->fetch()) {
            $_SESSION['admin_message'] = "Error: El rol seleccionado no es válido.";
            header('Location: admin_usuarios.php');
            exit;
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET rol_id = ? WHERE id = ?");
        if ($stmt->execute([$new_rol_id, $user_id])) {
            $_SESSION['admin_message'] = "Rol de usuario actualizado con éxito.";
        } else {
            $_SESSION['admin_message'] = "Error al actualizar el rol de usuario.";
        }
    } catch (PDOException $e) {
        $_SESSION['admin_message'] = "Error de base de datos: " . $e->getMessage();
    }
} else {
    $_SESSION['admin_message'] = "Petición inválida para modificar el rol.";
}

header('Location: admin_usuarios.php');
exit;
?>