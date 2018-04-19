<?php

namespace Component\Mailer;

interface MailerInterface{

    public function setting();
    public function send($targets,$content,$title);
}

