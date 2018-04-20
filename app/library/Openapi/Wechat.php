<?php
namespace Openapi;
class Wechat extends Base
{

    private $wechat;

    public function __construct()
    {
        parent::__construct();
        $this->wechat = new \Wechat\OfficialAccount();
    }

    /**
     * 接入微信公众号，验证消息的确来自于微信服务器
     */
    public function doRequest()
    {
        $signature = $this->request->getQuery('signature');
        $echostr = $this->request->getQuery('echostr');
        $timestamp = $this->request->getQuery('timestamp');
        $nonce = $this->request->getQuery('nonce');

        $this->wechat->setRequestParams($this->request->getQuery());
        try {
            if ($echostr) {
                if (! $this->wechat->checkSignature($signature, $timestamp, $nonce)) {
                    throw new \Exception('接入微信服务器失败');
                }
                return $echostr;
            }
            // 接受用户发送的信息，xml 格式
            $post_xml = file_get_contents('php://input');
            if (!empty($post_xml)) {
                $this->wechat->doPost($post_xml);
            } else {
                echo '';
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
