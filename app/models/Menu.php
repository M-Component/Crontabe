<?php

class Menu extends Phalcon\Mvc\Model
{
    public function get_columns(){
        return array(
            'name'=>array(
                'type'=>'text',
                'name'=>'菜单名称',
                'is_tile'=>true,
            ),
            'url'=>array(
                'type' => 'text',
                'name' => '菜单栏地址',
            ),
            'icon' =>array(
                'type' =>'image',
                'name' => '图标',
                'update' => false,
                'hidd' =>true
            ),
            'sort' =>array(
                'type' =>'number',
                'name' => '排序'
            )
        );
    }


    public function finder_extends_columns(){
        return array('edit'=>array('label'=>'编辑'));
    }

    public function finder_extends_edit($row){
        return \Phalcon\Tag::linkTo('menu/edit/'.$row['id'],'编辑');
    }

    public function modify_finder(&$list){
        $upload = \Upload::getFilesUrl(array_keys(\Utils::array_change_key($list,'icon')));
        foreach($list as &$item){
            $item['icon'] = '<img height="25" src='.$upload[$item['icon']].'>';
        }
    }
}
