<?php
use Phalcon\Di;
class MigrateMessage extends SubscribeMessageBase implements \Task\TaskInterface
{
    /**
     * @param $params
     */
    public function exec($job =null ,$params = null)
    {
        $this->migrateMessage();
    }
}
