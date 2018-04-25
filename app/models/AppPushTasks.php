<?php

class AppPushTasks extends \Mvc\AdvModel
{
    public function get_columns(){
        return array(
            'name'=>array(
                'type'=>'text',
                'name'=>'任务名称',
            ),
            'send_time'=>array(
                'type'=>'time',
                'name'=>'任务执行时间',
                
            ),
            'platform'=>array(
                'type' => array(
                    'all'=>'全部平台',
                    'ios'=>'苹果IOS',
                    'android'=>'安卓Android'
                ),
                'name' => '推送平台',
            ),
            'title' =>array(
                'type' =>'text',
                'name' => '推送消息个性化标题',
                'hidd' =>true
            ),
            'content' =>array(
                'type' =>'code',
                'name' => '推送消息内容',
                'hidd' =>true
            ),
            'url' =>array(
                'type' =>'text',
                'name' => '消息点击后目标地址',
                'hidd' =>true
            ),
            'present' =>array(
                'type' =>array(
                    'NO'=>'新窗体',
                    'YES'=>'当前窗体弹出'
                ),
                'name' => '跳转打开方式',
                'hidd' =>true
            ),
            'style' =>array(
                'type' =>array(
                    'style01'=>'否',
                    'style02'=>'是'
                ),
                'name' => '跳转页面是否隐藏顶部',
                'hidd' =>true
            ),
            'task_mark' =>array(
                'type' =>'textarea',
                'name' => '推送任务备注',
                'hidd' =>true
            ),
        );
    }

}
