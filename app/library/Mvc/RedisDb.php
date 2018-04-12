<?php
namespace Mvc;
class RedisDb extends \Redis{

    public function zaddArray($key ,$items){
        if (!$key || !is_array($items)){
            return false;
        }
        $p[] =$key;
        foreach($items as $item){
            $p[]= $item['score'];
            $p[]= $item['value'];
        }
        return  call_user_func_array(array($this ,'zadd') ,$p);
    }

    public function lpushArray($key ,$values){
        return  call_user_func_array(array($this ,'lpush') ,array_merge([$key] ,$values));
    }

    public function rpushArray($key ,$values){
        return  call_user_func_array(array($this ,'rpush') ,array_merge([$key] ,$values));
    }
}
