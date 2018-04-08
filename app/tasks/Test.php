<?php
use Robot\Remote;
use Phalcon\Di;
class Test implements \Task\TaskInterface
{
    public function exec($job =null ,$params = null)
    {
        for($i=0;$i<10;$i++){
            sleep(3);
            echo '='.$i.'-';
        }
        return true;
        echo 1;
        throw new Exception('此时没有可用的robot，请稍后重试');
        $db = Di::getDefault()->get('db');

        $db->begin();
        $robot_mdl = new \Robot();
        $params['taskId']=123;
        $robot = $robot_mdl ->getRandomFree($params['taskId']);
        if(!$robot){
            $db->rollback();
            throw new Exception('此时没有可用的robot，请稍后重试');
        }
        $db->commit();
        var_dump($robot['id']);
        try{
            $params['robot'] = $robot;
            $db ->begin();
            sleep(5);
            $db ->commit();
        }catch (Exception $e){
            $db ->rollback();
            $robot['use_status'] = 0;
            $robot_mdl->save($robot);
            throw new Exception($e->getMessage());
        }
        return true;
        throw new \Phalcon\Exception('exec error');
        error_log('start:'.date('Y-m-d H:i:s') ,3 ,__FILE__.'log');
        for($i=0;$i<20;$i++){
            file_get_contents('https://vmcshop.com');
        }
        error_log('end:'.date('Y-m-d H:i:s') ,3 ,__FILE__.'log');
    }
}
