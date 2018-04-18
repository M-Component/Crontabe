<?php

class SysSetting
{
    public $drives = array();
    public $namespace; //

    public function getName($id)
    {
        return $this->drives[$id];
    }

    // 获取所有驱动
    public function getAll()
    {

        $result = array();
        foreach ($this->drives as $drive) {
            $result[] = $this->getInfo($drive);
        }

        foreach ($result as $key => $row) {
            $volume[$key] = $row['order_num'];  // 根据定义的排序功能
        }
        array_multisort($volume,SORT_ASC,$result); // 按照 order_num 降序排序
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

    public function getObj($drive_name)
    {
        $socket_class = "\\Component\\" . $this->namespace . "\\Driver\\" . ucfirst(strtolower($drive_name));
        return new $socket_class;
    }

    /**
     * 根据 Model 中定义的属性名称,数据存入数据库中的值
     *
     * @param $sms_name
     * @return array
     */
    public function getInfo($drive_name)
    {
        $drive_class = "\\Component\\" . $this->namespace . "\\Driver\\" . ucfirst(strtolower($drive_name));
        $drive = new $drive_class;
        $conf = Setting::getConf($drive_name); // 首先查一遍数据
        return array(
            'display_name' => $conf['name'] ?: $drive->name,
            'order_num' => $conf['order_num'] ? $conf['order_num'] : 0,
            'drive_name' => $drive_name,
            'status' => $conf['status'],
            'setting' => $conf,
        );
    }
}
