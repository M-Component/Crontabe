<?php
class Validate{
    public static function isAccount($string){
        $reg = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
        return preg_match($reg ,$string);
    }

    public static function isEmail($string){
        $reg = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
        return preg_match($reg ,$string);
    }

    public static function isMobile($string){
        $reg = '/1[3|4|5|7|8][0-9]{9}$/';
        return preg_match($reg ,$string);
    }
}