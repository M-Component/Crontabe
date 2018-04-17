<?php
namespace Api;
class Goods extends Base
{
    public function getList(){
        $data['limit'] = $this->request->get('limit') ? :10;;
        $data['page'] = $this->request->get('page') ? :1;
        $data['keywords'] = $this->request->get('keywords');
        $goods = \Utils::curl_client("http://120.132.13.132/api/search/goods",$data);
        $res = json_decode($goods,1);
        if (!$res['errorCode']){
            $this->success($goods);
        }
        $this->error($goods['msg']);
    }
    
    public function getGoodsRow($id){
        if (!$id){
            $this->getList();
        }
        $goods = \Utils::curl_client("http://120.132.13.132/api/search/goods/".$id);
        $res = json_decode($goods,1);
        if (!$res['errorCode']){
            $this->success($goods);
        }
        $this->error($goods['msg']);
    }
}
