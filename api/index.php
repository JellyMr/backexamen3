<?php
header("Access-Control-Allow-Origin: https://cheery-marshmallow-7da01c.netlify.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Verifica si la solicitud es de tipo OPTIONS (solicitud previa de CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: https://cheery-marshmallow-7da01c.netlify.app");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    http_response_code(200);
    exit;
}

include_once 'controllers/ProductController.php';
include_once 'controllers/SaleController.php';

$requestUri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$method = $_SERVER['REQUEST_METHOD'];

$productController = new ProductController();
$saleController = new SaleController();

// Verifica que la primera parte de la ruta sea "api"
if ($requestUri[0] === 'api') {
    // Verifica que la segunda parte de la ruta exista
    if (isset($requestUri[1])) {
        switch ($requestUri[1]) {
            case 'items':
                if ($method === 'GET') {
                    // Verifica si hay un parámetro de consulta 'q' para búsqueda
                    if (isset($_GET['q'])) {
                        $query = $_GET['q'];
                        echo json_encode(['products' => $productController->searchProducts($query)]);
                    } elseif (isset($requestUri[2]) && is_numeric($requestUri[2])) {
                        // Obtiene los detalles de un producto específico usando su ID
                        $id = intval($requestUri[2]);
                        echo json_encode($productController->getProduct($id));
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Query or ID not provided']);
                    }
                }
                break;

            case 'addSale':
                if ($method === 'POST') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (isset($data['product_id']) && isset($data['quantity'])) {
                        $response = $saleController->addSale($data['product_id'], $data['quantity']);
                        echo json_encode($response);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Invalid data']);
                    }
                }
                break;

            case 'sales':
                if ($method === 'GET') {
                    echo json_encode(['sales' => $saleController->getSales()]);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid endpoint']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'API root endpoint accessed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Endpoint not found']);
}
