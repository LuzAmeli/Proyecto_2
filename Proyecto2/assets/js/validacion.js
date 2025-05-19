document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        // Obtener valores de los campos
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Validar campos vacíos
        if(!name || !email || !password || !confirmPassword) {
            alert('Por favor complete todos los campos');
            e.preventDefault();
            return false;
        }
        
        // Validar formato de email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(email)) {
            alert('Por favor ingrese un email válido');
            e.preventDefault();
            return false;
        }
        
        // Validar longitud de contraseña
        if(password.length < 8) {
            alert('La contraseña debe tener al menos 8 caracteres');
            e.preventDefault();
            return false;
        }
        
        // Validar coincidencia de contraseñas
        if(password !== confirmPassword) {
            alert('Las contraseñas no coinciden');
            e.preventDefault();
            return false;
        }
        
        // Si todo es válido, el formulario se enviará
        return true;
    });
});