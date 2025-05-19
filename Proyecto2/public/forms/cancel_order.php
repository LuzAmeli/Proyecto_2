<?php
$base_url = '/proyecto2';
session_start();
require_once __DIR__ . '/../../api/config/database.php';
require_once __DIR__ . '/../../api/models/Sale.php';
require_once __DIR__ . '/../../api/utils/helpers.php';

// Verificar si el usuario está autenticado
if (!isAuthenticated()) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    $_SESSION['error'] = 'Debes iniciar sesión para realizar esta acción';
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

// Verificar que el pedido pertenece al usuario actual
if ($order['user_id'] != $_SESSION['user_id']) {
    $_SESSION['error'] = 'No tienes permiso para realizar esta acción';
    header("Location: $base_url/public/forms/my_orders.php");
    exit;
}

// Verificar que el pedido está pendiente
if ($order['status'] !== 'pendiente') {
    $_SESSION['error'] = 'Solo se pueden cancelar pedidos con estado "pendiente"';
    header("Location: $base_url/public/forms/order_detail.php?id=$order_id");
    exit;
}

// Actualizar el estado del pedido
try {
    $query = "UPDATE orders 
             SET status = 'cancelado', updated_at = NOW() 
             WHERE id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $_SESSION['success'] = 'Pedido cancelado correctamente';
} catch (PDOException $e) {
    error_log("Error al cancelar pedido: " . $e->getMessage());
    $_SESSION['error'] = 'Error al cancelar el pedido';
}

header("Location: $base_url/public/forms/order_detail.php?id=$order_id");
exit;