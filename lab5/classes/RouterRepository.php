<?php
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/Router.php';

class RouterRepository extends BaseRepository {

    protected function loadFromCsv() {
        $this->list = [];
        $this->lastid = 0;

        if (!file_exists($this->csvFilePath)) {
            return;
        }

        if (($handle = fopen($this->csvFilePath, 'r')) !== FALSE) {
            fgetcsv($handle, 1000, ','); // Пропуск заголовка
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (count($data) >= 5) {
                    $r = new Router([
                        'id' => $data[0],
                        'model' => $data[1],
                        'vendor' => $data[2],
                        'price' => $data[3],
                        'manufacturerId' => $data[4],
                        'image' => $data[5] ?? 'uploads/default.png'
                    ]);
                    $this->list[] = $r;
                    if ($r->getId() > $this->lastid) {
                        $this->lastid = $r->getId();
                    }
                }
            }
            fclose($handle);
        }
    }

    protected function saveToCsv() {
        if (($handle = fopen($this->csvFilePath, 'w')) !== FALSE) {
            fputcsv($handle, ['id', 'model', 'vendor', 'price', 'manufacturerId', 'image']);
            foreach ($this->list as $item) {
                $arr = $item->getAsArray();
                fputcsv($handle, [$arr['id'], $arr['model'], $arr['vendor'], $arr['price'], $arr['manufacturerId'], $arr['image']]);
            }
            fclose($handle);
        }
    }

    public function add($dataArray) {
        $this->lastid++;
        $dataArray['id'] = $this->lastid;
        $r = new Router($dataArray);
        $this->list[] = $r;
        $this->saveToCsv();
        return $dataArray['id'];
    }

    public function findAllWithManufacturers($manufacturerRepo) {
        $result = [];
        foreach ($this->list as $item) {
            $arr = $item->getAsArray();
            $m = $manufacturerRepo->findById($arr['manufacturerId']);
            $arr['manufacturer_name'] = $m ? $m['name'] : 'Невідомо';
            $result[] = $arr;
        }
        return $result;
    }
}