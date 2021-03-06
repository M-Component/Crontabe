<?php
namespace Member;
use Phalcon\Di;
use Component\Vcode;
use Sender\Sms as SmsSender;
use Sender\Email as EmailSender;
use Sender\App as AppSender;
use Sender\Wechat as WechatSender;

class Message{

    private $sender;

    private $log_model;

    private $template;

    private $msg_type;


    public function setTemplate($template){
        $this->template =$template;
        return $this;
    }

    public function setMsgType($msg_type){
        $this->msg_type =$msg_type;
        return $this;
    }

    public function sendVcode(array $target ,$params=array()){
        $vcodeObj = new Vcode();
        $vcode =$vcodeObj->setVcode($target['target'] ,$this->template);
        $params['vcode'] =$vcode;
        return $this->sendOne($target ,$params);
    }

    //['target'=>13533454356]
    public function sendOne(array $target ,array  $params ,$extend_params = null){
        return $this->send([$target] ,$params ,$extend_params);
    }

    /**
     * @param array $targets    发送目标
     * @param array $params     主要用于和模版吻合参数
     * @param null $extend_params   拓展参数，主要用于app消息推送
     * @return mixed
     * @throws \Exception
     */
    public function send(array $targets , array $params ,$extend_params =null ){
        $this->_getSender();

        $send_targets = array();
        $title = '';
        if($this->msg_type == 'wechat'){
            foreach($targets as $target){
                $send_targets[]= $target['target'];
            }
            $content = $params; // 模版里面的 data 数据

        }else{
            $template = \MessageTemplate::findFirst("template='{$this->template}' AND msg_type='{$this->msg_type}'");
            if(!$template){
                throw new \Exception('未知的消息模板');
            }
            $content =$template->content;
            $this->_getContent($content ,$params);
            $title = $template->title?:$this->_getTitle($content);
            $send_targets =array();
            foreach($targets as $target){
                $send_targets[]= $target['target'];
            }
        }
        $this->sender->send($send_targets ,$content, $title,$extend_params);

        $log_data =[];
        $time =time();
        foreach($targets as $target){
            $log_data[] =array(
                'member_id' =>$target['member_id'] ? :0,
                'target'=>$target['target'],
                'title'=>$title,
                'content'=>$content,
                'create_time' =>$time
            );
        }
        return $this->log_model->batchCreate($log_data);
    }

    public function batchSend(array $target_contents){
        $this->_getSender();

        $log_data = $send_data =[];
        $time =time();
        if($this->msg_type == 'wechat'){
            foreach($target_contents as $key=>$value){
                $send_data[] = [
                    'touser'=>$value['target'],
                    'url'=>$value['url'],
                    'miniprogram'=> $value['miniprogram']?:array(),
                    'template_id' => $value['template_id'],
                    'data'=>$value['data'],
                    'color'=>$value['color']?:''
                ];

                $log_data[] = [
                    'member_id' => $value['member_id']?:0,
                    'target'=> $value['target'],
                    'title'=> $value['title']?:'微信消息推送',
                    'content'=> serialize($value['data']),
                    'create_time'=> $time,
                ];
            }
        }else{
            $templates =\MessageTemplate::find("msg_type='{$this->msg_type}'")->toArray();
            $templates =\Utils::array_change_key($templates ,'template');

            foreach($target_contents as $k => $v){
                $target =$v['target'];
                $template =$templates[$v['template']];
                $content =$template['content'];
                $this->_getContent($content ,$v['params']);
                $title =$v['title'] ?: $this->_getTitle($content ,$v);
                $send_data[]=[
                    'target'=>$target,
                    'content' =>$content,
                    'title' =>$title,
                ];

                $log_data[] =[
                    'member_id' =>$v['member_id'] ? :0,
                    'target'=>$target,
                    'title' =>$title,
                    'content' =>$content,
                    'create_time' =>$time
                ];
            }            
        }

        $this->sender->sendList($send_data);
        return $this->log_model->batchCreate($log_data);
    }


    private function _getSender(){
        switch($this->msg_type){
        case 'sms':
            $this->sender =  new SmsSender();
            $this->log_model =new \MessageSms();
            break;
        case 'email':
            $this->sender =  new EmailSender();
            $this->log_model =new \MessageEmail();
            break;
        case 'wechat':
            $this->sender =  new WechatSender();
            $this->log_model =new \MessageWechat();
            break;
        case 'app':
            $this->sender =  new AppSender();
            $this->log_model =new \MessageApp();
            break;
        default:
            throw new \Exception('不支持的通知类型');
        }
    }

    private function _getContent(&$content ,$params){
        $search = $replace = array();
        foreach($params as $k=>$v){
            $search[] ='{{'.$k.'}}';
            $replace[] =$v;
        }
        $content =str_replace($search ,$replace ,$content);
    }

    private function  _getTitle($content){
        $limit =50;
        return mb_substr(strip_tags($content) ,0 ,$limit).'...';
    }
}
