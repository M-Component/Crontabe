<?php
/**
 * Created by PhpStorm.
 * User: wl
 * Date: 17/6/18
 * Time: 下午5:05
 */
namespace Mvc;
class DbFilter{
    public static function filter($filter=array()){
        $where = array();
        $filter =(array)$filter;
        foreach($filter as $k=>$v){
            $k_arr = explode('|' ,$k);
            $column = $k_arr[0];
            $type = $k_arr[1];
            if(is_null($v)){
                $where[] = "`$column` IS NULL";
                continue;
            }elseif($k=='filter_sql'){
                $where[] = $v;
            }elseif($type){
                $v = self::_parse_filter($type ,$v);
                $where[] = $column.$v;
            }else{
                $where[] = $column.'='.('\''.$v.'\'');
            }
        }
        return implode(' AND ',$where);
    }

    private static function _parse_filter($type, $var) {
        $FilterArray = array(
            'than' => ' > ' . $var,
            'lthan' => ' < ' . $var,
            'nequal' => ' = \'' . $var . '\'',
            'noequal' => ' <> \'' . $var . '\'',
            'tequal' => ' = \'' . $var . '\'',
            'sthan' => ' <= ' . $var,
            'bthan' => ' >= ' . $var,
            'has' => ' like \'%' . $var . '%\'',
            'head' => ' like \'' . $var . '%\'',
            'foot' => ' like \'%' . $var . '\'',
            'nohas' => ' not like \'%' . $var . '%\'',
            'between' => ' {field}>=' . $var[0] . ' and ' . ' {field}<=' . $var[1],
            'in' => " in ('" . implode("','", (array)$var) . "') ",
            'notin' => " not in ('" . implode("','", (array)$var) . "') ",
        );
        return $FilterArray[$type];
    }
}
