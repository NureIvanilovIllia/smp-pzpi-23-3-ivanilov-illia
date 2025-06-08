<?php

function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $productId = (int)$productId;
    $quantity = (int)$quantity;

    if ($quantity <= 0) return; 

    $product = getProductById($productId);
    if (!$product) return; 

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
}

function removeFromCart($productId) {
    $productId = (int)$productId;
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function updateCartQuantity($productId, $quantity) {
    $productId = (int)$productId;
    $quantity = (int)$quantity;

    isset($_SESSION['cart'][$productId]);
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
}

function getCartItems() {
    return $_SESSION['cart'] ?? [];
}

function getCartTotal() {
    $total = 0;
    foreach (getCartItems() as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function clearCart() {
    $_SESSION['cart'] = [];
}

function site_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    
    $base_path = dirname($script_name); 
    if ($base_path === '/' || $base_path === '\\') {
        $base_path = '';
    }
    
    return $protocol . "://" . $host . $base_path . '/' . ltrim($path, '/');
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}
?>