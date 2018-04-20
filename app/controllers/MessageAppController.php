<?php

class MessageAppController extends BackstageController
{
    var $model_name = 'MessageApp';
    var $index_title = 'APP消息';
    var $custom_action = array(
        'use_add' => false
    );
    public function jpushAction(){
        $sender = new \Sender\App();
        $target ='1a0018970a8b3045795';
        $res =$sender->sendOne($target , '' ,'' ,array(
            'message' =>'hello world !'
        ));
        var_dump($res);
        exit;

    }
}
