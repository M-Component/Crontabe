<?php
namespace Api;
class Goods extends Base
{
    public function getList()
    {
        $data['limit'] = $this->request->get('limit') ?: 10;;
        $data['page'] = $this->request->get('page') ?: 1;
        $data['keywords'] = $this->request->get('keywords');
        $goods = \Utils::curl_client("http://120.132.13.132/api/search/goods", $data);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent($goods);
        $this->response->send();
        exit;

    }

    public function getGoodsRow($id)
    {
        if (!$id) {
            $this->getList();
        }
        $goods = \Utils::curl_client("http://120.132.13.132/api/search/goods/" . $id);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent($goods);
        $this->response->send();
        exit;
    }
}
