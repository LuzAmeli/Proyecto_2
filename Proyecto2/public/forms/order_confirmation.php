<?php
$base_url = '/proyecto2';
session_start();
require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../../api/models/Sale.php';
require_once __DIR__ . '/../../api/utils/helpers.php';

if (!isset($_SESSION['order_id'])) {
    header("Location: $base_url/public/forms/product_sale.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$sale = new Sale($db);
$order = $sale->getOrderDetails($_SESSION['order_id']);
$items = $sale->getOrderItems($_SESSION['order_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Productos - GK-SHOP</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/product_sale.css">
</head>
<body>

    <?php include '../partials/header.php'; ?>

<main class="container">
    <h2>Confirmación de Pedido</h2>
    
    <div class="order-confirmation">
        <div class="order-summary">
            <h3>Resumen de tu Pedido #<?= $order['id'] ?></h3>
            <p><strong>Estado:</strong> <?= ucfirst($order['status']) ?></p>
            <p><strong>Fecha:</strong> <?= $order['created_at'] ?></p>
            <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
        </div>
        
        <div class="order-items">
            <h4>Productos:</h4>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="order-actions">
            <a href="<?= $base_url ?>/public/index.php" class="btn">Comprar</a>
            <a href="<?= $base_url ?>/public/forms/my_orders.php" class="btn">Ver mis pedidos</a>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>