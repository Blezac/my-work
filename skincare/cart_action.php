<?php
require_once 'includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        
        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            exit();
        }
        
        // Fetch product
        $stmt = $pdo->prepare("SELECT id, name, price, sale_price FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit();
        }
        
        $price = $product['sale_price'] ?? $product['price'];
        $itemKey = 'item_' . $productId;
        
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => (float)$price,
                'quantity' => $quantity,
            ];
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Added to cart',
            'cart_count' => getCartCount(),
            'cart_total' => getCartTotal()
        ]);
        break;
    
    case 'update':
        $itemId = sanitize($_POST['item_id'] ?? '');
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]['quantity'] = $qty;
            $itemTotal = $_SESSION['cart'][$itemId]['price'] * $qty;
            echo json_encode([
                'success' => true,
                'item_total' => $itemTotal,
                'cart_total' => getCartTotal(),
                'cart_count' => getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not found']);
        }
        break;
    
    case 'remove':
        $itemId = sanitize($_POST['item_id'] ?? '');
        if (isset($_SESSION['cart'][$itemId])) {
            unset($_SESSION['cart'][$itemId]);
            echo json_encode([
                'success' => true,
                'cart_total' => getCartTotal(),
                'cart_count' => getCartCount()
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;
    
    case 'clear':
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart_count' => 0]);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
}
