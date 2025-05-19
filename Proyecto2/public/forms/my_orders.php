<?php
$base_url = '/proyecto2';
session_start();
require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../../api/models/Sale.php';
require_once __DIR__ . '/../../api/utils/helpers.php';

// Verificar si el usuario está autenticado
if (!isAuthenticated()) {
    $_SESSION['redirect_to'] = $base_url . '/public/forms/my_orders.php';
    $_SESSION['error'] = 'Debes iniciar sesión para ver tus pedidos';
    header("Location: $base_url/public/forms/login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$sale = new Sale($db);
$user_id = $_SESSION['user_id'];
$orders = $sale->getUserOrders($user_id);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar_Orden - GK-SHOP</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/order_confirmation.css">
</head>
<body>
<?php include '../partials/header.php';
?>

<main class="container">
    <h2>Mis Pedidos</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($orders)): ?>
        <div class="no-orders">
            <p>No has realizado ningún pedido todavía.</p>
            <a href="<?= $base_url ?>/public/index.php" class="btn">Ver productos</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Pedido #<?= $order['id'] ?></h3>
                        <span class="order-status <?= strtolower($order['status']) ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    
                    <div class="order-details">
                        <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                        <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
                    </div>
                    
                    <div class="order-actions">
                        <a href="<?= $base_url ?>/public/forms/order_detail.php?id=<?= $order['id'] ?>" class="btn btn-small">
                            Ver detalles
                        </a>
                        
                        <?php if ($order['status'] === 'pendiente'): ?>
                            <a href="<?= $base_url ?>/public/forms/cancel_order.php?id=<?= $order['id'] ?>" class="btn btn-small btn-danger">
                                Cancelar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../partials/footer.php'; ?>