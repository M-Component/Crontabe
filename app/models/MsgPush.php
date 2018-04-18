<?php

class MsgPush implements \Sender\SenderModelInterface
{
    public $drivers = array(
        'jpush' => 'jpush'
    );

    public function getName($id)
    {
        return $this->drivers[$id];
    }

    // 获取所有短信通道类
    public function getAll($filter = array())
    {
        $result = array();
        foreach ($this->drivers as $driver) {
            $result[] = $this->getInfo($driver);
        }

        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];
        }
        // 按照 order_num 降序排列
        array_multisort($volume, SORT_ASC, $result);
        return $result;

    }

    public function getById($id)
    {

        return $this->getInfo($this->getName($id));
    }

    public function getByName($class_name)
    {
        return $this->getInfo($class_name);
    }

    public function getObj($driver)
    {
        $socket_class = "\\Component\\MsgPush\\Driver\\" . ucfirst(strtolower($driver));
        return new $socket_class;
    }

    public function getInfo($driver_name)
    {
        $driver_class = "\\Component\\MsgPush\\Driver\\" . ucfirst(strtolower($driver_name));
        $driver = new $driver_class;
        $conf = Setting::getConf($driver_name); // 首先查一遍数据
        return array(
            'display_name' => $conf['name'] ?: $driver->name,
            'order_num' => $conf['order_num'] ? $conf['order_num'] : 0,
            'driver_name' => $driver_name,
            'status' => $conf['status'],
            'setting' => $conf,
        );
    }
}