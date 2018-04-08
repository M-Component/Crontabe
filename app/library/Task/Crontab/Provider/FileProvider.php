<?php
namespace Task\Crontab\Provider;
use Phalcon\Di;
use Exception;

class FileProvider extends Crontab implements ProviderInterface
{
    protected $file;
    public function __construct()
    {

    }

    public function get()
    {
        $this ->file =$this ->config['file'];
        if ( !file_exists($this ->file)) {
            throw new Exception('Can not find the crontab file');
        }
        $data = include $this->file;
        return $this->parseCrontab($data);
    }


    /**
     * 格式化配置文件中的配置
     * @return array
     */
    protected function parseCrontab($data)
    {
        $crontab = [];
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if($val['disable']){
                    continue;
                }
                $crontab[] = array(
                    'id'       => $key,
                    "name"     => $val["name"],
                    "rule"     => $val["rule"],
                    "unique"   => $val["unique"],
                    "job"      => $val["job"],
                    "data"     => $val["data"],
                );
            }
        }
        return $crontab;
    }

    public function start($crontab){

    }

    public function finish($crontab){

    }
}