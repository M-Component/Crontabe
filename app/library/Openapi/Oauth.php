<?php

namespace Openapi;
use Exception;
class Oauth extends Base
{
    public function callback($oauth_class)
    {
        $params = $this ->request ->get();
        $oauth_class = "\\Component\\Oauth\\Pam\\".ucfirst($oauth_class);
        if (!class_exists($oauth_class)) {
            throw new Exception('Oauth应用程序错误');
        }
        $oauth_instance = new $oauth_class();
        if (!method_exists($oauth_instance, 'callback')) {
            throw new Exception('Oauth应用程序错误');
        }
        $oauth_instance->callback($params);
    }
}
