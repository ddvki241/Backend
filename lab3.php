<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

    abstract class BaseEntity{
        protected $id;
        public abstract function __construct($dataArray);
        public abstract function updateData($dataArray);
        public abstract function getAsArray();
        public function getId(){
            return $this->id;
        }
    }

    class Router extends BaseEntity{
        private $model;
        private $vendor;
        private $price;
        private $manufacturerId;

        public function __construct($dataArray){
            $this->id = $dataArray['id'];
            $this->model = $dataArray['model'];
            $this->vendor = $dataArray['vendor'];
            $this->price = $dataArray['price'];
            $this->manufacturerId = $dataArray['manufacturerId'];
        }

        public function getAsArray(){
            return [
                'id' => $this->id,
                'model' => $this->model,
                'vendor' => $this->vendor,
                'price' => $this->price,
                'manufacturerId' => $this->manufacturerId
            ];
        }

        public function displayData($manufacturerRepository){
            $manufacturerData = $manufacturerRepository->findById($this->manufacturerId);
            echo "{$this->id}. {$this->vendor} {$this->model}. <i>Ціна: {$this->price}</i>, Виробник {$manufacturerData['name']}</br>";
        }

        public function updateData($dataArray){
            $this->model = $dataArray['model'];
            $this->vendor = $dataArray['vendor'];
            $this->price = $dataArray['price'];
            $this->manufacturerId = $dataArray['manufacturerId'];
        }
    }

    class Manufacturer extends BaseEntity{
        private $name;

        public function __construct($dataArray){
            $this->id = $dataArray['id'];
            $this->name = $dataArray['name'];
        }

        public function getAsArray(){
            return [
                'id' => $this->id,
                'name' => $this->name
            ];
        }

        public function displayData(){
            echo "{$this->id}. {$this->name}</br>";
        }

        public function updateData($dataArray){
            $this->name = $dataArray['name'];
        }
    }

    abstract class BaseRepository{
        protected $list;
        protected $lastid;

        public function __construct(){
            $this->list = [];
            $this->lastid = 0;
        }

        public abstract function add($dataArray);

        public function findById($id){
            for ($i = 0; $i < count($this->list); $i++){
                if($this->list[$i]->getId() == $id){
                    return $this->list[$i]->getAsArray();
                }
            }
        }

        public function updateById($dataArray){
            for ($i = 0; $i < count($this->list); $i++){
                if($this->list[$i]->getId() == $dataArray['id']){
                    $this->list[$i]->updateData($dataArray);
                    break;
                }
            }
        }

        public function deleteById($id){
            for ($i = 0; $i < count($this->list); $i++){
                if($this->list[$i]->getId() == $id){
                    array_splice($this->list, $i, 1);
                    break;
                }
            }
        }
    }

    class ManufacturerRepository extends BaseRepository{
        public function add($dataArray){
            $this->lastid++;
            $dataArray['id'] = $this->lastid;
            $cat = new Manufacturer($dataArray);
            array_push($this->list, $cat);
        }

        public function displayAll(){
            for ($i = 0; $i < count($this->list); $i++){
                $this->list[$i]->displayData();
            }
        }
    }

    class RouterRepository extends BaseRepository{
        public function add($dataArray){
            $this->lastid++;
            $dataArray['id'] = $this->lastid;
            $r = new Router($dataArray);
            array_push($this->list, $r);
        }

        public function displayAll($manufacturerRepository){
            for ($i = 0; $i < count($this->list); $i++){
                $this->list[$i]->displayData($manufacturerRepository);
            }
        }
    }

    $repo1 = new ManufacturerRepository();
    $repo1->add(['name' => "TP-Link"]);
    $repo1->add(['name' => "ASUS"]);

    $repo2 = new RouterRepository();
    $repo2->add([
        'model' => 'Archer AX12',
        'vendor' => 'TP-Link',
        'price' => 1800,
        'manufacturerId' => 1
    ]);
    $repo2->add([
        'model' => 'TUF Gaming AX4200',
        'vendor' => 'ASUS',
        'price' => 4200,
        'manufacturerId' => 2
    ]);

    $repo2->displayAll($repo1);

    $repo2->updateById([
        'id' => 1,
        'model' => 'TUF Gaming AX4200',
        'vendor' => 'ASUS',
        'price' => 4200,
        'manufacturerId' => 2
    ]);

    $repo2->displayAll($repo1);

    $repo2->deleteById(1);

    $repo2->displayAll($repo1);
?>
