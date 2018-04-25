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
        $validator->add("template", new Uniqueness([
            "message" => "已存在相同消息模版的记录",
            'allowEmpty'=>true // 允许在数据库的字段为空的情况下，不做判断
        ]));
        return $this->validate($validator);
    }
}
