<?php
namespace Event\Model;
use Phalcon\Di;
class SubscribeManager{
    protected $redisDb;

    public function __construct(){
        $this->redisDb = Di::getDefault()->getShared('redisDb');
    }

    public function afterSave(\Subscribe $subscribe){
        $key_arr =array(
            $subscribe->goods_id,
            $subscribe->member_id,
        );
        $key_str =implode('-' , $key_arr);
        $key = 'subscribe-'.$key_str;


        $value =array(
            $subscribe->rule,
            $subscribe->value,
            $subscribe->notice_price,
            $subscribe->current_price,
            $subscribe->id
        );
        $this->redisDb->set($key ,json_encode($value));
        $this->redisDb->sadd('subscribe_goods_set',$subscribe->goods_id);
    }
}
