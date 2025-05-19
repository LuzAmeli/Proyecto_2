<?php
$base_url = '/proyecto2';
session_start();
require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../../api/models/Sale.php';
require_once __DIR__ . '/../../api/utils/helpers.php';

// Verificar si el usuario está autenticado
if (!isAuthenticated()) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    $_SESSION['error'] = 'Debes iniciar sesión para ver este pedido';
    header("Location: $base_url/public/forms/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'Pedido no especificado';
    header("Location: $base_url/public/forms/my_orders.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$sale = new Sale($db);
$order_id = $_GET['id'];
$order = $sale->getOrderDetails($order_id);
$items = $sale->getOrderItems($order_id);

// Verificar que el pedido pertenece al usuario actual
if ($order['user_id'] != $_SESSION['user_id']) {
    $_SESSION['error'] = 'No tienes permiso para ver este pedido';
    header("Location: $base_url/public/forms/my_orders.php");
    exit;
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
    <h2>Detalles del Pedido #<?= $order['id'] ?></h2>
    
    <div class="order-detail">
        <div class="order-summary">
            <p><strong>Estado:</strong> <span class="order-status <?= strtolower($order['status']) ?>">
                <?= ucfirst($order['status']) ?>
            </span></p>
            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Última actualización:</strong> <?= date('d/m/Y H:i', strtotime($order['updated_at'])) ?></p>
        </div>
        
        <div class="order-items">
            <h3>Productos</h3>
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
                        <td>
                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                            <p class="product-description"><?= htmlspecialchars($item['description']) ?></p>
                        </td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($order['total'], 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="order-actions">
            <a href="<?= $base_url ?>/public/forms/my_orders.php" class="btn">comprar</a>
            
            <?php if ($order['status'] === 'pendiente'): ?>
                <a href="<?= $base_url ?>/public/forms/cancel_order.php?id=<?= $order['id'] ?>" class="btn btn-danger">
                    Cancelar pedido
                </a>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>