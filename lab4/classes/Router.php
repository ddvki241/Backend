<?php
require_once __DIR__ . '/BaseEntity.php';

class Router extends BaseEntity {
    private $model;
    private $vendor;
    private $price;
    private $manufacturerId;

    public function __construct($dataArray) {
        $this->id = (int)$dataArray['id'];
        $this->model = $dataArray['model'];
        $this->vendor = $dataArray['vendor'];
        $this->price = (float)$dataArray['price'];
        $this->manufacturerId = (int)$dataArray['manufacturerId'];
    }

    public function getAsArray() {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'vendor' => $this->vendor,
            'price' => $this->price,
            'manufacturerId' => $this->manufacturerId
        ];
    }

    public function updateData($dataArray) {
        $this->model = $dataArray['model'];
        $this->vendor = $dataArray['vendor'];
        $this->price = (float)$dataArray['price'];
        $this->manufacturerId = (int)$dataArray['manufacturerId'];
    }
}