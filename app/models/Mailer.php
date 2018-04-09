<?php

class Mailer
{
    public $mailer_driver = array('Smtp', 'Sendmail', 'Phpmail');

    // 显示所有配置项
    public function getAll()
    {
        $result = array();
        foreach ($this->mailer_driver as $driver) {
            $result[] = $this->getInfo($driver);
        }

        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];
        }
        // 按照 order_num 降序排列
        array_multisort($volume, SORT_ASC, $result);
        return $result;
    }

    public function getObj($mailer_name){
        $driver_class = "\\Component\\Mailer\\Driver\\".ucfirst(strtolower($mailer_name));
        return new $driver_class;
    }

    public function getByName($mailer_name){
        return $this->getInfo($mailer_name);
    }

    public function getInfo($mailer_name)
    {
        $driver_class = "\\Component\\Mailer\\Driver\\".ucfirst(strtolower($mailer_name));
        $driver = new $driver_class;
        $conf = Setting::getConf($mailer_name);
        return array(
            'display_name' =>$conf['nameConfig']?:$driver->nameConfig,
            'order_num'=>$conf['order_num']?$conf['order_num']:0,
            'mailer_name'=>$mailer_name,
            'status'=>$conf['status'],
            'setting'=>$conf
        );
    }
}