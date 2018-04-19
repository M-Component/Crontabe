<?php
namespace Sender;
interface SenderInterface
{
    public function send(array $targets ,$content ,$title='', $extend_params= null);
    public function sendOne($target ,$content ,$title='',$extend_params = null);
    public function sendList(array $target_contents);
}
