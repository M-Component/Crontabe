<?php
namespace Api;

class Spage extends Base{
    public function getList()
    {
        $start_page = \StartPage::find(array(
            'sort'=>'sort asc'
        ))->toArray();

        if($start_page){
            $image_ids = array_keys(\Utils::array_change_key($start_page,'image'));
            $urls = \Upload::getFilesUrl($image_ids);
            foreach($start_page as &$item){
                $item['image'] = $urls[$item['image']];
            }
            $this->success($start_page);
        }
        $this->error('获取失败');
    }
}
