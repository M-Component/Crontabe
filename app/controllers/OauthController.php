<?php

class OauthController extends BackstageController{
    public function indexAction(){
        $oauth = new Oauth();
        $this ->view ->oauth_list =$oauth ->getAll();
    }

    public function settingAction($oauth_name){
        if($this ->request ->isPost()){
            $post = $this ->request ->getPost();
            $this->begin();
            if(false ===\Setting::setConf($oauth_name , $post['setting'])){
                $this->end(false);
            }
            $this ->end(true);
        }
        $oauth = new Oauth();
        $this ->view ->setting =$oauth ->getObj($oauth_name) ->setting();
        $this ->view ->oauth =$oauth ->getByName($oauth_name);
    }
}
