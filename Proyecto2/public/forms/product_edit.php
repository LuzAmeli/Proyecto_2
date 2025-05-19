<?php
$base_url = '/proyecto2';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../../api/config/database.php';
require_once '../../api/models/Product.php';
require_once '../../api/utils/helpers.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);

if(isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product->read_single();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product->id = $_POST['id'];
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->category_id = $_POST['category_id'];

    if($product->update()) {
        header("Location: product_list.php");
        exit;
    } else {
        $error = "Error al actualizar el producto";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - E-commerce</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <main class="container">
        <h1>Editar Producto</h1>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="product_edit.php">
            <input type="hidden" name="id" value="<?= $product->id ?>">
            
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product->name) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($product->description) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= $product->price ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select id="category_id" name="category_id" required>
                    <option value="1" <?= $product->category_id == 1 ? 'selected' : '' ?>>Electrónicos</option>
                    <option value="2" <?= $product->category_id == 2 ? 'selected' : '' ?>>Ropa</option>
                    <option value="3" <?= $product->category_id == 3 ? 'selected' : '' ?>>Hogar</option>
                    <option value="4" <?= $product->category_id == 4 ? 'selected' : '' ?>>Juguetes</option>
                    <option value="5" <?= $product->category_id == 5 ? 'selected' : '' ?>>Alimentos</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Guardar Cambios</button>
                <a href="product_list.php" class="btn cancel">Cancelar</a>
            </div>
        </form>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script src="../../assets/js/scripts.js"></script>
</body>
</html>