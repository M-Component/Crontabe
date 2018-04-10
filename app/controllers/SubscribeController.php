<?php
class SubscribeController extends CollectionController
{
    var $model_name = 'Subscribe';
    var $index_title = '商品订阅';
    var $custom_action = array(
        'use_add' => false
    );
}
