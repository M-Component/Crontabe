<?php
namespace Api;
class Goods extends Base
{
    public function __construct(){
        parent::__construct();
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
            if($memebr =$this->auth->isLogin()){
                $subscribe =\Subscribe::findFirst(array(
                    'goods_id'=>(string)$id,
                    'member_id'=>(int)$member['member_id']
                ));
            }
            $goods['subscribe']= $subscribe ? true :false;
            $this->success($goods);   
        }
        $this->error($goods['msg']);
    }
    
    public function getReviews($goods_id){
        $reviews = \Utils::curl_client($this->config."/api/search/goods/".$goods_id."/reviews");
        $reviews = json_decode($reviews,1);
        if (!$reviews['errorCode']){
            $this->success($reviews);
        }
        $this->error($reviews['msg']);
    }
}
