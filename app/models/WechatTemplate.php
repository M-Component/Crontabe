<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class WechatTemplate extends \Mvc\AdvModel
{
    public function get_columns(){
        return array(
            'title'=>array(
                'type' => 'text',
                'name' => '微信模版名称',
                'update' => false,
            ),

            'primary_industry'=>array(
                'type' => 'text',
                'name' => '模板所属行业的一级行业',
                'update' => false
            ),

            'deputy_industry'=>array(
                'type' => 'text',
                'name' => '模板所属行业的二级行业',
                'update' => false
            ),

            'template'=>array(
                'type' =>array(
                    'vcode'=>'通用提醒',
                    'payment'=>'支付提醒',
                    'signin'=>'登录提醒',
                    'signup'=>'注册提醒',
                ),
                'name'=>'模版类型',
            ),

            'template_id'=>array(
                'type' =>'text',
                'name'=>'模版ID (用于接口调用)',
                'update' => false,
            ),

            'content'=>array(
                'type' =>'code',
                'name'=>'模版内容',
                'update' => false
            ),

            'example'=>array(
                'type' =>'code',
                'name'=>'模版示例',
                'update' => false
            )
        );
    }

    public function finder_extends_columns()
    {
        return array('edit' => array('label' => '编辑'),);
    }

    public function finder_extends_edit($row)
    {
        return Phalcon\Tag::linkTo('wechat_template/edit/' . $row['id'], '编辑');
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(array('template','template_id'), new Uniqueness([
            "message" => "已存在相同通知类型、模板类型的记录"
        ]));
        return $this->validate($validator);
    }

    public function check_update_msg_temp($template_list){

        if (count($template_list) < 1) return false;
        $self_template_list = self::find();
        $template_ids = array_keys(\Utils::array_change_key($template_list,'template_id'));
        foreach ($self_template_list as $key=>$item){
            if (!in_array($item->template_id,$template_ids)){
                self::findFirst(\Mvc\DbFilter::filter(array('template_id'=>$item->template_id)))->delete();
            }else{
                unset($template_list[array_search($item->template_id,$template_ids)]);
            }
        }
        return $template_list;
    }
}
