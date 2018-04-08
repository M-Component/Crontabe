<?php

class Sms
{
    public $sms_method = array(
        'zthysms' => 'zthysms',
    );

    public function getName($id)
    {
        return $this->sms_method[$id];
    }

    // 获取所有短信通道类
    public function getAll()
    {
        $result = array();
        foreach ($this->sms_method as $sms_name) {
            $result[] = $this->getInfo($sms_name);
        }

        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];
        }
        // 按照 order_num 降序排列
        array_multisort($volume, SORT_ASC, $result);
        return $result;

    }
    public function getById($id){

        return $this->getInfo($this->getName($id));
    }

    public function getByName($class_name){
        return $this->getInfo($class_name);
    }

    public function getObj($sms_name)
    {
        $socket_class = "\\Component\\Sms\\Socket\\".ucfirst($sms_name);
        return new $socket_class;
    }

    /**
     * 根据 Model 中定义的属性名称,数据存入数据库中的值
     *
     * @param $sms_name
     * @return array
     */
    public function getInfo($sms_name)
    {
        $socket_class = "\\Component\\Sms\\Socket\\" . ucfirst(strtolower($sms_name));
        $socket = new $socket_class;
        $conf = Setting::getConf($sms_name); // 首先查一遍数据
        return array(
            'display_name' => $conf['name'] ?: $socket->name,
            'order_num' => $conf['order_num'] ? $conf['order_num'] : 0,
            'sms_name' => $sms_name,
            'status' => $conf['status'],
            'setting' => $conf,
        );
    }
}
