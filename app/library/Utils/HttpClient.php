<?php
namespace Utils;
class HttpClient{
    function __construct(){

        $this->netcore = new Http();

    }
    function get($url,$headers=null,$callback=null,$ping_only=false){
        return $this->netcore->action(__FUNCTION__,$url,$headers,$callback,null,$ping_only);
    }

    function delete($url,$headers=null,$callback=null,$ping_only=false){
        return $this->netcore->action(__FUNCTION__,$url,$headers,$callback,null,$ping_only);
    }

    function post($url,$data,$headers=null,$callback=null,$ping_only=false){
        return $this->netcore->action(__FUNCTION__,$url,$headers,$callback,$data,$ping_only);
    }

    function set_timeout($timeout){
        $this->netcore->set_timeout($timeout);
        $this->timeout = $timeout;
        return $this;
    }
    function action($action,$url,$headers=null,$callback=null,$data=null,$ping_only=false){
        return $this->netcore->action($action,$url,$headers,$callback,$data,$ping_only);
    }
    function is_addr($ip){
        return $this->netcore->is_addr($ip);
    }
    function upload($url,$files,$data,$headers=null,$callback=null,$ping_only=false){
        $boundary = '----FormBoundaryEsor2rdD1hne8INi';
        $headers['Content-Type']='multipart/form-data; boundary='.$boundary;
        $formData = array();
        $this->_http_query($formData,$data);

        $output ='';
        foreach($formData as $k=>$v){
            $output .= '--'.$boundary."\r\n";
            $output .= 'Content-Disposition: form-data; name="'
                .str_replace('"','\\\"',$k)."\"\r\n\r\n";
            $output .= $v."\r\n";
        }
        foreach($files as $k=>$v){
            $output .= '--'.$boundary."\r\n";
            $output .= 'Content-Disposition: form-data; name="'
                .str_replace('"','\\\"',$k).'"; filename="'.basename($v)."\"\r\n";
            $mime = function_exists('mime_content_type')?mime_content_type($v):'application/octet-stream';
            $output .= "Content-Type: $mime\r\n\r\n";
            $output .= file_get_contents($v)."\r\n";
        }
        $output .= '--'.$boundary."--\r\n";

        return $this->netcore->action('post',$url,$headers,$callback,$output,$ping_only);
    }

    function _http_query(&$return,$data,$prefix=null,$key='')
    {
        foreach((array)$data as $k => $v){
            if(is_int($k) && $prefix != null){
                $k = $prefix.$k;
            }
            if(!empty($key)){
                $k = $key."[".$k."]";
            }

            if(is_array($v) || is_object($v)){
                $this->_http_query($return,$v,"",$k);
            }else{
                $return[$k]=$v;
            }
        }
    }

}