<?php

namespace Api;
use Phalcon\Mvc\User\Component;
class Base extends Component{
    protected $session_id;

    protected $member =[];

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
        $http_origin = $this->request->getHeader('ORIGIN');
        if ($http_origin == "https://test.pianyijiaowo.com" || $http_origin == "http://test.pianyijiaowo.com" || $http_origin == "http://local.pianyijiaowo.com:8080"){  
            $this->response->setHeader('Access-Control-Allow-Origin',$http_origin);
            $this->response->setHeader('Access-Control-Allow-Methods','POST,GET');
            $this->response->setHeader('Access-Control-Allow-Credentials','true'); 
        }
        $this->response->setHeader('_SID' ,$this->session_id);
        if($fun =$this->request->get('callback')){
            $this->response->setContent($fun.'('.json_encode($data).')');
        }else{
            $this ->response ->setJsonContent($data);            
        }

        $this ->response ->send();
        exit;
    }

    protected function error($msg ,$code=400){
        $data=array(
            'errorCode'=>$code,
            'msg' =>$msg
        );
        $http_origin = $this->request->getHeader('ORIGIN');
        if ($http_origin == "https://test.pianyijiaowo.com" || $http_origin == "http://test.pianyijiaowo.com" || $http_origin == "http://local.pianyijiaowo.com:8080"){  
            $this->response->setHeader('Access-Control-Allow-Origin',$http_origin);
            $this->response->setHeader('Access-Control-Allow-Methods','POST,GET');
            $this->response->setHeader('Access-Control-Allow-Credentials','true'); 
        }
        $this->response->setHeader('_SID' ,$this->session_id);
        if($fun =$this->request->get('callback')){
            $this->response->setContent($fun.'('.json_encode($data).')');
        }else{
            $this ->response ->setJsonContent($data);            
        }

        $this ->response ->send();
        exit;
    }
}
