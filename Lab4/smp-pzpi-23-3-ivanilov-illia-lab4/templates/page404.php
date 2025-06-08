<?php
if (isset($_SESSION['userName'])) {
    redirect(site_url('index.php'));
}
?>

<div>
    <div>
        <div>
            <h1>404</h1>
            <h2>Сторінку не знайдено</h2>
            <p><a href="<?php echo site_url('index.php?page=login'); ?>">Увійдіть в систему</a> для доступу до сайту.</p>
        </div>
    </div>
</div>  