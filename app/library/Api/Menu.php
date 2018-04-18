<?php
namespace Api;
class Menu extends Base
{
    public function getList()
    {
        $upload_config = $this->getDI()->getConfig()->upload;
        $menus =\Menu::find()->toArray();
        if(!empty($menus)){
            $image_ids =array_keys(\Utils::array_change_key($menus ,'icon'));
        
            $urls =\Upload::getFilesUrl($image_ids);
            foreach($menus as &$v){
                $v['icon'] =$urls[$v['icon']];
            }            
        }
        $this->success($menus);
    }
}
