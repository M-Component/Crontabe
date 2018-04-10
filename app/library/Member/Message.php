<?php
namespace Member;
use Phalcon\Di;
use Component\Sms\Send as SmsSender;
use Component\Mailer\Send as EmailSender;

class Message{
    /**
     * @param array $target  目标
     * @param String $type    sms|email
     * @param String $msg_type 模版类型
     * @param array $params
     * @throws \Exception
     */
    public function send($target, $type ,$msg_type ,$params =array()){
        $template = \MessageTemplate::findFirst("type='$type' AND msg_type='$msg_type'");
        if(!$template){
            throw new \Exception('未知的消息模板');
        }
        $view = Di::getDefault()->get('view');
        $search = $replace = array();
        foreach($params as $k=>$v){
            $search[] ='{{'.$k.'}}';
            $replace[] =$v;
        }
        $content =str_replace($search ,$replace ,$template ->content);
        if($msg_type =='sms'){
            $sender =  new SmsSender();
            $send_target[] =array(
                'target'=>$target,
                'member_id' =>$params['member_id'] ? :0
            );
           return $sender->send($send_target,$content);
        }elseif($msg_type =='email'){
            $sender =  new EmailSender();
            $send_target[] =array(
                'target'=>$target,
                'member_id' =>$params['member_id'] ? :0
            );
            return $sender->send($send_target,$content);
        }else{
            throw new \Exception('不支持的通知类型');
        }
    }
}
