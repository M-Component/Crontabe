<?php
namespace Openapi;
use Phalcon\Mvc\User\Component;
class  Base extends Component{
    protected function success($data){
        if(is_string($data)){
            $data =array(
                'success'=>true,
                'msg'=>$data
            );
        }
        $this ->response ->setJsonContent($data) ;
        $this ->response ->send();
        exit;
    }

    protected function error($msg ,$code=400){
        $data=array(
            'errorCode'=>$code,
            'msg' =>$msg
        );
        $this ->response ->setJsonContent($data) ;
        $this ->response ->send();
        exit;
    }
}
