<?php
namespace Mvc;
class AdvModel extends \Phalcon\Mvc\Model
{

    public function batchCreate($rows ,$ignore = false){
        $table = $this ->getSource();
        if ($ignore) {
            $insertString = "INSERT IGNORE INTO `%s` (%s) VALUES %s";
        } else {
            $insertString = "INSERT INTO `%s` (%s) VALUES %s";
        }
        $columns = array_keys($rows[0]);
        $fieldCount = count($columns);
        $valueCount = count($rows);
        $columns = sprintf('`%s`', implode('`,`', $columns));
        // 创建占位符
        $placeholders = [];
        for ($i = 0; $i < $valueCount; $i++) {
            $placeholders[] = '(' . rtrim(str_repeat('?,', $fieldCount), ',') . ')';
        }
        $bind = implode(',', $placeholders);
        // 填充数据
        $values = array();
        foreach ($rows as $row){
            foreach($row as $value){
                $values[] = $value;
            }
        }
        $query = sprintf($insertString,
            $table,
            $columns,
            $bind
        );
        return $this->getDI()->get('db')->execute($query, $values);
    }
}