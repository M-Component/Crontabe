<?php

namespace Task\Crontab;
class Table
{
    const TABLE_LINE = 10240;

    static public $table;

    static private $column = [
        "unique_id" => [\swoole_table::TYPE_INT, 8],
        "createTime" => [\swoole_table::TYPE_INT, 8],
        "sec" => [\swoole_table::TYPE_INT, 8],
        "id" => [\swoole_table::TYPE_STRING, 64],
        "unique" => [\swoole_table::TYPE_INT, 8],
        "cron" => [\swoole_table::TYPE_STRING, 1024],//cron
        "runStatus" => [\swoole_table::TYPE_INT, 1],
        "job" => [\swoole_table::TYPE_STRING, 64],//cron
    ];

    public static function init()
    {
        if(!self::$table){
            self::$table = new \swoole_table(self::TABLE_LINE);
            foreach (self::$column as $key => $v) {
                self::$table->column($key, $v[0], $v[1]);
            }
            self::$table->create();
        }
    }

    public static function getTasks(){
        return self::$table;
    }

    public static function setTask($id ,$data){
        self::$table->set($id ,$data);
    }

    public static function getTask($id ){
        return self::$table->get($id );
    }

    public static function delTask($id){
        self::$table->del($id);
    }

}
