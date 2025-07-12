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

if (!isset($_GET['id'])) {
    die("ID de producto no especificado.");
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    die("Producto no encontrado.");
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $categoria_id = intval($_POST['categoria_id']);
    $gramos = isset($_POST['gramos']) ? $_POST['gramos'] : null;
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $origen = $_POST['origen'];
    $durabilidad = intval($_POST['durabilidad']);

    if ($mensaje === '') {
        $imagen = '';
        if (!empty($_POST['url_imagen'])) {
            $urlImagen = trim($_POST['url_imagen']);
            if (filter_var($urlImagen, FILTER_VALIDATE_URL)) {
                $imagen = $urlImagen;
            } else {
                $mensaje = "La URL de la imagen no es válida.";
            }
        } else {
            $imagen = $producto['imagen'];
        }

        if ($mensaje === '') {
            $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria_id = ?, gramos = ?, imagen = ?, destacado = ?, origen = ?, durabilidad = ? WHERE id = ?");
            if ($stmt->execute([$nombre, $descripcion, $precio, $categoria_id, $gramos, $imagen, $destacado, $origen, $durabilidad, $id])) {
                $redirect = match ($categoria_id) {
                    1 => 'mates.php',
                    2 => 'yerbas.php',
                    3 => 'bombillas.php',
                    4 => 'termos.php',
                    5 => 'accesorios.php',
                    default => 'index.php'
                };
                header("Location: $redirect");
                exit;
            } else {
                $mensaje = "Error al actualizar el producto.";
            }
        }
    }
}

$page_title = "Editar Producto - Matex";
include 'header.php';
?>
    <title><?= $page_title ?></title>
    <style>
        /* Estilos específicos para editar_producto.php */
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 1rem;
        }

        .form-card {
            background-color: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(34, 197, 94, 0.3);
            padding: 3rem;
            border-radius: 20px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
        }

        h2 {
            text-align: center;
            color: #22c55e;
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.8rem;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 0.6rem;
            color: #f1f5f9;
            font-size: 1.1rem;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            padding: 14px 18px;
            font-size: 1rem;
            border-radius: 12px;
            border: none;
            background-color: #1e293b;
            color: #f1f5f9;
            font-family: inherit;
            box-shadow: inset 0 0 6px rgba(0,0,0,0.6);
        }

        input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 600;
        }

        #gramos-container {
            display: <?= $producto['categoria_id'] == 2 ? 'flex' : 'none' ?>;
            flex-direction: column;
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
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<main>
    <div class="form-card">
        <h2>Editar Producto</h2>
        <?php if ($mensaje): ?>
            <p class="error"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>
        <form action="editar_producto.php?id=<?= $id ?>" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required value="<?= htmlspecialchars($producto['nombre']) ?>">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" min="0" name="precio" id="precio" required value="<?= htmlspecialchars($producto['precio']) ?>">
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría:</label>
                <select name="categoria_id" id="categoria_id" required>
                    <option value="1" <?= $producto['categoria_id'] == 1 ? 'selected' : '' ?>>Mates</option>
                    <option value="2" <?= $producto['categoria_id'] == 2 ? 'selected' : '' ?>>Yerbas</option>
                    <option value="3" <?= $producto['categoria_id'] == 3 ? 'selected' : '' ?>>Bombillas</option>
                    <option value="4" <?= $producto['categoria_id'] == 4 ? 'selected' : '' ?>>Termos</option>
                    <option value="5" <?= $producto['categoria_id'] == 5 ? 'selected' : '' ?>>Accesorios</option>
                </select>
            </div>
            <div class="form-group">
                <label for="origen">Origen:</label>
                <input type="text" name="origen" id="origen" required value="<?= htmlspecialchars($producto['origen']) ?>">
            </div>
            <div class="form-group">
                <label for="durabilidad">Durabilidad (1-5):</label>
                <input type="number" name="durabilidad" id="durabilidad" min="1" max="5" required value="<?= htmlspecialchars($producto['durabilidad']) ?>">
            </div>
            <div class="form-group" id="gramos-container">
                <label for="gramos">Gramos o kilos (solo yerbas):</label>
                <input type="text" name="gramos" id="gramos" value="<?= htmlspecialchars($producto['gramos'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="url_imagen">URL de la imagen:</label>
                <input type="text" name="url_imagen" id="url_imagen" value="<?= htmlspecialchars($producto['imagen']) ?>">
            </div>
            <div class="form-group checkbox-label">
                <input type="checkbox" name="destacado" id="destacado" <?= $producto['destacado'] ? 'checked' : '' ?>>
                <label for="destacado">Producto destacado</label>
            </div>
            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
</main>

<script>
    function toggleGramos() {
        const categoria = document.getElementById('categoria_id').value;
        const gramosContainer = document.getElementById('gramos-container');
        if (categoria == 2) {
            gramosContainer.style.display = 'flex';
        } else {
            gramosContainer.style.display = 'none';
            document.getElementById('gramos').value = '';
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('categoria_id').addEventListener('change', toggleGramos);
        toggleGramos(); // Para establecer el estado inicial
    });
</script>

<?php include 'footer.php'; ?>