<?php
?>
    <footer>
        <div>
            <div>
                <div>
                    <p>&copy; 2024 Продовольчий магазин "Весна". Всі права захищені.</p>
                </div>
                <div">
                <nav>
                    <ul>
                        <li><a href="<?php echo site_url('index.php?page=home'); ?>">Головна</a></li>
                        <li><a href="<?php echo site_url('index.php?page=products'); ?>">Товари</a></li>
                        <li><a href="<?php echo site_url('index.php?page=cart'); ?>">Кошик</a></li>
                        <li><a href="<?php echo site_url('index.php?page=about'); ?>">Про нас</a></li>
                    </ul>
                </nav>
                    <?php if (isset($_SESSION['userName'])): ?>
                        <a href="<?php echo site_url('index.php?action=logout'); ?>" class="btn btn-outline-danger">Вийти</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>