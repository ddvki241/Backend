<?php
require_once __DIR__ . '/BaseEntity.php';

class Manufacturer extends BaseEntity {
    private $name;
    private $country;

    public function __construct($dataArray) {
        $this->id = (int)$dataArray['id'];
        $this->name = $dataArray['name'];
        $this->country = $dataArray['country'] ?? 'Не вказано';
    }

    public function getAsArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country
        ];
    }

    public function updateData($dataArray) {
        $this->name = $dataArray['name'];
        if (isset($dataArray['country'])) {
            $this->country = $dataArray['country'];
        }
    }
}