<?php
require 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteger esta página: Solo administrador y jefe
if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'administrador' && $_SESSION['rol'] !== 'jefe')) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $producto_id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT categoria_id FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch();

    if ($producto) {
        $categoria_id = $producto['categoria_id'];

        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);

        switch ($categoria_id) {
            case 1:
                header("Location: mates.php");
                break;
            case 2:
                header("Location: yerbas.php");
                break;
            case 3:
                header("Location: bombillas.php");
                break;
            case 4:
                header("Location: termos.php");
                break;
            case 5:
                header("Location: accesorios.php");
                break;
            default:
                header("Location: index.php");
        }
        exit;
    } else {
        echo "Producto no encontrado.";
    }
} else {
    echo "ID de producto no especificado o método de solicitud inválido.";
}
// Este script no necesita header/footer ya que solo redirige.
?>