<?php
session_start();
require 'conexion.php';

// Redirigir al login si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'carrito.php';
    header('Location: login.php');
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_unidad'])) {
        $id = (int)$_POST['producto_id'];
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]--;
            if ($_SESSION['carrito'][$id] <= 0) {
                unset($_SESSION['carrito'][$id]);
            }
        }
        header('Location: carrito.php');
        exit;
    }

    if (isset($_POST['eliminar_producto'])) {
        $id = (int)$_POST['producto_id'];
        unset($_SESSION['carrito'][$id]);
        header('Location: carrito.php');
        exit;
    }
}

$page_title = "Carrito - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilos específicos del carrito, si no están ya en styles.css */
        .carrito-container {
            max-width: 900px;
            margin: 2rem auto 4rem;
            padding: 2rem;
            background: rgba(30, 41, 59, 0.9);
            border-radius: 15px;
            color: #f1f5f9;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
        }

        th {
            background-color: #22c55e;
            color: #1e293b;
        }

        tbody tr {
            background-color: #1e293b;
            border-bottom: 2px solid white;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        .btn-eliminar, .btn-eliminar-unidad {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            color: white;
            background-color: #dc2626;
            margin: 0 3px;
            transition: background-color 0.3s ease;
        }

        .btn-eliminar:hover, .btn-eliminar-unidad:hover {
            background-color: #b91c1c;
        }

        .payment-section {
            margin-top: 2rem;
            padding: 2rem;
            background: rgba(34, 197, 94, 0.1);
            border-radius: 15px;
            border: 2px solid #22c55e;
            text-align: center;
        }

        .payment-section h3 {
            color: #22c55e;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .total-payment {
            font-size: 2rem;
            font-weight: bold;
            color: #22c55e;
            margin-bottom: 1.5rem;
        }

        .btn-pagar {
            background: linear-gradient(45deg, #22c55e, #16a34a);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
        }

        .btn-pagar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
            background: linear-gradient(45deg, #16a34a, #15803d);
        }

        .btn-pagar:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .carrito-container {
                margin: 2rem 1rem;
            }
            .total-payment {
                font-size: 1.5rem;
            }
            .btn-pagar {
                font-size: 1rem;
                padding: 0.8rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <section class="carrito-container">
        <h2>Tu carrito de compras</h2>
        <?php if (empty($carrito)): ?>
            <p>Tu carrito está vacío.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalGeneral = 0;
                    foreach ($carrito as $producto_id => $cantidad): 
                        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
                        $stmt->execute([$producto_id]);
                        $producto = $stmt->fetch();
                        if (!$producto) continue;
                        $totalProducto = $producto['precio'] * $cantidad;
                        $totalGeneral += $totalProducto;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= $cantidad ?></td>
                        <td>$<?= number_format($producto['precio'], 2, ',', '.') ?></td>
                        <td>$<?= number_format($totalProducto, 2, ',', '.') ?></td>
                        <td style="display: flex; justify-content: center;">
                            <form action="carrito.php" method="POST" style="display:inline;">
                                <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
                                <button type="submit" name="eliminar_unidad" class="btn-eliminar-unidad" title="Eliminar una unidad">-1</button>
                            </form>
                            <form action="carrito.php" method="POST" style="display:inline;">
                                <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
                                <button type="submit" name="eliminar_producto" class="btn-eliminar" title="Eliminar producto completo">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:bold;">Total General:</td>
                        <td colspan="2" style="font-weight:bold;">$<?= number_format($totalGeneral, 2, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="payment-section">
                <h3>🛒 Finalizar Compra</h3>
                <div class="total-payment">
                    Total a pagar: $<?= number_format($totalGeneral, 2, ',', '.') ?>
                </div>
                <a href="mp.php?total=<?= $totalGeneral ?>" class="btn-pagar">
                    💳 Pagar con MercadoPago
                </a>
            </div>
        <?php endif; ?>
    </section>

<?php include 'footer.php'; ?>