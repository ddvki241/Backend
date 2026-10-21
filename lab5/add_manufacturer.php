<?php
require_once __DIR__ . '/bootstrap.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $country = trim($_POST['country'] ?? '');

    // Валідація
    if (empty($name)) {
        $errors[] = 'Назва виробника є обов’язковим полем.';
    }
    if (empty($country)) {
        $errors[] = 'Країна є обов’язковим полем.';
    }

    if (empty($errors)) {
        // Збереження через репозиторій у CSV
        $manufacturerRepo->add([
            'name' => $name,
            'country' => $country
        ]);

        // Патерн Post/Redirect/Get (PRG)
        header('Location: index.php?msg=manufacturer_added');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати виробника</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; margin: 30px; color: #0f172a; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 500px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
        button { background: #4f46e5; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .error { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        a { color: #64748b; text-decoration: none; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
<div class="card">
    <h2>➕ Додати виробника</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <p style="margin:2px 0;"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_manufacturer.php">
        <div class="form-group">
            <label>Назва бренду/виробника:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Країна походження:</label>
            <input type="text" name="country" value="<?= htmlspecialchars($_POST['country'] ?? '') ?>">
        </div>
        <button type="submit">Зберегти виробника</button>
    </form>
    <a href="index.php">← Повернутися до списку</a>
</div>
</body>
</html>