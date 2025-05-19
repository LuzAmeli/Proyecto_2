<?php
session_start();

// Verifica si el método de solicitud es GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_unset(); // Limpia todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: login.php"); 
    echo json_encode(['message' => 'Sesión cerrada correctamente']);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>