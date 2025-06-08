<?php
if (!isset($_SESSION['userName'])) {
    redirect(site_url('index.php?page=page404'));
}

require_once 'data/user_data.php';

$user = $_SESSION['userName'];
if (!isset($userData[$user])) {
    redirect(site_url('index.php?page=page404'));
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_photo') {
        $file = $_FILES['photo'] ?? null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $typeOK = in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif']);
            $sizeOK = $file['size'] <= 5 * 1024 * 1024;

            if (!$typeOK) {
                $errors[] = "Дозволені тільки зображення JPG, PNG або GIF";
            } elseif (!$sizeOK) {
                $errors[] = "Максимальний розмір — 5MB";
            } else {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = uniqid() . '_' . basename($file['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $oldPhoto = $userData[$user]['photo'] ?? '';
                    if ($oldPhoto && file_exists($uploadDir . $oldPhoto)) {
                        unlink($uploadDir . $oldPhoto);
                    }

                    $userData[$user]['photo'] = $fileName;
                    file_put_contents(__DIR__ . '/../data/user_data.php', '<?php $userData = ' . var_export($userData, true) . ';');
                    $success = true;
                } else {
                    $errors[] = "Помилка завантаження файлу";
                }
            }
        } else {
            $errors[] = "Оберіть файл для завантаження";
        }
    } else {
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $birthDate = trim($_POST['birthDate'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        if (!$firstName || !preg_match('/^[\p{L}\s-]{2,}$/u', $firstName)) {
            $errors[] = "Ім’я має бути не коротше 2 літер і тільки з букв";
        }

        if (!$lastName || !preg_match('/^[\p{L}\s-]{2,}$/u', $lastName)) {
            $errors[] = "Прізвище має бути не коротше 2 літер і тільки з букв";
        }

        if ($birthDate) {
            $age = (new DateTime())->diff(new DateTime($birthDate))->y;
            if ($age < 16) $errors[] = "Вам має бути мінімум 16 років";
        } else {
            $errors[] = "Дата народження обов'язкова";
        }

        if (strlen($bio) < 50) {
            $errors[] = "Коротка інформація повинна бути мінімум 50 символів";
        }

        if (!$errors) {
            $userData[$user] = [
                'userName' => $user,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'birthDate' => $birthDate,
                'bio' => $bio,
                'photo' => $userData[$user]['photo'] ?? ''
            ];
            file_put_contents(__DIR__ . '/../data/user_data.php', '<?php $userData = ' . var_export($userData, true) . ';');
            $success = true;
        }
    }
}

$currentUserData = $userData[$user];
?>


<div class="profile-container">
    <div class="profile-header">
        <h2>Профіль користувача</h2>
    </div>

    <div class="profile-content">
        <div class="profile-info">
            <div class="info-group">
                <h3>Фотографія</h3>
                <div class="photo-container">
                    <?php if (!empty($currentUserData['photo'])): ?>
                        <img src="<?php echo site_url('uploads/' . $currentUserData['photo']); ?>" alt="Фото профілю" class="profile-photo">
                    <?php else: ?>
                        <div class="no-photo">Немає фото</div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo site_url('index.php?page=profile'); ?>" enctype="multipart/form-data" class="photo-form">
                        <input type="hidden" name="action" value="update_photo">
                        <div class="form-group">
                            <label for="photo">Змінити фото:</label>
                            <input type="file" id="photo" name="photo" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn-save">Завантажити</button>
                    </form>
                </div>
            </div>

            <div class="info-group">
                <h3>Основна інформація</h3>
                <div class="info-item">
                    <span class="info-label">UserName:</span>
                    <span class="info-value"><?php echo htmlspecialchars($currentUserData['userName']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ім'я:</span>
                    <span class="info-value"><?php echo htmlspecialchars($currentUserData['firstName']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Прізвище:</span>
                    <span class="info-value"><?php echo htmlspecialchars($currentUserData['lastName']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Дата народження:</span>
                    <span class="info-value"><?php echo htmlspecialchars($currentUserData['birthDate']); ?></span>
                </div>
            </div>

            <div class="info-group">
                <h3>Коротка інформація</h3>
                <div class="info-item bio">
                    <span class="info-value"><?php echo nl2br(htmlspecialchars($currentUserData['bio'])); ?></span>
                </div>
            </div>
        </div>

        <div class="profile-edit">
            <h3>Редагування профілю</h3>
            
            <?php if ($success): ?>
                <div class="message success">Дані успішно збережено!</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="message error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo site_url('index.php?page=profile'); ?>" class="edit-form">
                <div class="form-group">
                    <label for="firstName">Ім'я:</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($currentUserData['firstName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastName">Прізвище:</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($currentUserData['lastName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="birthDate">Дата народження:</label>
                    <input type="date" id="birthDate" name="birthDate" value="<?php echo htmlspecialchars($currentUserData['birthDate']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="bio">Коротка інформація:</label>
                    <textarea id="bio" name="bio" rows="4" required><?php echo htmlspecialchars($currentUserData['bio']); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-save">Зберегти зміни</button>
                </div>
            </form>
        </div>
    </div>
</div>