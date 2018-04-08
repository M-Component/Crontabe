<?php
use Phalcon\Mvc\User\Component;
class FunctionExtension extends Component{
    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . "(". $arguments . ")";
        }elseif(method_exists($this , $name)){
            return "call_user_func_array(array(new \\FunctionExtension() ,'$name') ,array($arguments))";
        }else{
            switch ($name){
                case 'link_url':
                    return  '$this->url->get'."(". $arguments . ")";
            }
        }
    }


    public function compileFilter($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . "(". $arguments . ")";
        }
    }

    public function input_object($params){
        $view_data = $params;
        $data['model'] = $params['model'];
        $data['multiple'] = $params['multiple'] ? : false;
        $data['title'] = $params['title'] ? : '';
        $data['base_filter'] = $params['base_filter'] ? : '';
        if(is_array($data['base_filter'])){
            $data['base_filter'] = json_encode($data['base_filter']);
        }
        $view_data['params'] = http_build_query($data);

        if(!$view_data['textcol']){
            $model = new $data['model'] ;
            $columns = $model->dump();
            foreach($columns as $key=>$column){
                if(!is_array($column)){
                    unset($columns[$key]);
                    continue;
                }
                if($column['is_title']){
                    $view_data['textcol'] = $key;
                    break;
                }
            }
        }

        if($view_data['value']){
            $model = new $data['model'] ;
            $view_data['data'] = $model->findFirst($view_data['value'])->toArray();
            $view_data['textvalue'] =$view_data['textcol'] ? $view_data['data'][$view_data['textcol']] : $view_data['value'];
        }
        $view_data['dom_id'] = uniqid();
        return $this->view->getPartial('backstage/input_object' , $view_data);
    }

    public function  input_image($params){

        $image_id = $params['image_id'];
        $view_data = $params;
        $view_data['img_url']= \Upload::getFileUrl($image_id);
        $view_data['width'] =$params['width'] ? : 120;
        $view_data['height'] =$params['height'] ? : 120;
        return $this->view->getPartial('backstage/input_image' ,$view_data);
    }

    public function input_category($params){
        $root = \GoodsCategory::find('is_top=1')->toArray();
        $root = \Utils::array_change_key($root ,'cid');
        $cat_id = $params['value'];
        if($cat_id!==null){
            $cat= \GoodsCategory::findFirst('cid='.$cat_id);
            $tree = array();
            $select_id = null;
            if($parent_path = $cat->parent_path){
                $parent_path = explode(',', $parent_path);
                $parent_path_tmp = array_reverse($parent_path);
                foreach($parent_path_tmp as $pid){
                    if($pid ==$cat_id){
                        continue;
                    }

                    $children = \GoodsCategory::find('parent_cid='.$pid)->toArray();
                    if(!empty($children)){
                        $children = \Utils::array_change_key($children ,'cid');
                        unset($children[$cat_id]);
                        if(!empty($children)){
                            if($select_id){
                                $children[$select_id]['selected'] = true;
                            }
                            $tree[$pid] = $children;
                        }
                        $select_id = $pid;
                    }
                }
                $tree = array_reverse($tree, 1);
                $view_data['tree'] = $tree;
            }
            if($select_id!==null){
                $root[$select_id]['selected'] =true;
            }
            $view_data['cat'] = $cat;

        }

        if(null !== $parent_id = $params['parent_id']){
            $parent = \GoodsCategory::findFirst('cid='.$parent_id);
            $select_id = $parent_id;
            $parent_path = $parent->parent_path;
            if($parent_path){
                $parent_path = explode(',', $parent_path);
                $parent_path_tmp = array_reverse($parent_path);
                foreach($parent_path_tmp as $pid){
                    $children = \GoodsCategory::find('parent_cid='.$pid)->toArray();
                    if(!empty($children)){
                        $children = \Utils::array_change_key($children ,'cid');
                        if($select_id && isset($children[$select_id])){
                            $children[$select_id]['selected'] = true;
                        }
                        $tree[$pid] = $children;
                        $select_id = $pid;
                    }
                }
                $tree = array_reverse($tree, 1);
                $view_data['tree'] = $tree;
            }

            if($select_id!==null){
                $root[$select_id]['selected'] =true;
            }
            $view_data['parent_id'] = $parent_id;
        }

        $view_data['root'] =$root;
        $view_data['dom_md5'] = uniqid();
        $view_data['name'] =$params['name'];
        return $this->view->getPartial('backstage/input_category' ,$view_data);
    }
}
