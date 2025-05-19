<?php

$base_url = '/proyecto2'; 

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return strlen($password) >= 8;
}

function redirect($url) {
    header("Location: $url");
    exit();
}
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

?>