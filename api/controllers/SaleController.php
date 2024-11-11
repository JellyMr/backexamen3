<?php
// controllers/SaleController.php
include_once 'config.php';

class SaleController {
    public function addSale($product_id, $quantity) {
        global $pdo;
        
        // Verificar stock disponible
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = :id");
        $stmt->execute(['id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $product['stock'] >= $quantity) {
            // Registrar la venta
            $stmt = $pdo->prepare("INSERT INTO sales (product_id, quantity, sale_date) VALUES (:product_id, :quantity, NOW())");
            $stmt->execute(['product_id' => $product_id, 'quantity' => $quantity]);

            // Actualizar el stock del producto
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
            $stmt->execute(['quantity' => $quantity, 'id' => $product_id]);

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Not enough stock'];
        }
    }

    public function getSales() {
        global $pdo;
        $stmt = $pdo->query("SELECT sales.sale_id, sales.quantity, sales.sale_date, products.title, products.price 
                             FROM sales 
                             JOIN products ON sales.product_id = products.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
