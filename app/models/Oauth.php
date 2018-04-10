<?php
/**
 * Created by PhpStorm.
 * User: wl
 * Date: 16/10/7
 * Time: 下午11:08
 */
class Oauth{
    public $oauth_method =array(
        'wechat' =>'Wechat',
        'qq' =>'Qq',
        'sina'=>'Sina',
        'wxapp'=>'Wxapp'
    );
    public function getName($id){
        return $this ->oauth_method[$id];
    }

    public function getAll(){
        $result =array();
        foreach($this ->oauth_method as $oauth_name){
            $result[] = $this ->getInfo($oauth_name);
        }
        return $result;
    }

    public function getById($id){
        return $this ->getInfo($this ->getName($id));
    }

    public function getByName($class_name){
        return $this ->getInfo($class_name);
    }

    public function getObj($oauth_name){
        $oauth_class = "\\Component\\Oauth\\Pam\\".strtoupper(strtolower($oauth_name));
        return new $oauth_class;
    }

    private function getInfo($oauth_name){
        $oauth_class = "\\Component\\Oauth\\Pam\\".strtoupper(strtolower($oauth_name));
        $oauth = new $oauth_class;
        $conf = Setting::getConf($oauth_name);
        return array(
            'name' => $oauth->name,
            'version' => $oauth->version,
            'login_type'=>$oauth->login_type,
            'display_name' => $conf['display_name'],
            'order_num' => $conf['order_num'] ? $conf['order_num'] : 0,
            'oauth_name' => $oauth_name,
            'description' => $conf['description'],
            'authorize_url' => $oauth->authorize_url(),
            'status' => $conf['status'],
            'setting' =>$conf
        );
    }

}
