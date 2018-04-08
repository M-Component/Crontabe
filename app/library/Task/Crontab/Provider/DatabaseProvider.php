<?php
namespace Task\Crontab\Provider;
use Phalcon\Di;
class DatabaseProvider extends Crontab implements ProviderInterface
{
    protected $db;

    protected $table;

    public function __construct()
    {
        $di = Di::getDefault();
        $this->db = $di->get('db');
    }



    /**
     * 返回格式化好的任务配置
     * @return array
     */
    public function get()
    {
        $this->table = $this->config['table'];
        $data = $this->db->fetchAll("select * from `{$this->table}` where `status`=0");
        return $this ->parseCrontab($data);
    }

    /**
     * 格式化配置
     * @return array
     */
    protected function parseCrontab($data)
    {
        $crontab = [];
        if (is_array($data)) {
            foreach ($data as $key => $val) {
//                $rule = unserialize($val["rule"]);
                $rule = $val["rule"];
                if (!is_array($rule)) {
                    $rule = $val["rule"];
                }
                $crontab[$val['id']] = array(
                    'id'       =>$val['id'],
                    "name"     => $val["name"],
                    "rule"     => $rule,
                    "unique"   => $val["unique"],
                    "job"      => $val["job"],
                    "data"     => json_decode($val["data"], true),
                    "last_time"      => $val["last_time"],
                );
            }
        }
        return $crontab;
    }


    public function start($crontab){
        $time = time();
        $this->table = $this->config['table'];
        return $this->db->execute("update `{$this->table}` set last_time=$time where `id`={$crontab['id']}");
    }

    public function finish($crontab){

    }
}
