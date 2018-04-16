<?php
namespace Api;
class Oauth extends Base
{

    public function getList(){
        $oauth = new \Oauth();
        $filter =array(
            'status' =>'true',
            'platform'=>'app'
        );
        $data = $oauth->getAll($filter);
        $this->success($data);
    }

    public function getOauth($name){
        $oauth = new \Oauth();
        $data =$oauth->getInfo($name);
        $this->success($data);
    }
}
