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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->connect();

    $product = new Product($db);
    
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->category_id = $_POST['category_id'];

    if($product->create()) {
        header("Location: product_list.php");
        exit;
    } else {
        $error = "Error al crear el producto";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto - E-commerce</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <main class="container">
        <h1>Crear Producto</h1>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="product_create.php">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select id="category_id" name="category_id" required>
                    <option value="1">Electrónicos</option>
                    <option value="2">Ropa</option>
                    <option value="3">Hogar</option>
                    <option value="4">Juguetes</option>
                    <option value="5">Alimentos</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Guardar</button>
                <a href="product_list.php" class="btn cancel">Cancelar</a>
            </div>
        </form>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script src="../../assets/js/scripts.js"></script>
</body>
</html>