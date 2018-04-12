<?php
namespace Member;
use Phalcon\Di;

class Subscribe{
    public function getLastPrice($current_price ,$rule ,$value){
        $last_price = false;
        switch($rule){
            //价格大于等于
            case 'gt':
            case 'gte':
                $last_price =$current_price+$value ;
                break;
            //价格小于
            case 'lt':
            case 'lte':
                $last_price =$current_price-$value ;
                break;

            case 'up_percent':
                $last_price = round($current_price*(1+$value/100) ,2)  ;
                break;
            case 'down_percent':
                $last_price = round($current_price*(1-$value/100) ,2)  ;
                break;
            default:
                $last_price =false;
                break;
        }
        return $last_price;
    }


    public function checkRule($last_price ,$rule ,$price){
        $checked = false;
        switch($rule){
            //价格大于
        case 'gt':
        case 'up_percent':
            $checked = $price >$last_price;
            break;
        case 'gte':
            $checked = $price >=$last_price;
            break;

            //价格小于
        case 'lt':
        case 'down_percent':
            $checked = $price <$last_price;
            break;
        case 'lte':
            $checked = $price <=$last_price;
            break;
        default:
            $checked =false;
            break;
        }
        return $checked;
    }
}
