<?php

$base_url = '/proyecto2'; 

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if(isset($_SESSION['success_message'])) {
    echo '<div class="alert success">'.$_SESSION['success_message'].'</div>';
    unset($_SESSION['success_message']);
}

if(isset($_SESSION['error_message'])) {
    echo '<div class="alert error">'.$_SESSION['error_message'].'</div>';
    unset($_SESSION['error_message']);
}
require_once '../../api/utils/helpers.php';
require_once '../../api/config/database.php';
require_once '../../api/models/Product.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$products = $product->read();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos - E-commerce</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>
    <main class="container">
        <h1>Lista de Productos</h1>
        
        <div class="product-grid">
            <?php while($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p class="price">$<?= number_format($row['price'], 2) ?></p>
                    <div class="product-actions">
                        <a href="product_edit.php?id=<?= $row['id'] ?>" class="btn">Editar</a>
                        <form action="product_delete.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn delete">Eliminar</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="action-buttons">
            <a href="product_create.php" class="btn">Agregar Producto</a>
        </div>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script src="../../assets/js/scripts.js"></script>
</body>
</html>