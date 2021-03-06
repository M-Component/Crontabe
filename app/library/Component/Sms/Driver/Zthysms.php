<?php
namespace Component\Sms\Driver;

use Component\Sms\SmsInterface;

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
            'url' => array(
                'title' => 'API地址',
                'type' => 'text',
                'default' => ''
            ),
            'batch_url' => array(
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


    public function send(array $targets, $content)
    {
        $zthysms = $this->getConf(null ,'Zthysms');
        $data['tkey'] = date('YmdHis');
        $data['username'] = $zthysms['account'];
        $data['password'] = $this->getPassword($zthysms['password'], $data['tkey']);
        $data['content'] = $content . "【{$zthysms['sign']}】";
        $data['mobile'] = implode(',', $targets);
        if (count($targets) > 1) {
            $url = $zthysms['batch_url'];
        } else {
            $url = $zthysms['url'];
        }
        $res = \Utils::curl_client($url, $data, 'POST', 1);
        $this->logger->info('sms return :' . $res . '===content:' . $data['content'] . '===mobile:' . $data['mobile']);

        $msg = explode(',', $res);
        if ($msg[0] == 1) {
            return true;
        }
        return false;
    }
    public function sendOne($target,$content){
        return $this->send([$$target],$content);
    }
    public function batchSend($target_contents)
    {
        $config = $this->getConf(null ,'Zthysms');
        $request_params = array();

        $time = date('YmdHis');
        $url = $config['url'];
        foreach ($target_contents as $target) {
            $request_params[] = array(
            'tkey' => $time,
            'username' => $config['account'],
            'password' => $this->getPassword($config['password'], $time),
            'content' => $target['content']."【{$config['sign']}】",
            'mobile' => $target['target']
            );
        }
        $httpClient = new \HttpClient();
        $result = $httpClient->simpleMultiple($url,'POST',$request_params);
        if ($result) {
            return true;
        }
        return false;
    }

    private function getPassword($password, $tkey)
    {
        return md5(md5($password) . $tkey);
    }
}
