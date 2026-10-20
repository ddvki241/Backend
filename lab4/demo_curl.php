<?php
// Автоматичне визначення повної URL-адреси до api.php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$dir = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$apiUrl = "{$protocol}://{$host}{$dir}/api.php";

$searchId = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($searchId !== '') {
    $apiUrl .= '?id=' . urlencode($searchId);
}

// Виконання cURL-запиту
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна №4 - Демонстрація cURL</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 24px; background: #f8fafc; color: #0f172a; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 800px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; }
        th { background-color: #f1f5f9; }
        .controls { margin-bottom: 15px; display: flex; gap: 10px; align-items: center; }
        input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; }
        button { padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #059669; }
        .btn-reset { background: #64748b; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🐘 Демонстрація cURL запиту у PHP</h2>

        <form method="GET" class="controls">
            <input type="number" name="id" value="<?= htmlspecialchars($searchId) ?>" placeholder="Введіть ID...">
            <button type="submit">Надіслати cURL запит</button>
            <a href="demo_curl.php" class="btn-reset"><button type="button" class="btn-reset">Скинути</button></a>
        </form>

        <p><b>Запитаний URL:</b> <code><?= htmlspecialchars($apiUrl) ?></code></p>
        <p><b>HTTP Код відповіді:</b> <code><?= $httpCode ?></code></p>

        <?php if ($httpCode === 200 && isset($result['data'])): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Модель</th>
                        <th>Вендор</th>
                        <th>Ціна (грн)</th>
                        <th>Виробник</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $dataList = isset($result['data'][0]) ? $result['data'] : [$result['data']];
                    foreach ($dataList as $item): 
                    ?>
                        <tr>
                            <td><b><?= htmlspecialchars($item['id']) ?></b></td>
                            <td><?= htmlspecialchars($item['model']) ?></td>
                            <td><?= htmlspecialchars($item['vendor']) ?></td>
                            <td><?= htmlspecialchars($item['price']) ?></td>
                            <td><?= htmlspecialchars($item['manufacturer_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #ef4444; font-weight: 600;">
                Помилка: <?= htmlspecialchars($result['message'] ?? 'Не вдалося отримати дані через cURL') ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>