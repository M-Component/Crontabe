<?php
use Wechat\OfficialAccount;

class WechatTemplateController extends BackstageController
{
    var $model_name = 'WechatTemplate';
    var $index_title = '微信消息模版';
    var $custom_action = array(
        'use_add' => false,
        'custom_actions' => array(
            array(
                'title' => '更新模版',
                'href' => '/wechat_template/get_template_list',
                'data-ignore' => 'true', // 忽略操作数据
            ),
        )
    );


    public function get_template_listAction()
    {
        $official_account = new OfficialAccount();
        try {
            $template_list = $official_account->getTemplateList();
            $wechat_template_list =\WechatTemplate::find();
            $wechat_template_arr =[];

            foreach ($wechat_template_list as $template){
                $wechat_template_arr[$template->template_id] =$template;
            }

            foreach ($template_list as $item){
                if(!$wechat_template = $wechat_template_arr[$item['template_id']]){
                    $wechat_template = new \WechatTemplate();
                    $wechat_template->template_id =$item['template_id'];
                }else{
                    unset($wechat_template_arr[$item['template_id']]);
                }
                $wechat_template->content =$item['content'];
                if (!$wechat_template->save()){
                    foreach ($wechat_template->getMessages() as $message){
                        throw new \Exception($message->getMessage());
                    }
                }
            }
            foreach ($wechat_template_arr as $item){
                $item->delete();
            }

            $this->success('更新成功');
        } catch (\Exception $exception) {
            $this->error('更新失败 ' . $exception->getMessage());
        }
    }
}
