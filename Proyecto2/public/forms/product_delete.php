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

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $database = new Database();
    $db = $database->connect();

    $product = new Product($db);
    $product->id = $_POST['id'];

    if($product->delete()) {
        $_SESSION['success_message'] = "Producto eliminado correctamente";
    } else {
        $_SESSION['error_message'] = "Error al eliminar el producto";
    }
}

header("Location: product_list.php");
exit;
?>