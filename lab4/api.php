<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {
    // 1. READ (GET) — Отримання одного або всіх записів
    case 'GET':
        if ($id !== null) {
            $router = $routerRepo->findById($id);
            if ($router) {
                $m = $manufacturerRepo->findById($router['manufacturerId']);
                $router['manufacturer_name'] = $m ? $m['name'] : 'Невідомо';
                http_response_code(200);
                echo json_encode(['status' => 'success', 'data' => $router], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => "Запис з ID {$id} не знайдено"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            $routers = $routerRepo->findAllWithManufacturers($manufacturerRepo);
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $routers], JSON_UNESCAPED_UNICODE);
        }
        break;

    // 2. CREATE (POST) — Додавання нового запису в CSV
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['model'], $input['vendor'], $input['price'], $input['manufacturerId'])) {
            $newId = $routerRepo->add([
                'model' => $input['model'],
                'vendor' => $input['vendor'],
                'price' => $input['price'],
                'manufacturerId' => $input['manufacturerId']
            ]);
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Запис створено та збережено в CSV', 'id' => $newId], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Передано неповні дані'], JSON_UNESCAPED_UNICODE);
        }
        break;

    // 3. UPDATE (PUT) — Оновлення запису в CSV
    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        if ($id !== null && isset($input['model'], $input['vendor'], $input['price'], $input['manufacturerId'])) {
            $input['id'] = $id;
            $updated = $routerRepo->updateById($input);
            if ($updated) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => "Запис ID {$id} оновлено в CSV"], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => "Запис ID {$id} не знайдено"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Не вказано ID або дані для оновлення'], JSON_UNESCAPED_UNICODE);
        }
        break;

    // 4. DELETE (DELETE) — Видалення запису з CSV
    case 'DELETE':
        if ($id !== null) {
            $deleted = $routerRepo->deleteById($id);
            if ($deleted) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => "Запис ID {$id} видалено з CSV"], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => "Запис ID {$id} не знайдено"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Не вказано ID для видалення'], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Метод не підтримується'], JSON_UNESCAPED_UNICODE);
        break;
}