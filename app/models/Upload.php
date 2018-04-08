<?php
use \Phalcon\Di;
class Upload extends \Phalcon\Mvc\Model
{
    public static function getFileUrl($id){
        $upload =\Upload::findFirst("md5='$id'");
        $upload_config =Di::getDefault()->get('config')->upload->toArray();
        $config = $upload_config['drivers'][$upload->driver];
        return $config['domain'].($upload->save_path.$upload->save_name);
    }

    public static function getFilePath($id){
        $upload =\Upload::findFirst("md5='$id'");
        $upload_config =Di::getDefault()->get('config')->upload->toArray();
        $config = $upload_config['drivers'][$upload->driver];
        return $config['rootPath'].($upload->save_path.$upload->save_name);
    }

}
