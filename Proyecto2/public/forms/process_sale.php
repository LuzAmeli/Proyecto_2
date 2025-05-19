<?php
$base_url = '/proyecto2';
session_start();
require_once '../../api/config/database.php';
require_once '../../api/models/Sale.php';
require_once '../../api/utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../product_sale.php");
    exit;
}

// Validar que se aceptaron los términos
if (!isset($_POST['aceptoTerminos'])) {
    $_SESSION['error'] = 'Debes aceptar los términos y condiciones';
    header("Location: ../product_sale.php");
    exit;
}

// Validar que hay productos seleccionados
if (!isset($_POST['productos']) || empty($_POST['productos'])) {
    $_SESSION['error'] = 'Debes seleccionar al menos un producto';
    header("Location: ../product_sale.php");
    exit;
}

$database = new Database();
$db = $database->connect();

// Procesar cada producto seleccionado
$productosVenta = [];
foreach ($_POST['productos'] as $idProducto => $detalle) {
    if (isset($detalle['seleccionado']) && $detalle['cantidad'] > 0) {
        $productosVenta[] = [
            'id' => $idProducto,
            'cantidad' => $detalle['cantidad']
        ];
    }
}

// Crear la orden en la base de datos
$sale = new Sale($db);
$user_id = $_SESSION['user_id'] ?? null; // Asegúrate de que el user_id está en la sesión

if (!$user_id) {
    $_SESSION['error'] = 'Debes iniciar sesión para realizar una compra';
    header("Location: $base_url/public/forms/login.php");
    exit;
}

$order_id = $sale->create($user_id, $productosVenta);

if ($order_id) {
    $_SESSION['order_id'] = $order_id;
    $_SESSION['success'] = 'Orden creada con éxito. Número de orden: ' . $order_id;
    header("Location: $base_url/public/forms/order_confirmation.php");
} else {
    $_SESSION['error'] = 'Error al procesar la orden';
    header("Location: $base_url/public/forms/product_sale.php");
}
exit;