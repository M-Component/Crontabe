<?php
namespace Mvc;
class AdvCollection extends \Phalcon\Mvc\MongoCollection

{

    public static function findById($id){
        $item =parent::findById($id);
        if($item){
            $item->id =(string)$item->getId();
        }
        return $item;
    }

    public static function findFirst(array $parameters = null) {
        $item =parent::findFirst($parameters);
        if($item){
            $item->id =(string)$item->getId();
        }
        return $item;
    }

    public static function find(array $parameters = null) {
        $items =parent::find($parameters);
        foreach($items as &$v){
            $item->id =(string)$v->getId();
        }
        return $items;
    }

    public function batchUpdate(array $data ,$condition=array()){

        $collection = $this->getDI()->getShared('mongo')->selectCollection($this->getSource());

        return $collection->updateMany(
            $condition,
            ['$set' => $data],
            ['multi' =>true ]
        );
    }

    public function beforeSave(){

        if($this->id){
            unset($this->id);
        }
        return true;
    }

    public function afterSave(){
        if($this->_id){
            $this->id = (string)$this->getId();
        }
        return true;
    }

}
