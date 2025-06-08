<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Весна</title>
    <link rel="stylesheet" href="<?php echo site_url('css/style.css'); ?>">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="<?php echo site_url('index.php?page=home'); ?>" style="color:black; text-decoration:none;">Продовольчий магазин "Весна"</a></h1>
            <nav>
                <ul>
                    <li><a href="<?php echo site_url('index.php?page=home'); ?>">Головна</a></li>
                    <li><a href="<?php echo site_url('index.php?page=products'); ?>">Товари</a></li>
                    <li><a href="<?php echo site_url('index.php?page=cart'); ?>">Кошик (<?php
                        $itemCount = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $item) {
                                $itemCount += $item['quantity'];
                            }
                        }
                        echo $itemCount;
                    ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">