<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(SITE_URL . '/checkout.php');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect(SITE_URL . '/cart.php');
}

// Validate & sanitize inputs
$customerName = sanitize($_POST['customer_name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = sanitize($_POST['phone'] ?? '');
$address = sanitize($_POST['address'] ?? '');
$city = sanitize($_POST['city'] ?? '');
$state = sanitize($_POST['state'] ?? '');
$notes = sanitize($_POST['notes'] ?? '');
$paymentMethod = sanitize($_POST['payment_method'] ?? 'bank_transfer');

// Validate required
$errors = [];
if (empty($customerName)) $errors[] = 'Full name is required';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
if (empty($phone)) $errors[] = 'Phone number is required';
if (empty($address)) $errors[] = 'Address is required';
if (empty($city)) $errors[] = 'City is required';
if (empty($state)) $errors[] = 'State is required';

if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    redirect(SITE_URL . '/checkout.php');
}

// Calculate totals
$subtotal = getCartTotal();
$shipping = $subtotal >= 15000 ? 0 : 2500;
$total = $subtotal + $shipping;

// Generate order number
$orderNumber = generateOrderNumber();

try {
    $pdo->beginTransaction();
    
    // Insert order
    $stmt = $pdo->prepare("INSERT INTO orders (order_number, customer_name, email, phone, address, city, state, notes, subtotal, shipping, total, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$orderNumber, $customerName, $email, $phone, $address, $city, $state, $notes, $subtotal, $shipping, $total, $paymentMethod]);
    $orderId = $pdo->lastInsertId();
    
    // Insert order items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt->execute([$orderId, $item['product_id'], $item['name'], $item['quantity'], $item['price']]);
    }
    
    $pdo->commit();
    
    // Clear cart
    $_SESSION['cart'] = [];
    
    // Store order info in session for confirmation page
    $_SESSION['last_order'] = [
        'order_number' => $orderNumber,
        'customer_name' => $customerName,
        'email' => $email,
        'total' => $total,
        'payment_method' => $paymentMethod
    ];
    
    redirect(SITE_URL . '/order_confirmation.php');
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['checkout_error'] = 'Order processing failed. Please try again or contact support.';
    redirect(SITE_URL . '/checkout.php');
}
