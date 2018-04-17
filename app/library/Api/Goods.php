<?php
namespace Api;
class Goods extends Base
{
    public function __construct(){
        $this->config = $this->getDI()->getConfig()->api['translation'];
    }
    public function getList()
    {
        $data['limit'] = $this->request->get('limit') ?: 10;;
        $data['page'] = $this->request->get('page') ?: 1;
        $data['keywords'] = $this->request->get('keywords');
        $data['store_name'] = $this->request->get('store_name');
        $goods = \Utils::curl_client($this->config."/api/search/goods", $data);
        $goods = json_decode($goods,1);
        if(!$goods['errorCode']){
            $this->success($goods);   
        }
        $this->error($goods['msg']);
    }

    public function getGoodsRow($id)
    {
        if (!$id) {
            $this->getList();
        }
        $goods = \Utils::curl_client($this->config."/api/search/goods/" . $id);
        $goods = json_decode($goods,1);
        if(!$goods['errorCode']){
            $this->success($goods);   
        }
        $this->error($goods['msg']);
    }
    
    public function getComments(){
        $id= $this->request->get('id');
        $comments = \Utils::curl_client($this->config."/api/search/goods/comments/".$id);
        $comments = json_decode($comments,1);
        if (!$comments['errorCode']){
            $this->success($comments);
        }
        $this->error($comments['msg']);
    }
}
