<?php
use Phalcon\Di;
use Phalcon\Cli\Task;
class TestTask extends Task implements \Task\TaskInterface
{
    public function exec($job =null ,$params = null)
    {
     
    }

    public function mainAction()
    {
        echo 'This is the default task and the default action' . PHP_EOL;
        $http = new \HttpClient();
        $url ='http://www.mmgo.com/passport-login.html';
        $params =array();
        for($i=0;$i<100 ;$i++){
            $params[] =array(
                'username' =>'test'
            );
        }
        $re =$http->simpleMultiple($url ,'GET' ,$params );
        foreach($re as $v){
            var_dump(strlen($v));
        }
    }
}
