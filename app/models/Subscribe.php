<?php
//use Phalcon\Mvc\Collection;
use Phalcon\Mvc\MongoCollection as Collection;
class Subscribe extends Collection
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
}
