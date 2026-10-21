<?php
abstract class BaseEntity {
    protected $id;

    public abstract function __construct($dataArray);
    public abstract function updateData($dataArray);
    public abstract function getAsArray();

    public function getId() {
        return $this->id;
    }
}