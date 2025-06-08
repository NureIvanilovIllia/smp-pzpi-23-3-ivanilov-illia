<?php
$products = getAllProducts();
?>
<h2>Наші Товари</h2>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message <?php echo $_SESSION['message_type'] ?? 'success'; ?>">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php 
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>
<?php endif; ?>

<?php if (empty($products)): ?>
    <p>На жаль, на даний момент товарів немає.</p>
<?php else: ?>
    <ul class="products-list">
        <?php foreach ($products as $product): ?>
            <li>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>Ціна: <?php echo number_format($product['price'], 2); ?> грн</p>
                </div>
                <div class="product-actions">
                    <form action="<?php echo site_url('index.php?action=add_to_cart'); ?>" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <label for="quantity_<?php echo $product['id']; ?>">К-сть:</label>
                        <input type="number" id="quantity_<?php echo $product['id']; ?>" name="quantity" value="1" min="1" max="100" style="width: 50px;">
                        <input type="submit" value="Додати в кошик">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>