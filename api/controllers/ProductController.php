<?php
// controllers/ProductController.php
include_once 'config.php';

class ProductController {
    public function searchProducts($query) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE title LIKE :query OR description LIKE :query");
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduct($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
