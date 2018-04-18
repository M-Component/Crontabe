<?php

class MenuController extends BackstageController{
    var $model_name = 'Menu';
    var $index_title = '菜单栏';
    var $custom_action =array(
        'use_delete' => true,
        'use_add'=>true
    );
}