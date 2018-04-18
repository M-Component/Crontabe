<?php

namespace Component\Mailer;

interface MailerInterface{
    public function setting();
    public function send($target,$conetn,$title);
}

