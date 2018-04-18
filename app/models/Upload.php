<?php
use \Phalcon\Di;
class Upload extends \Phalcon\Mvc\Model
{

    public static function getFileUrl($id){
        $upload_config = Di::getDefault()->get('config')->upload->toArray();
        $driver = $upload_config['default'];
        $config = $upload_config['drivers'][$driver];

        $upload =\Upload::findFirst("md5='$id'");
        return $config['domain'].($upload->save_path.$upload->save_name);
    }

    public static function getFilePath($id){

        $upload_config = Di::getDefault()->get('config')->upload->toArray();
        $driver = $upload_config['default'];
        $config = $upload_config['drivers'][$driver];
        $upload =\Upload::findFirst("md5='$id'");
        return $config['rootPath'].($upload->save_path.$upload->save_name);
    }

    public static function getFilesUrl($ids){
        $upload_config = Di::getDefault()->get('config')->upload->toArray();
        $driver = $upload_config['default'];
        $config = $upload_config['drivers'][$driver];

        $files = \Upload::find(\Mvc\DbFilter::filter(array(
            'md5|in'=>$ids
        )))->toArray();

        $files =\Utils::array_change_key($files ,'md5');
        foreach($files as $k=>$upload){
            $files[$k] = $config['domain'].($upload['save_path'].$upload['save_name']);
        }
        return $files;
    }

}
