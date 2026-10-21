<?php
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/Manufacturer.php';

class ManufacturerRepository extends BaseRepository {

    protected function loadFromCsv() {
        $this->list = [];
        $this->lastid = 0;

        if (!file_exists($this->csvFilePath)) {
            return;
        }

        if (($handle = fopen($this->csvFilePath, 'r')) !== FALSE) {
            fgetcsv($handle, 1000, ','); // Пропуск заголовка
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (count($data) >= 3) {
                    $m = new Manufacturer([
                        'id' => $data[0],
                        'name' => $data[1],
                        'country' => $data[2]
                    ]);
                    $this->list[] = $m;
                    if ($m->getId() > $this->lastid) {
                        $this->lastid = $m->getId();
                    }
                }
            }
            fclose($handle);
        }
    }

    protected function saveToCsv() {
        if (($handle = fopen($this->csvFilePath, 'w')) !== FALSE) {
            fputcsv($handle, ['id', 'name', 'country']);
            foreach ($this->list as $item) {
                $arr = $item->getAsArray();
                fputcsv($handle, [$arr['id'], $arr['name'], $arr['country']]);
            }
            fclose($handle);
        }
    }

    public function add($dataArray) {
        $this->lastid++;
        $dataArray['id'] = $this->lastid;
        $m = new Manufacturer($dataArray);
        $this->list[] = $m;
        $this->saveToCsv();
        return $dataArray['id'];
    }
}