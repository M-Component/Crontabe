<?php

class MessageController extends BackstageController
{
    var $model_name = 'Message';
    var $index_title = '用户消息';
    var $custom_action = array(
        'use_add' => false
    );

    function testAction(){

    }
}
