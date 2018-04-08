<?php

namespace Component\Sms;
class ShortLink
{
    const APP_KEY = '10000005';
    const APP_SECRET = "6d74fd5a65d444d2b1617a236a41c0a5";
    const APP_URL = "http://edmip.cn/api/Shortener/UrlToShort";

    /**
     * @param $LUrl
     * @return bool|mixed|null
     * {"IsErr":false,"Msg":null,"Data":{"LUrl":"原始链接","SUrl":"短链接"}}
     */
    public function Post($LUrl)
    {
        date_default_timezone_set('Asia/Shanghai');
        $app_key = self::APP_KEY;
        $app_secret = self::APP_SECRET;
        $params = array(
            'LUrl' => $LUrl,
            'appkey' => $app_key,
            'time' => date('Y-m-d H:i:s'),
            'v' => '1.0'
        );
        $sign = $this->sign($params, $app_secret);
        $params['sign'] = $sign;

        $api = self::APP_URL;
        $res = \Utils::curl_client($api, $params, 'POST', true);
        $res = json_decode($res,1);
        return $res['Data']['SUrl'];
    }

    public function sign($params, $app_secret)
    {
        ksort($params); // 按照键名排序
        $mac = '';
        foreach ($params as $k => $v) {
            $mac .= "{$k}{$v}";
        }
        $sign = md5($app_secret . $mac . $app_secret);
        return strtoupper($sign); // 将字符串转为大些
    }
}

