<?php

$base_url = '/proyecto2'; 

session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

require_once '../../api/utils/helpers.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    if(validateEmail($email) && validatePassword($password)) {
        require_once '../../api/config/database.php';
        require_once '../../api/models/User.php';

        $database = new Database();
        $db = $database->connect();

        $user = new User($db);
        $user->email = $email;

        if($user->emailExists()) {
            if(password_verify($password, $user->password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                
                header("Location: ../dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }
    } else {
        $error = "Por favor ingrese un email y contraseña válidos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-commerce</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <main class="container">
        <h1>Iniciar Sesión</h1>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="correo@ejemplo.com">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required minlength="8" placeholder="Ingrese su contraseña">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Ingresar</button>
                <a href="../index.php" class="btn cancel">Cancelar</a>
            </div>
        </form>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script src="../../assets/js/scripts.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if(!email || !password) {
                alert('Por favor complete todos los campos');
                e.preventDefault();
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailRegex.test(email)) {
                alert('Por favor ingrese un email válido');
                e.preventDefault();
                return false;
            }
            
            if(password.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>