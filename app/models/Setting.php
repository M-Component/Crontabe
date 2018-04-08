<?php
/**
 * Created by PhpStorm.
 * User: wl
 * Date: 16/9/27
 * Time: 下午10:24
 */
class Setting extends \Mvc\AdvModel{

    public static function getConf($key ,$pre='',$default=false){
        if(!$key){
            return false;
        }
        $key = $pre ?$pre.'-'.$key : $key;
        $setting = self::findFirst(array('key="'.$key.'"'));
        if($setting){
            $value = $setting->value;
            $value = unserialize($value);
            return $value;
        }
        return $default;
    }

    public static function setConf($key ,$value ,$pre=''){
        $key = $pre ?$pre.'-'.$key : $key;
        $setting = self::findFirst(array('key="'.$key.'"'));
        $value = serialize($value);
        if($setting){
            $setting->value = $value;
            $setting->update();
        }else{
            $setting = new Setting();
            $setting ->create(array('key'=>$key ,'value'=>$value));
        }
    }
}