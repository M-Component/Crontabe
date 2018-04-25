<?php
namespace Sender;

use Wechat\OfficialAccount;

class Wechat implements SenderInterface{

    private $official_account;
    public function __construct(){
        $this->official_account = new OfficialAccount();
    }
    public function send(array $target, $content, $title = '', $extend_params=null){

        foreach($target as $item){
            $params = array(
                'touser'=> $item,
                'url'=>$extend_params['url'],
                'template_id'=>$extend_params['template_id'],
                'miniprogram'=>$extend_params['miniprogram']?:array(),
                'data'=>$content,
                'color'=>$extend_params['color']
            );
            return $this->official_account->sendTempMsg($params);
        }
    }

    public function sendOne($target, $content, $title = '', $extend_params=null){
        $params = array(
            'touser'=> $target,
            'url'=>$extend_params['url'],
            'template_id'=>$extend_params['template_id'],
            'miniprogram'=>$extend_params['miniprogram']?:array(),
            'data'=>$content,
            'color'=>$extend_params['color'],
        );
        return $this->official_account->sendTempMsg($params);
    }


    public function sendList(array $target_contents){
        foreach($target_contents as $item){
         /*   $params = array(
                'touser'=> $item['touser'],
                'url'=>$item['url'],
                'template_id'=>$item['template_id'],
                'miniprogram'=>$item['miniprogram'],
                'data'=>$item['data'],
                'color'=>$item['color'],
                );*/
            return $this->official_account->sendTempMsg($item);
        }
    }


}
