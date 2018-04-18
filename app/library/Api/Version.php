<?php
namespace Api;
class Version extends Base
{
    public function getVersion()
    {
        $setting = \Setting::getConf('version');
        if ($setting){
            $this->success($setting);
        }
        $this->error('获取失败');
    }
}