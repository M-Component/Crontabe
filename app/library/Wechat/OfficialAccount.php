<?php
namespace Wechat;
use Component\Wechat\Wechat;

class OfficialAccount{

    private $wechat;

    private $request_params;

    private $response_data;

    public function __construct(){
        $config = \Setting::getConf('Wechat');
        $this->wechat = new Wechat($config);
    }

    public function setRequestParams($params =array()){
        $this->request_params =$params;
    }
    
    // 验证消息的确来自于微信服务器
    public function checkSignature($signature ,$timestamp ,$nonce)
	{
        return $this->wechat->check_sign($signature ,$timestamp ,$nonce);
	}

    public function doPost($xml_data){
        // 第三方收到公众号平台发送的消息
        if($this->request_params['encrypt_type'] == 'aes'){
            //需要解密
            $xml_data = $this->wechat->decrypt_msg($xml_data,$this->request_params['msg_signature,'] ,$this->request_params['timestamp'] , $this->request_params['nonce']);           
            if (!$xml_data) {
                return false;
            }
        }
        $data = $this->wechat->xml_parse($xml_data);

        if(!$data['MsgType']){
            return false;
        }
        $reply =$this->replyMessage($data['MsgType'] ,$data);
        $reply_data = array(
            'ToUserName' => $data['FromUserName'],
            'FromUserName' => $data['ToUserName'],
            'MsgType' => 'text',
            'Content'=>$reply,
            'CreateTime'=>time()
        );
        $reply_xml = $this->wechat->xml_build($reply_data);
        $nonce =\Utils::randomkeys(5);
        $reply_xml = $this->wechat->encrypt_msg($reply_xml, $data['CreateTime'],$nonce);
        echo $reply_xml;

    }

    private function replyMessage($msg_type ,$data){
        switch ($msg_type) {
        case 'event':
            return '收到事件消息';
            break;
        case 'text':
            return '收到文字消息';
            break;
        case 'image':
            return '收到图片消息';
            break;
        case 'voice':
            return '收到语音消息';
            break;
        case 'video':
            return '收到视频消息';
            break;
        case 'location':
            return '收到坐标消息';
            break;
        case 'link':
            return '收到链接消息';
            break;
        case 'file':
            return '收到文件消息';
            break;
        default:
            return '收到默认信息';
        }
    }
}
