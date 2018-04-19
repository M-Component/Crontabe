<?php
/**
 * Created by PhpStorm.
 * User: medivh
 * Date: 2017/9/6
 * Time: 11:24
 */

namespace Component\Sms;


interface SmsInterface
{
    public function setting();
    public function send(array $targets,$content);
    public function sendOne($targets,$content);
    public function batchSend($target_contents);
}
