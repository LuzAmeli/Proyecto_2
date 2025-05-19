<?php

$base_url = '/proyecto2'; 
require_once '../../api/utils/helpers.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email']);
    $comments = sanitizeInput($_POST['comments']);
    
    // Aquí normalmente enviarías el correo o guardarías en la base de datos
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - E-commerce</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <main class="container">
        <h1>Contacto</h1>
        
        <?php if(isset($success) && $success): ?>
            <div class="alert success">¡Gracias por contactarnos! Te responderemos pronto.</div>
        <?php endif; ?>

        <form id="contactForm" method="POST" action="contact.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="su email: correo@ejemplo.com">
            </div>
            
            <div class="form-group">
                <label for="comments">Comentarios:</label>
                <textarea id="comments" name="comments" rows="5" required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Enviar</button>
                <button type="reset" class="btn cancel">Cancelar</button>
            </div>
        </form>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script src="../../assets/js/scripts.js"></script>
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const comments = document.getElementById('comments').value;
            
            if(!email || !comments) {
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
            
            return true;
        });
        
        document.querySelector('.btn.cancel').addEventListener('click', function() {
            document.getElementById('contactForm').reset();
        });
    </script>
</body>
</html>