<?php

namespace Mvc;
class CollectionFilter{
    public static function filter($filter=array()){
        $where = array();
        $filter =(array)$filter;
        foreach($filter as $k=>$v){
            $k_arr = explode('|' ,$k);
            $column = $k_arr[0];
            $type = $k_arr[1];
            if(is_null($v)){
                $where[] = array(
                    $k=>null
                );
                continue;
            }elseif($type){
                $v = self::_parse_filter($type ,$v);
                $where[] = array(
                    $k=>$v
                );
            }else{
                $where[] = array(
                    $k=>$v
                );
            }
        }
        return $where;
    }


    public static function sort($orderBy=''){
        $order =array();
        if(!empty($orderBy)){
            $orderBy =explode(',',$orderBy);
            foreach($orderBy as $v){
                $vv =explode(' ', trim($v));
                if($v[1] && strtolower($v[1]) =='desc'){
                    $order[$vv[0]] =$vv[1];
                }else{
                    $order[$vv[0]] =1;
                }

            }            
        }
        return $order;
    }

    private static function _parse_filter($type, $var) {
        $FilterArray = array(
            'than' => ['$gt'=>$var],
            'lthan' => ['$lt'=>$var],
            'nequal' => ['$eq'=>$var],
            'noequal' => ['$ne'=>$var],
            'sthan' => ['$lte'=>$var],
            'bthan' => ['$gte'=>$var],
            'has' => ['$regex'=>$var],
            'between' => [
                '$gte' =>$var[0],
                '$lt' =>$var[1]
            ],
            'in' => ['$in'=>$var],
            'notin' => ['$nin'=>$var],
        );
        return $FilterArray[$type];
    }
}
