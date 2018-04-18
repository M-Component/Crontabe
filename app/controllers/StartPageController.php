<?php

class StartPageController extends BackstageController{
    var $model_name = 'StartPage';
    var $index_title = 'app 启动页';

    var $custom_action = array(
        'use_add' => true,
        'use_delete' => true
    );
}
