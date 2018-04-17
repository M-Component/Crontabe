<?php

namespace Component\MsgPush;

interface MsgPushInterface{

    public function setting();
    public function send(array $registrationId,$alert,$title,$message);
    public function sendOne($registrationId,$alert,$title,$message);
}