<?php
// Підключення всіх класів
require_once __DIR__ . '/classes/BaseEntity.php';
require_once __DIR__ . '/classes/BaseRepository.php';
require_once __DIR__ . '/classes/Manufacturer.php';
require_once __DIR__ . '/classes/Router.php';
require_once __DIR__ . '/classes/ManufacturerRepository.php';
require_once __DIR__ . '/classes/RouterRepository.php';

// Ініціалізація екземплярів репозиторіїв з прив'язкою до CSV-файлів
$manufacturerRepo = new ManufacturerRepository(__DIR__ . '/data/manufacturers.csv');
$routerRepo = new RouterRepository(__DIR__ . '/data/routers.csv');