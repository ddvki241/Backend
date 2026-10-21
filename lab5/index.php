<?php
require_once __DIR__ . '/bootstrap.php';

$manufacturers = $manufacturerRepo->findAll();
$routers = $routerRepo->findAllWithManufacturers($manufacturerRepo);

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна №5 - SSR Застосунок</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 30px; background: #f8fafc; color: #0f172a; }
        .container { max-width: 950px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 14px; text-decoration: none; border-radius: 4px; font-weight: 600; color: white; display: inline-block; margin-left: 5px; }
        .btn-m { background: #4f46e5; }
        .btn-r { background: #10b981; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; vertical-align: middle; }
        th { background: #f1f5f9; }
        .img-thumb {width: 100px; height: 100px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; background-color: #ffffff; padding: 4px;}
        .alert { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>🌐 SSR Каталог: Маршрутизатори та Виробники</h2>
        <div>
            <a href="add_manufacturer.php" class="btn btn-m">+ Виробник</a>
            <a href="add_router.php" class="btn btn-r">+ Маршрутизатор</a>
        </div>
    </div>

    <?php if ($msg === 'manufacturer_added'): ?>
        <div class="alert">Успішно додано нового виробника до CSV!</div>
    <?php elseif ($msg === 'router_added'): ?>
        <div class="alert">Успішно додано новий маршрутизатор та завантажено зображення!</div>
    <?php endif; ?>

    <h3>📡 Маршрутизатори</h3>
    <?php if (empty($routers)): ?>
        <p>Записи відсутні.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>ID</th>
                    <th>Модель</th>
                    <th>Вендор</th>
                    <th>Ціна (грн)</th>
                    <th>Виробник</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($routers as $r): ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($r['image']) ?>" alt="Router" class="img-thumb" onerror="this.src='uploads/default.png'">
                        </td>
                        <td><b><?= $r['id'] ?></b></td>
                        <td><?= htmlspecialchars($r['model']) ?></td>
                        <td><?= htmlspecialchars($r['vendor']) ?></td>
                        <td><?= htmlspecialchars($r['price']) ?></td>
                        <td><?= htmlspecialchars($r['manufacturer_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h3>🏭 Виробники (Довідник)</h3>
    <?php if (empty($manufacturers)): ?>
        <p>Записи відсутні.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Назва бренду</th>
                    <th>Країна</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($manufacturers as $m): ?>
                    <tr>
                        <td><b><?= $m['id'] ?></b></td>
                        <td><?= htmlspecialchars($m['name']) ?></td>
                        <td><?= htmlspecialchars($m['country']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>