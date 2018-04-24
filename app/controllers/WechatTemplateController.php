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
            $wechat_template = new \WechatTemplate();
            $new_template_list = $wechat_template->check_update_msg_temp($template_list);
            if ($new_template_list){
                foreach ($new_template_list as $key => $item) {
                    $params = array();
                    $params = array(
                        'title' => $item['title'],
                        'template_id' => $item['template_id'],
                        'primary_industry' => $item['primary_industry'],
                        'deputy_industry' => $item['deputy_industry'],
                        'content' => $item['content'],
                        'example' => $item['example'],
                    );
                    $wechat_template = new \WechatTemplate();
                    $wechat_template->save($params);
                }
            }
            $this->success('更新成功');
        } catch (\Exception $exception) {
            $this->error('更新失败 ' . $exception->getMessage());
        }
    }
}
