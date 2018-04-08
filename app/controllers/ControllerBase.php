<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller{

    /*视图输出*/
    protected function output($view = 'list'){
        if($this->request->isAjax()){
            $this->view->pick($view);
        }else{
            $this->view->pick('backstage/loadpage');
        }
    }

    //数据操作事务 TODO
    protected function begin($url=''){
        $this ->redirect_url = $url;
        $this->db->begin();
    }

    protected function end_only($flag){
        if($flag){
            $this->db->commit();
        }else{
            $this->db->rollback();
        }
    }
    protected function end($flag,$msg='',$data = array()){
        if($flag){
            $this->db->commit();
            $msg = $msg != ''?$msg:'操作成功';
            $content = array('status'=>'success','msg'=>$msg,'data'=>$data,'redirect'=>$this->redirect_url);

        }else{
            $this->db->rollback();
            $msg = $msg != ''?$msg:'操作失败';
            $content= array('status'=>'error','msg'=>$msg,'data'=>$data,'redirect'=>$this->redirect_url);
        }
        $this ->response ->setJsonContent($content) ;
        $this ->response ->send();
        exit;
    }
    //数据操作事务 TODO

    protected function success($msg='',$redirect='' ,$data = array()){
        $msg = $msg != ''?$msg:'操作成功';
        $content = array('status'=>'success','msg'=>$msg,'data'=>$data,'redirect'=>$redirect);
        $this ->response ->setJsonContent($content) ;
        $this ->response ->send();
        exit;
    }

    protected function error($msg='',$redirect='' ,$data = array()){
        $msg = $msg != ''?$msg:'操作失败';
        $content = array('status'=>'error','msg'=>$msg,'data'=>$data,'redirect'=>$redirect);
        $this ->response ->setJsonContent($content) ;
        $this ->response ->send();
        exit;
    }

}
