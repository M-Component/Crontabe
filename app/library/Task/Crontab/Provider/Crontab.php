<?php
namespace Task\Crontab\Provider;
use Phalcon\Di;
abstract class Crontab{
    abstract public function get();

    protected $cache_key ='crontab-cache';

    protected $config;

    public function setConfig($config){
        $this ->config = $config;
    }

    public function setCache($data){
        $di = DI::getDefault();
        $cache = $di->get('cache');
        $cache->save($this ->cache_key , $data);
    }

    public function getCache(){
        $di = DI::getDefault();
        $cache = $di->get('cache');
        return $cache->get($this ->cache_key);
    }


}