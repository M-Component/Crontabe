<?php
namespace Member;
use Phalcon\Di;
use Component\Sms\Send as SmsSender;

class Message{
    public function send($target, $type ,$msg_type ,$params =array()){
        $template = \MessageTemplate::findFirst("type='$type' AND msg_type='$msg_type'");
        if(!$template){
            throw new \Exception('未知的消息模板');
        }
        $view = Di::getDefault()->get('view');
        $search = $replace=array();
        foreach($params as $k=>$v){
            $search[] ='{{'.$k.'}}';
            $replace =$v;
        }
        $content =str_replace($search ,$replace ,$template ->content);
        if($msg_type =='sms'){
            $sender =  new SmsSender();
            $target[] =array(
                'mobile'=>$target,
                'member_id' =>$params['member_id'] ? :$member_id
            );
        }elseif($msg_type =='email'){
            $sender =  new SmsSender();
            $target[] =array(
                'email'=>$target,
                'member_id' =>$params['member_id'] ? :$member_id
            );
        }else{
            throw new \Exception('不支持的通知类型');
        }
    }
}
