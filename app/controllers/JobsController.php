<?php
//------------------------------------------------------------
//----------------------------wl------------------------------
//------------------------------------------------------------
class JobsController extends BackstageController{
    var $model_name='Jobs';
    var $index_title ='队列';
    var $show_finder_detail =true;
    var $custom_action =array(
        'use_add' =>false,
        'use_delete'=>true
    );

    public function finder_detailAction($id){
        $job =\Jobs::findFirst($id);
        $config = json_decode($job->payload);
        $job->config=json_encode($config ,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $this->view->data= $job;
    }
}
