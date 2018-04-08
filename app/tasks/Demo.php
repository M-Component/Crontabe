<?php

use Task\Output;
class Demo implements \Task\TaskInterface
{
    /**
     * @param $params
     */
    public function exec($job =null ,$params = null)
    {
        for($i=0;$i<10;$i++){
            echo $i.'=';
            sleep(1);
        }
        return true;
    }
}
