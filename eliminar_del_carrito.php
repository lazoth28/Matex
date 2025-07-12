<?php
session_start();

if (isset($_POST['producto_id']) && isset($_SESSION['carrito'][$_POST['producto_id']])) {
    $producto_id = $_POST['producto_id'];

    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_unidad') {
        // Disminuir cantidad en 1
        $_SESSION['carrito'][$producto_id]--;

        if ($_SESSION['carrito'][$producto_id] <= 0) {
            unset($_SESSION['carrito'][$producto_id]);
        }
    } else {
        // Eliminar producto completo
        unset($_SESSION['carrito'][$producto_id]);
    }
}

header('Location: carrito.php');
exit;
?>
