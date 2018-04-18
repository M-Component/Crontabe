<?php

class StartPage extends Phalcon\Mvc\Model{
    public function get_columns(){
        return array(
            'name' => array(
                'type' => 'text',
                'name' => '启动页名称',
                'is_title' =>true
            ),
            'url' => array(
                'type' => 'text',
                'name' => '启动页地址',
            ),
            'image' => array(
                'type' => 'image',
                'name' => '图片',
                'hidd' => true,
                'update' => false,
            ),
            'sort' => array(
                'type' => 'number',
                'name' => '排序'
            )
        );
    }


    public function finder_extends_columns(){
        return array('edit' => array('label' => '编辑'));
    }

    public function finder_extends_edit($row){
        return Phalcon\Tag::linkTo('start_page/edit/'.$row['id'],'编辑');
    }

    public function modify_finder(&$list){
        $upload = \Upload::getFilesUrl(array_keys(\Utils::array_change_key($list,'image')));
        foreach($list as &$item){
            $item['image'] = $upload[$item['image']];
        }
    }
}
