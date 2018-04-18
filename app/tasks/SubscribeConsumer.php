<?php

use Phalcon\Di;
class SubscribeConsumer implements \Task\TaskInterface{

    private $set_key ='subscribe_goods_zset';

    private $goods;

    public function exec($job =null ,$params = null){
        $this->redisDb =Di::getDefault()->getShared('redisDb');
        $this->checker = new \Member\Subscribe;
        
        $goods_row = $this->redisDb->zrange($this->set_key ,0 ,0);
        if(empty($goods_row)){
            return true;
        }
        $goods_row =$goods_row[0];
        if(! $this->redisDb->zrem($this->set_key, $goods_row)){
            return true;
        }

        $this->goods =json_decode($goods_row);
        $goods_id =$this->goods['goods_id'];
        $price =$this->goods['price'];
        //更新订阅记录的价格
        $subscribe = new \Subscribe();
        $subscribe->batchUpdate(array('last_price'=>$price),array('goods_id' =>$goods_id));

        $pattern = 'subscribe-'.$goods_id.'-*';
        $it = null;
        $limit =100;

        do  
        {  
            $keys_arr = $this->redisDb->scan($it, $pattern, $limit); 
            if ($keys_arr)  
            {  
                foreach ($keys_arr as $key)  
                {
                    $value =$this->redisDb->get($key);
                    $value = json_decode($value,1);

                    $rule =$value[0];
                    $value =$value[1];
                    $notice_price =$value[2];//临界提醒价格
                    $curent_price =$value[3];//订阅时候的价格
                    $subscribe_id =$value[4];

                    if($this->checker->checkRule($notice_price ,$rule ,$price)){
                        $this->redisDb->delete($key);
                        $this->_createMessage($subscribe_id ) ;
                    }
                }  
            }  
  
        } while ($it > 0);

        return true;
    }

    private function _createMessage($subscribe_id, $goods){

        $subscribe = \Subscribe::findById($subscribe_id);
        if(!$subscribe){
            return true;
        }
        $message =array(
            'subscribe_id' =>$subscribe->_id,
            'member_id' =>$subscribe->member_id,
            'rule' =>$subscribe->rule,
            'curent_price'=>$subscribe->curent_price,
            'from'=>$subscribe->from,
            'to' =>$subscribe->to,
            'goods_id' =>$this->goods['goods_id'],
            'goods_name' =>$this->goods['goods_name'],
            'goods_link' =>$this->goods['goods_link'],
            'price' =>$this->goods['price'],
            'time'=>time()
        );
        if($subscribe->sms_notice){
            $message['target'] =$subscribe->mobile;
            $this->redisDb->rpush('subscribe_sms' ,json_encode($message));
        }
        if($subscribe->email_notice){
            $message['target'] =$subscribe->email;
            $this->redisDb->rpush('subscribe_email' ,json_encode($message));
        }
        if($subscribe->wechat_notice){
            $message['target'] =$subscribe->openid;
            $this->redisDb->rpush('subscribe_wechat' ,json_encode($message));
        }
        if($subscribe->app_notice){
            $this->redisDb->rpush('subscribe_app' ,json_encode($message));
        }

    }
}
