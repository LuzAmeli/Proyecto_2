<?php
class Sale {
    private $conn;
    private $orders_table = 'orders';
    private $order_items_table = 'order_items';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una nueva orden (venta)
    public function create($user_id, $products) {
        try {
            // Validar entrada
            if (empty($user_id)) {
                throw new Exception("ID de usuario no válido");
            }
            
            if (empty($products) || !is_array($products)) {
                throw new Exception("Lista de productos no válida");
            }

            // Iniciar transacción
            $this->conn->beginTransaction();

            // 1. Calcular el total de la orden
            $total = 0;
            $items_data = [];
            
            foreach ($products as $product) {
                // Validar producto
                if (empty($product['id']) || empty($product['cantidad']) || $product['cantidad'] <= 0) {
                    throw new Exception("Datos de producto no válidos");
                }

                // Obtener precio actual del producto
                $price_query = "SELECT price FROM products WHERE id = :product_id";
                $price_stmt = $this->conn->prepare($price_query);
                $price_stmt->bindParam(':product_id', $product['id'], PDO::PARAM_INT);
                $price_stmt->execute();
                $product_data = $price_stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$product_data) {
                    throw new Exception("Producto no encontrado: " . $product['id']);
                }
                
                $subtotal = $product_data['price'] * $product['cantidad'];
                $total += $subtotal;
                
                $items_data[] = [
                    'product_id' => (int)$product['id'],
                    'quantity' => (int)$product['cantidad'],
                    'price' => (float)$product_data['price'],
                    'subtotal' => (float)$subtotal
                ];
            }

            // 2. Insertar la orden principal
            $query = "INSERT INTO " . $this->orders_table . " 
                     (user_id, total, status) 
                     VALUES (:user_id, :total, 'pendiente')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':total', $total);
            $stmt->execute();
            $order_id = $this->conn->lastInsertId();

            // 3. Insertar los items de la orden
            foreach ($items_data as $item) {
                $detail_query = "INSERT INTO " . $this->order_items_table . " 
                               (order_id, product_id, quantity, price) 
                               VALUES (:order_id, :product_id, :quantity, :price)";
                $detail_stmt = $this->conn->prepare($detail_query);
                $detail_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $detail_stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                $detail_stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $detail_stmt->bindParam(':price', $item['price']);
                $detail_stmt->execute();
            }

            // Confirmar transacción
            $this->conn->commit();
            return $order_id;

        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error al crear orden: " . $e->getMessage());
            return false;
        }
    }

    // Obtener detalles de una orden
    public function getOrderDetails($order_id) {
        try {
            $query = "SELECT o.*, u.name, u.email 
                     FROM " . $this->orders_table . " o
                     JOIN users u ON o.user_id = u.id
                     WHERE o.id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                throw new Exception("Orden no encontrada");
            }
            
            // Asegurar valores por defecto
            $order['status'] = $order['status'] ?? 'pendiente';
            
            return $order;
            
        } catch (Exception $e) {
            error_log("Error al obtener detalles de orden: " . $e->getMessage());
            return false;
        }
    }

    // Obtener items de una orden
    public function getOrderItems($order_id) {
        try {
            $query = "SELECT 
                         oi.*, 
                         p.name as product_name, 
                         p.description,
                         p.price as unit_price
                      FROM " . $this->order_items_table . " oi
                      JOIN products p ON oi.product_id = p.id
                      WHERE oi.order_id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calcular subtotal si no viene en los datos
            foreach ($items as &$item) {
                $item['subtotal'] = $item['subtotal'] ?? ($item['price'] * $item['quantity']);
            }
            
            return $items;
            
        } catch (Exception $e) {
            error_log("Error al obtener items de orden: " . $e->getMessage());
            return false;
        }
    }

    // Método adicional: obtener todas las órdenes de un usuario
    public function getUserOrders($user_id) {
        try {
            $query = "SELECT 
                         o.id, 
                         o.total, 
                         o.status, 
                         o.created_at,
                         COUNT(oi.id) as items_count
                      FROM " . $this->orders_table . " o
                      LEFT JOIN " . $this->order_items_table . " oi ON o.id = oi.order_id
                      WHERE o.user_id = :user_id
                      GROUP BY o.id
                      ORDER BY o.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener órdenes de usuario: " . $e->getMessage());
            return false;
        }
    }
}