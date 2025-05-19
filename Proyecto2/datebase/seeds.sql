-- Datos iniciales
INSERT INTO categories (name) VALUES 
('Electrónicos'),
('Ropa'),
('Hogar'),
('Juguetes'),
('Alimentos');

-- Usuario administrador inicial (password: admin123)
INSERT INTO users (name, email, password) VALUES 
('Administrador', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Productos de ejemplo
INSERT INTO products (name, description, price, category_id) VALUES
('Laptop HP', 'Laptop HP 15.6 pulgadas, 8GB RAM, 256GB SSD', 899.99, 1),
('Smartphone Samsung', 'Smartphone Samsung Galaxy S21 128GB', 799.99, 1),
('Camisa de Algodón', 'Camisa de algodón 100% talla M', 29.99, 2),
('Juego de Sábanas', 'Juego de sábanas de algodón, tamaño queen', 49.99, 3),
('Robot Aspirador', 'Robot aspirador inteligente con app', 299.99, 1),
('Zapatos Deportivos', 'Zapatos deportivos para correr talla 10', 89.99, 2),
('Licuadora Profesional', 'Licuadora de 1000W con 10 velocidades', 129.99, 3),
('Juego de Lego', 'Juego de construcción Lego City 600 piezas', 59.99, 4),
('Café Premium', 'Café en grano 100% arábica 1kg', 19.99, 5),
('Auriculares Inalámbricos', 'Auriculares Bluetooth con cancelación de ruido', 199.99, 1);