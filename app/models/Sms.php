<?php

class Sms implements \Sender\SenderModelInterface
{
    public $drivers = array(
        'zthysms' => 'Zthysms',
    );

    public function getName($id)
    {
        return $this->drivers[$id];
    }

    // 获取所有短信通道类
    public function getAll($filter = array())
    {
        $result = array();
        foreach ($this->drivers  as $driver) {
            $sms = $this->getInfo($driver);
            if (isset($filter['status']) && $filter['status'] != $sms['status']) {
                continue;
            }
            $result[] = $sms;
        }
        if (!$result) throw new \Exception('短信通道被被关闭');
        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];
        }
        // 按照 order_num 降序排列
        array_multisort($volume, SORT_ASC, SORT_NUMERIC, $result);
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

    public function getObj($sms_name)
    {
        $socket_class = "\\Component\\Sms\\Driver\\" . ucfirst(strtolower($sms_name));
        return new $socket_class;
    }

    /**
     * 根据 Model 中定义的属性名称,数据存入数据库中的值
     *
     * @param $sms_name
     * @return array
     */
    public function getInfo($driver_name)
    {
        $driver_class = "\\Component\\Sms\\Driver\\" . ucfirst(strtolower($driver_name));
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
