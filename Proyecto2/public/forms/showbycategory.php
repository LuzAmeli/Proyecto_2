<?php
session_start();
require_once '../../api/utils/helpers.php';
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "ecommerce");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el ID de la categoría desde la URL
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;

// Obtener nombre de la categoría
$categoria_nombre = "";
$stmt = $conexion->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $categoria_id);
$stmt->execute();
$stmt->bind_result($categoria_nombre);
$stmt->fetch();
$stmt->close();

// Obtener productos de esa categoría
$productos = [];
$stmt = $conexion->prepare("SELECT name, description, price FROM products WHERE category_id = ?");
$stmt->bind_param("i", $categoria_id);
$stmt->execute();
$productos = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - <?= htmlspecialchars($categoria_nombre) ?></title>   
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>

<?php include '../partials/header.php'; ?>

<main class="container">
<h1>Productos en categoría: <?= htmlspecialchars($categoria_nombre) ?></h1>

<?php if ($productos->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($prod = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($prod['name']) ?></td>
                    <td><?= htmlspecialchars($prod['description']) ?></td>
                    <td>$<?= number_format($prod['price'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
<?php else: ?>
    <p>No hay productos en esta categoría.</p>
<?php endif; ?>

    <?php include '../partials/footer.php'; ?>
</body>
</html>