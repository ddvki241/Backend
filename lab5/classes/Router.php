<?php
require_once __DIR__ . '/BaseEntity.php';

class Router extends BaseEntity {
    private $model;
    private $vendor;
    private $price;
    private $manufacturerId;
    private $image;

    public function __construct($dataArray) {
        $this->id = isset($dataArray['id']) ? (int)$dataArray['id'] : 0;
        $this->model = $dataArray['model'];
        $this->vendor = $dataArray['vendor'];
        $this->price = (float)$dataArray['price'];
        $this->manufacturerId = (int)$dataArray['manufacturerId'];
        $this->image = !empty($dataArray['image']) ? $dataArray['image'] : 'uploads/default.png';
    }

    public function getAsArray() {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'vendor' => $this->vendor,
            'price' => $this->price,
            'manufacturerId' => $this->manufacturerId,
            'image' => $this->image
        ];
    }

    public function updateData($dataArray) {
        $this->model = $dataArray['model'];
        $this->vendor = $dataArray['vendor'];
        $this->price = (float)$dataArray['price'];
        $this->manufacturerId = (int)$dataArray['manufacturerId'];
        if (!empty($dataArray['image'])) {
            $this->image = $dataArray['image'];
        }
    }
}