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
        $setting = new Setting();
        $conf = $setting->getConf('jpush');
        $client = new Client($conf['app_key'],$conf['master_secret']);

        $push_api = "https://bjapi.push.jiguang.cn/v3/push";

        $push_payload = $client->push()
            ->setPlatform('all')
            ->addAllAudience()  // 如果要发广播（全部设备），则直接填写 “all”。推送设备对象，表示一条推送可以被推送到哪些设备列表。确认推送设备对象，JPush 提供了多种方式，比如：别名、标签、注册ID、分群、广播等。
            ->setNotificationAlert('Hi, JPush');
        try {
            $response = $push_payload->send();
            print_r($response);
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            print $e;
        }
    }

}
