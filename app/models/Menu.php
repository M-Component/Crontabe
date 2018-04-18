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
        $upload_config = $this->getDI()->getConfig()->upload;
        foreach($list as &$item){
            $upload = \Upload::findFirst(\Mvc\DbFilter::filter(array('md5'=>$item['icon'])));
            $item['icon'] = '<img height="25" src='.$upload_config['drivers']['local']['domain'].$upload->save_path.$upload->save_name.'>';
        }
    }
}