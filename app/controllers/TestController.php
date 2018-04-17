<?php
use JPush\Client;

class TestController extends BackstageController
{
    public function testAction()
    {
        $start = time();

        $request_list = array();
        for ($i = 0; $i < 100; $i++) {
            $request_list[] = array(
                'url' => 'http://mmgo.com',
                'data' => array(
                    ''
                )
            );
        }

        foreach ($request_list as $v) {
            \Utils::curl_client($v['url']);
        }

        $end = time();
        echo $end - $start;
    }

    public function test2Action()
    {
        $start = time();

        $request_list = [];
        for ($i = 0; $i < 100; $i++) {
            $request_list[] = array(
                'url' => 'http://mmgo.com',
            );
        }
        $httpClient = new \HttpClient();
        $res = $httpClient->multiple($request_list);
        //        var_dump($res);
        //var_dump($httpClient->getErrors());

        $end = time();
        echo $end - $start;
    }


    // App 推送消息
    public function pushAction()
    {
        $jpush =new \Component\MsgPush\Drive\Jpush();
        $send = $jpush->send();

        var_dump($send);exit;



    }

}
