<?php

use Mvc\AdvCollection;
class Subscribe extends AdvCollection
{
    public $member_id;

    public $goods_id;

    public $rule;

    public $value;

    public $wechat_notice;

    public $app_notice;

    public $sms_notice;

    public $email_notice;

    public $create_time;

    public $_id;

    public function get_columns(){
        
    }

    public function initialize()
    {
        //        $this->useImplicitObjectIds(false);
    }
}
