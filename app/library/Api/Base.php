<?php

namespace Api;
use Phalcon\Mvc\User\Component;
class Base extends Component{
    protected $session_id;

    protected $member;

    public function __construct(){
        $this->session_id =$this->session->getId();
    }

    protected function checkLogin(){
        if(!$member =$this->auth->isLogin()){
            $this->error('请先登录',500);
        }
        $this->member =$member;
    }

    protected function success($data){
        if(is_string($data)){
            $data =array(
                'success'=>true,
                'msg'=>$data
            );
        }

        $this ->response ->setJsonContent($data);
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
