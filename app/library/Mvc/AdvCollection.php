<?php
namespace Mvc;
class AdvCollection extends \Phalcon\Mvc\MongoCollection

{

    public static function findById($id){
        $item =parent::findById($id);
        if($item){
            $item->_id =(string)$item->getId();
            $item->id =$item->id;
        }
        return $item;
    }

    public static function findFirst(array $parameters = null) {
        $item =parent::findFirst($parameters);
        if($item){
            $item->_id =(string)$item->getId();
            $item->id =$item->_id;
        }
        return $item;
    }

    public static function find(array $parameters = null) {
        $items =parent::find($parameters);
        foreach($items as &$v){
            $v->_id =(string)$v->getId();
            $v->id =$v->_id;
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
