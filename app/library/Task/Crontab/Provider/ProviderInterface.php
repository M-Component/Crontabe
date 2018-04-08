<?php
namespace Task\Crontab\Provider;
interface ProviderInterface
{

    /**
     * 获取可执行任务列表
     * @return mixed
     */
    public function get();

    /**
     * 任务开始执行，通常用于记录上次执行时间
     * @param $crontab
     * @return mixed
     */
    public function start($crontab);
    public function finish($crontab);
}