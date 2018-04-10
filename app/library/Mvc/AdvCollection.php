<?php
namespace Mvc;
class AdvCollection extends \Phalcon\Mvc\MongoCollection

{
    public static function findById($id){
        $item =parent::findById($id);
        if($item){
            $item->_id =(string)$item->getId();
        }
        return $item;
    }

    public static function findFirst(array $parameters = null) {
        $item =parent::findFirst($parameters);
        if($item){
            $item->_id =(string)$item->getId();
        }
        return $item;
    }

    public static function find(array $parameters = null) {
        $items =parent::find($parameters);
        foreach($items as &$v){
            $v->_id =(string)$v->getId();
        }
        return $items;
    }
}
