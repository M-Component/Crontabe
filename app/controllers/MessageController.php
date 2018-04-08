<?php

class MessageController extends BackstageController
{
    var $model_name = 'Message';
    var $index_title = '短信消息';
    var $custom_action = array(
        'use_add' => false
    );
}