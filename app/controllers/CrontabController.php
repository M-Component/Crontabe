<?php
//------------------------------------------------------------
//----------------------------wl------------------------------
//------------------------------------------------------------
class CrontabController extends BackstageController
{
    var $model_name = 'Crontab';
    var $index_title = '计划任务';
    var $custom_action = array(
        'use_add'=>true,
        'use_delete'=>false
    );
}
