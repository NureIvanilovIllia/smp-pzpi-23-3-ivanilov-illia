<?php
$cartItems = getCartItems();
$cartTotal = getCartTotal();
?>
<h2>Ваш Кошик</h2>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message <?php echo $_SESSION['message_type'] ?? 'success'; ?>">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php 
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <p>Ваш кошик порожній. <a href="<?php echo site_url('index.php?page=products'); ?>">Перейти до товарів</a>.</p>
<?php else: ?>
    <ul class="cart-items">
        <?php foreach ($cartItems as $item): ?>
            <li>
                <div class="item-info">
                    <h3><?php echo $item['name']; ?></h3>
                    <p>Ціна: <?php echo number_format($item['price'], 2); ?> грн</p>
                    <form action="<?php echo site_url('index.php?action=update_cart_item'); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <label for="quantity_cart_<?php echo $item['id']; ?>">К-сть:</label>
                        <input type="number" id="quantity_cart_<?php echo $item['id']; ?>" name="quantity" value="<?php echo $item['quantity']; ?>" min="0">
                        <input type="submit" value="Оновити">
                    </form>
                </div>
                <div class="item-actions">
                    <p>Разом: <?php echo number_format($item['price'] * $item['quantity'], 2); ?> грн</p>
                    <form action="<?php echo site_url('index.php?action=remove_from_cart'); ?>" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <input type="submit" value="Видалити" style="background-color:#dc3545;">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="cart-total">
        Загальна сума: <?php echo number_format($cartTotal, 2); ?> грн
    </div>
    <div class="cart-actions">
        <a href="<?php echo site_url('index.php?page=products'); ?>" style="background-color:#007bff;">Продовжити покупки</a>
        <form action="<?php echo site_url('index.php?action=clear_cart'); ?>" method="post" style="display:inline;">
            <button type="submit" class="clear-cart">Очистити кошик</button>
        </form>
    </div>
<?php endif; ?>