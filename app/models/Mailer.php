<?php

class Mailer implements \Sender\SenderModelInterface
{
    public $drivers = array(
        'smtp'=>'Smtp',
        /*'sendmail'=>'Sendmail',
        'phpmail'=>'Phpmail'*/
    );

    public function getName($id)
    {
        return $this->drivers[$id];
    }

    // 显示所有配置项
    public function getAll($filter = array())
    {
        $result = array();
        foreach ($this->drivers as $driver) {
            $mailer = $this->getInfo($driver);
            if (isset($filter['status']) && $filter['status'] != $mailer['status']) {
                continue;
            }
            $result[] = $mailer;
        }

        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];
        }

        if (!$result) throw new \Exception('邮件平台被关闭');
        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];
        }

        // 按照 order_num 降序排列
        array_multisort($volume, SORT_ASC,SORT_NUMERIC, $result);
        return $result;
    }
    public function getById($id)
    {
        return $this->getInfo($this->getName($id));
    }

    public function getByName($driver_name){
        return $this->getInfo($driver_name);
    }

    public function getObj($driver_name){
        $driver_class = "\\Component\\Mailer\\Driver\\".ucfirst(strtolower($driver_name));
        return new $driver_class;
    }

    public function getInfo($driver_name)
    {
        $driver_class = "\\Component\\Mailer\\Driver\\".ucfirst(strtolower($driver_name));
        $driver = new $driver_class;
        $conf = Setting::getConf($driver_name);
        return array(
            'display_name' =>$conf['nameConfig']?:$driver->nameConfig,
            'order_num'=>$conf['order_num']?$conf['order_num']:0,
            'driver_name'=>$driver_name,
            'status'=>$conf['status'],
            'setting'=>$conf
        );
    }
}