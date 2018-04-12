<?php
/**
 * Created by PhpStorm.
 * User: medivh
 * Date: 2017/9/6
 * Time: 11:27
 */

namespace Component\Sms\Socket;

use Component\Sms\SmsInterface;
use Component\Sms\Socket\Base;

class Zthysms extends Base implements SmsInterface
{
    public $name = '助通科技';

    public function setting()
    {
        return array(
            'display_name' => array(
                'title' => '通道名称',
                'type' => 'text',
                'default' => $this->name
            ),
            'account' => array(
                'title' => '账户',
                'type' => 'text',
                'default' => ''
            ),
            'password' => array(
                'title' => '密码',
                'type' => 'text',
                'default' => ''
            ),
            'sign' => array(
                'title' => '签名',
                'type' => 'text',
                'default' => ''
            ),
            'api' => array(
                'title' => 'API地址',
                'type' => 'text',
                'default' => ''
            ),
            'array_api' => array(
                'title' => '批量发送地址',
                'type' => 'text',
                'default' => ''
            ),
            'status' => array(
                'title' => '是否启用',
                'type' => 'select',
                'options' => array(
                    'true' => '是',
                    'false' => '否'
                ),
                'default' => 'false'
            ),
            'order_num' => array(
                'title' => '排序',
                'type' => 'number',
                'default' => 0
            ),
        );
    }


    public function send($targets,$content)
    {
        $zthysms = \Setting::getConf('Zthysms');
        $data['tkey'] = date('YmdHis');
        $data['username'] = $zthysms['account'];
        $data['password'] = $this->getPassword($zthysms['password'], $data['tkey']);
        $data['content'] = $content . "【{$zthysms['sign']}】";
        $data['mobile'] =implode(',',$targets);
        if(count($targets)>1){
            $url = $zthysms['array_api'];
        }else{
            $url = $zthysms['api'];
        }
        $res = \Utils::curl_client($url, $data, 'POST', 1);
        $this->logger->info('sms return :' . $res.'===content:'.$data['content'].'===mobile:'.$data['mobile']);

        $msg = explode(',',$res);
        if ($msg[0] == 1){
            return true;
        }
        return false;
    }

    private function getPassword($password, $tkey)
    {
        return md5(md5($password) . $tkey);
    }
}