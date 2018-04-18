<?php
namespace Sender;
interface SenderInterface
{
    public function send(array $targets ,$content ,$title='');
    public function sendOne($target ,$content ,$title='');
    public function sendList(array $target_contents);
}
