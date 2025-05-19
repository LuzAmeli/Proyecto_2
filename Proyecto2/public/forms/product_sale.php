<?php
$base_url = '/proyecto2';
session_start();
require_once '../../api/config/database.php';
require_once '../../api/models/Product.php';
require_once '../../api/utils/helpers.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$products = $product->read();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Productos - GK-SHOP</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/product_sale.css">
</head>
<body>

    <?php include '../partials/header.php'; ?>

    <main class="container">
        <h2>Venta de Productos</h2>
        
        <div class="sale-container">
            <form id="formularioVenta" class="sale-form" method="post" action="<?= $base_url ?>/public/forms/process_sale.php">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <input type="checkbox" 
                                        class="producto-check" 
                                        name="productos[<?= $row['id'] ?>][seleccionado]"
                                        data-precio="<?= $row['price'] ?>">
                            </td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>$<?= number_format($row['price'], 2) ?></td>
                            <td>
                                <input type="number" 
                                        class="cantidad" 
                                        name="productos[<?= $row['id'] ?>][cantidad]"
                                        min="1" 
                                        value="1" 
                                        disabled>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <div class="terminos">
                    <input type="checkbox" id="aceptoTerminos" name="aceptoTerminos" required>
                    <label for="aceptoTerminos">Acepto los términos y condiciones de los productos</label>
                </div>
                
                <div class="botones">
                    <button type="submit" class="btn enviar">Enviar</button>
                    <button type="reset" class="btn cancelar">Cancelar</button>
                </div>
            </form>
            
            <div class="resumen">
                <h3>Resumen de Compra</h3>
                <div id="productosSeleccionados"></div>
                <div class="total">
                    <strong>Total:</strong> <span id="totalVenta">$0.00</span>
                </div>
            </div>
        </div>
    </main>

    <?php include '../partials/footer.php'; ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.producto-check');
        const formulario = document.getElementById('formularioVenta');
        const productosSeleccionadosDiv = document.getElementById('productosSeleccionados');
        const totalVentaSpan = document.getElementById('totalVenta');
        
        // Habilitar/deshabilitar cantidad cuando se selecciona un producto
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const cantidadInput = this.closest('tr').querySelector('.cantidad');
                cantidadInput.disabled = !this.checked;
                actualizarResumen();
            });
        });
        
        // Actualizar resumen cuando cambia la cantidad
        document.querySelectorAll('.cantidad').forEach(input => {
            input.addEventListener('change', actualizarResumen);
        });
        
        // Botón cancelar (reset)
        formulario.querySelector('button[type="reset"]').addEventListener('click', function() {
            setTimeout(() => {
                document.querySelectorAll('.cantidad').forEach(input => {
                    input.value = 1;
                    input.disabled = true;
                });
                productosSeleccionadosDiv.innerHTML = '';
                totalVentaSpan.textContent = '$0.00';
            }, 0);
        });
        
        // Validación al enviar el formulario
        formulario.addEventListener('submit', function(e) {
            const productosSeleccionados = document.querySelectorAll('.producto-check:checked');
            const aceptoTerminos = document.getElementById('aceptoTerminos').checked;
            
            if (productosSeleccionados.length === 0) {
                e.preventDefault();
                alert('Por favor selecciona al menos un producto.');
                return;
            }
            
            if (!aceptoTerminos) {
                e.preventDefault();
                alert('Debes aceptar los términos y condiciones para continuar.');
                return;
            }
        });
        
        // Función para actualizar el resumen de compra
        function actualizarResumen() {
            let html = '';
            let total = 0;
            
            document.querySelectorAll('.producto-check:checked').forEach(checkbox => {
                const fila = checkbox.closest('tr');
                const nombre = fila.cells[1].textContent;
                const precio = parseFloat(checkbox.dataset.precio);
                const cantidad = parseInt(fila.querySelector('.cantidad').value);
                const subtotal = precio * cantidad;
                
                html += `
                    <div class="producto-resumen">
                        <strong>${nombre}</strong><br>
                        Cantidad: ${cantidad}<br>
                        Subtotal: $${subtotal.toFixed(2)}
                    </div>
                `;
                
                total += subtotal;
            });
            
            productosSeleccionadosDiv.innerHTML = html || '<p>No hay productos seleccionados</p>';
            totalVentaSpan.textContent = `$${total.toFixed(2)}`;
        }
    });
    </script>
</body>
</html>