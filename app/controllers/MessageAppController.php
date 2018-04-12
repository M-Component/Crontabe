<?php

class MessageAppController extends BackstageController
{
    var $model_name = 'MessageApp';
    var $index_title = 'APP消息';
    var $custom_action = array(
        'use_add' => false
    );

    function testAction(){
        $start =time();

        $request_list ;
        for($i=0 ;$i<60;$i++){
            $request_list[] =array(
                'url'=>'http://mmgo.com',
            );            
        }

        foreach($request_list as $v){
            \Utils::curl_client($v['url']);
        }

        $end =time();
        echo $end-$start ;
        exit;
    }

    function test2Action(){
        $start =time();

        $request_list =[];
        for($i=0 ;$i<60;$i++){
            $request_list[] =array(
                'url'=>'http://mmgo.com',
            );            
        }
        $httpClient =new \HttpClient();
        $res =$httpClient ->multiple($request_list);
        //        var_dump($res);
        //var_dump($httpClient->getErrors());

        $end =time();
        echo $end-$start;
    }
}
