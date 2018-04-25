<?php
namespace Member;
use Phalcon\Di;

class Subscribe{

    public function saveSubscribe($member_id ,$goods_id ,$data){

        //需要验证手机号码或者邮箱
        if($data['mobile'] && !\MemberMobile::count("member_id=$member_id AND mobile='{$data['mobile']}'")){
            throw new \Exception('请先验证手机号码');
        }
        if($data['email'] && !\MemberEmail::count("member_id=$member_id AND email='{$data['
email']}'")){
            throw new \Exception('请先验证邮箱');
        }
        $subscribe =\Subscribe::findFirst(array(
            'goods_id'=>(string)$goods_id,
            'member_id'=>(int)$member_id
        ));
        if(!$subscribe){
            //创建
            $subscribe =new \Subscribe();
            $subscribe->price= (float)$data['current_price'];
            $subscribe->create_time=time();
            $subscribe->member_id = $member_id;
            $subscribe->goods_id = $goods_id;
        }else{
            unset($subscribe->id);
        }
        $subscribe->rule =$data['rule'];
        $subscribe->value=$data['value'];
        $subscribe->current_price = (float)$data['current_price'];
        $subscribe->from_time =$data['from_time'];
        $subscribe->to_time =$data['to_time'];
        $subscribe->tag =$data['tag'];
        $subscribe->mobile =$data['mobile'];
        $subscribe->email =$data['email'];
        $subscribe->wechat_notice=$data['wechat_notice'];
        $subscribe->app_notice=$data['app_notice'];
        $subscribe->sms_notice= $data['mobile'] ? 1 :0;
        $subscribe->email_notice=$data['email'] ? 1 :0;

        if(false ===$subscribe->save()){
            foreach ($subscribe->getMessages() as $message) {
                throw new \Exception($message);
            }
            throw new \Exception('保存失败');
        }
        return $subscribe;
    }

    public function getLastPrice($current_price ,$rule ,$value){
        $last_price = false;
        switch($rule){
            //价格大于等于
            case 'gt':
            case 'gte':
                $last_price =$current_price+$value ;
                break;
            //价格小于
            case 'lt':
            case 'lte':
                $last_price =$current_price-$value ;
                break;

            case 'up_percent':
                $last_price = round($current_price*(1+$value/100) ,2)  ;
                break;
            case 'down_percent':
                $last_price = round($current_price*(1-$value/100) ,2)  ;
                break;
            default:
                $last_price =false;
                break;
        }
        return $last_price;
    }


    public function checkRule($last_price ,$rule ,$price){
        $checked = false;
        switch($rule){
            //价格大于
        case 'gt':
        case 'up_percent':
            $checked = $price >$last_price;
            break;
        case 'gte':
            $checked = $price >=$last_price;
            break;

            //价格小于
        case 'lt':
        case 'down_percent':
            $checked = $price <$last_price;
            break;
        case 'lte':
            $checked = $price <=$last_price;
            break;
        default:
            $checked =false;
            break;
        }
        return $checked;
    }
}
