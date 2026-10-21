<?php
require_once __DIR__ . '/bootstrap.php';

$errors = [];
$manufacturers = $manufacturerRepo->findAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = trim($_POST['model'] ?? '');
    $vendor = trim($_POST['vendor'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $manufacturerId = (int)($_POST['manufacturerId'] ?? 0);
    $imagePath = 'uploads/default.png';

    // 1. Текстова валідація
    if (empty($model)) { $errors[] = 'Модель є обов’язковим полем.'; }
    if (empty($vendor)) { $errors[] = 'Вендор є обов’язковим полем.'; }
    if ($price <= 0) { $errors[] = 'Ціна повинна бути більше 0.'; }
    if ($manufacturerId <= 0) { $errors[] = 'Оберіть виробника.'; }

    // 2. Обробка та валідація завантаження файлу
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Перевірка MIME-типу через finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileTmpPath);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($mimeType, $allowedMimeTypes) || !in_array($fileExtension, $allowedExtensions)) {
            $errors[] = 'Неприпустимий тип файлу. Дозволені формати: JPG, PNG, WEBP.';
        } else {
            // Генерація унікального імені файлу
            $newFileName = uniqid('router_', true) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = 'uploads/' . $newFileName;
            } else {
                $errors[] = 'Помилка при збереженні завантаженого файлу на сервері.';
            }
        }
    }

    // 3. Збереження у CSV при відсутності помилок
    if (empty($errors)) {
        $routerRepo->add([
            'model' => $model,
            'vendor' => $vendor,
            'price' => $price,
            'manufacturerId' => $manufacturerId,
            'image' => $imagePath
        ]);

        // PRG перенаправлення
        header('Location: index.php?msg=router_added');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати маршрутизатор</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; margin: 30px; color: #0f172a; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 550px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
        button { background: #10b981; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .error { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        a { color: #64748b; text-decoration: none; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
<div class="card">
    <h2>📡 Додати маршрутизатор</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <p style="margin:2px 0;"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Важливо: enctype="multipart/form-data" для завантаження файлу -->
    <form method="POST" action="add_router.php" enctype="multipart/form-data">
        <div class="form-group">
            <label>Модель:</label>
            <input type="text" name="model" value="<?= htmlspecialchars($_POST['model'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Вендор:</label>
            <input type="text" name="vendor" value="<?= htmlspecialchars($_POST['vendor'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Ціна (грн):</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Виробник (Довідник):</label>
            <select name="manufacturerId">
                <option value="">-- Оберіть виробника --</option>
                <?php foreach ($manufacturers as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= (isset($_POST['manufacturerId']) && $_POST['manufacturerId'] == $m['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['country']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Зображення маршрутизатора (JPG, PNG, WEBP):</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </div>
        <button type="submit">Зберегти маршрутизатор</button>
    </form>
    <a href="index.php">← Повернутися до списку</a>
</div>
</body>
</html>