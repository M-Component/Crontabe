<?php
namespace Api;
class Menu extends Base
{
    public function getList()
    {
        $upload_config = $this->getDI()->getConfig()->upload;
        $menus =\Menu::find(array(
            'sort'=>'sort asc'
        ))->toArray();

        if(!empty($menus)){
            $image_ids =array_keys(\Utils::array_change_key($menus ,'icon'));
            $select_image_ids =array_keys(\Utils::array_change_key($menus ,'select_icon'));
        
            $urls =\Upload::getFilesUrl($image_ids);
            $select_urls =\Upload::getFilesUrl($select_image_ids);
            foreach($menus as &$v){
                $v['icon'] =$urls[$v['icon']];
                $v['select_icon'] =$select_urls[$v['select_icon']];
            }            
        }
        $this->success($menus);
    }
}
