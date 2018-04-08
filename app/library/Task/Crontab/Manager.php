<?php
namespace Task\Crontab;
use Closure;
use Phalcon\Di;
use Task\Crontab\Provider\DatabaseProvider;
use Task\Crontab\Provider\FileProvider;

class Manager{
    private static $instance;

    private $provider;

    private $providers =array();

    private $handles=array();

    private function __construct($config=null)
    {
        $config = Di::getDefault() ->get('config');
        $default= $config ->cron ->default;
        $this->container['config']['default'] =$default;
        $this->container['config']['provider']= $config ->cron ->provider->toArray();
        $this->registerProviders();
    }

    public static function getInstance($config=null)
    {
        if(!self::$instance){
            self::$instance = new Manager($config);
        }
        return self::$instance;
    }

    public function getCrontab($name =null)
    {
        $name = $name ?: $this->getDefaultProvider();
        return $this->getHandel($name)->get();
    }

    public function start($crontab ,$name=null)
    {
        $name = $name ?: $this->getDefaultProvider();
        return $this->getHandel($name)->start($crontab);
    }

    public function finish($crontab ,$name=null)
    {
        $name = $name ?: $this->getDefaultProvider();
        return $this->getHandel($name)->finish($crontab);
    }

    public function getHandel($name){
        $name = $name ?: $this->getDefaultProvider();
        if(!isset($this->handles[$name])){
            $this->handles[$name] = $this->resolve($name);
        }
        $this->handles[$name]->setConfig($this->container['config']['provider'][$name]);
        return $this->handles[$name];
    }


    private function getDefaultProvider(){
        return $this->container['config']['default'];
    }

    private function getConfig($name)
    {
        $name = $name ? : $this ->getDefaultProvider();
        return $this->container['config']["provider"][$name];
    }

    private function resolve($name)
    {
        $config = $this->getConfig($name);
        $driver = $config['driver'];
        if (isset($this->providers[$driver])) {
            return  call_user_func($this->providers[$driver]);
        }
    }

    private function registerProviders(){
        $this->addProvider('database', function () {
            return new DatabaseProvider();
        });
        $this->addProvider('file', function (){
            return new FileProvider();
        });
    }

    private function addProvider($driver, Closure $resolver){
        $this->providers[$driver] = $resolver;
    }
}