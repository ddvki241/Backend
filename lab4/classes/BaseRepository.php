<?php
abstract class BaseRepository {
    protected $list = [];
    protected $lastid = 0;
    protected $csvFilePath;

    public function __construct($csvFilePath) {
        $this->csvFilePath = $csvFilePath;
        $this->loadFromCsv();
    }

    abstract protected function loadFromCsv();
    abstract protected function saveToCsv();
    abstract public function add($dataArray);

    public function findAll() {
        $result = [];
        foreach ($this->list as $item) {
            $result[] = $item->getAsArray();
        }
        return $result;
    }

    public function findById($id) {
        foreach ($this->list as $item) {
            if ($item->getId() == $id) {
                return $item->getAsArray();
            }
        }
        return null;
    }

    public function updateById($dataArray) {
        foreach ($this->list as $item) {
            if ($item->getId() == $dataArray['id']) {
                $item->updateData($dataArray);
                $this->saveToCsv();
                return true;
            }
        }
        return false;
    }

    public function deleteById($id) {
        for ($i = 0; $i < count($this->list); $i++) {
            if ($this->list[$i]->getId() == $id) {
                array_splice($this->list, $i, 1);
                $this->saveToCsv();
                return true;
            }
        }
        return false;
    }
}