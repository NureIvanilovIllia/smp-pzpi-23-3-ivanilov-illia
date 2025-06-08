<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';
require_once 'includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action && ($_SERVER['REQUEST_METHOD'] === 'POST' || in_array($action, ['remove_from_cart', 'clear_cart', 'logout']))) {
    switch ($action) {
        case 'add_to_cart':
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? null;
            if ($productId && $quantity) {
                addToCart($productId, $quantity);
                $_SESSION['message'] = "Товар додано до кошика!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Помилка: ID товару або кількість не вказано.";
                $_SESSION['message_type'] = "error";
            }
            redirect(site_url('index.php?page=products'));
            break;

        case 'remove_from_cart':
            $productId = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
            if ($productId) {
                removeFromCart($productId);
                $_SESSION['message'] = "Товар видалено з кошика.";
            }
            redirect(site_url('index.php?page=cart'));
            break;

        case 'update_cart_item':
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? null;
            if ($productId && $quantity) {
                updateCartQuantity($productId, $quantity);
                $_SESSION['message'] = "Кількість товару оновлено.";
            }
            redirect(site_url('index.php?page=cart'));
            break;

        case 'clear_cart':
            clearCart();
            $_SESSION['message'] = "Кошик очищено.";
            redirect(site_url('index.php?page=cart'));
            break;

        case 'logout':
            session_destroy();
            redirect(site_url('index.php?page=login'));
            break;
    }
}
$page = $_GET['page'] ?? 'home'; 

require_once 'templates/header.php';

switch ($page) {
    case 'products':
        require_once 'templates/products.php';
        break;
    case 'cart':
        require_once 'templates/cart.php';
        break;
    case 'about':
        require_once 'templates/about.php';
        break;
    case 'login':
        require_once 'templates/login.php';
        break;
    case 'profile':
        require_once 'templates/profile.php';
        break;
    case 'page404':
        require_once 'templates/page404.php';
        break;
    case 'home':
    default:
        require_once 'templates/home.php';
        break;
}

require_once 'templates/footer.php';
?>