<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/credentials.php';
    
    $userName = $_POST['userName'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($userName) || empty($password)) {
        $error = 'Будь ласка, заповніть всі поля';
    } elseif ($userName === $credentials['userName'] && $password === $credentials['password']) {
        $_SESSION['userName'] = $userName;
        $_SESSION['loginTime'] = time();
        redirect(site_url('index.php'));
    } else {
        $error = 'Невірний логін або пароль';
    }
}
?>

<div>
    <div>
        <h3>Вхід в систему</h3>
    </div>
    <div>  
        <?php if ($error): ?>
            <div><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
                    
        <form method="POST" action="<?php echo site_url('index.php?page=login'); ?>">
            <div>
                <label for="userName" class="form-label">Ім'я користувача</label>
                <input type="text" class="form-control" id="userName" name="userName" required>
            </div>
            <div>
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Увійти</button>
            </div>
        </form>
    </div>
</div>