<?php
$base_url = '/proyecto2';
require_once '../../api/config/database.php';
require_once '../../api/models/Product.php';
require_once '../../api/utils/helpers.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);

if(isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product->read_single();
} else {
    header("Location: ../index.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->name) ?> - E-commerce</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <main class="container">
        <div class="product-detail">
            <h1><?= htmlspecialchars($product->name) ?></h1>
            <p class="category">Categoría: <?= htmlspecialchars($product->category_name) ?></p>
            <p class="price">$<?= number_format($product->price, 2) ?></p>
            <div class="description">
                <h3>Descripción:</h3>
                <p><?= nl2br(htmlspecialchars($product->description)) ?></p>
            </div>
            <a href="product_sale.php" class="btn">Agregar al carrito</a>
                <a href="../index.php" class="btn cancel">Volver</a>
            </div>
        </div>
    </main>

    <?php include '../partials/footer.php'; ?>
<script src="../../assets/js/scripts.js"></script>
</body>
</html>