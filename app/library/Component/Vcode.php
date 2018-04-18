<?php
namespace Component;
use Phalcon\Mvc\User\Component;
class Vcode extends Component{
    private $ttl =86400;

    private $retry =20;

    private $retry_time =30;

    private $vcode_length=6;

    public function setVcode($account ,$type='vcode'){
        $vcodeData = $this->getVcode($account, $type);
        if($vcodeData){
            if ($vcodeData['createtime'] == date('Ymd') && $vcodeData['count'] >= $this->retry) {
                throw new \Exception('每天只能进行'.$this->retry.'次验证');
            }
            $left_time = (time() - $vcodeData['lastmodify']);
            if ($left_time < $this->retry_time) {
                throw new \Exception('请'.($this->retry_time - $left_time).'秒后重试');
            }
            if ($vcodeData['createtime'] != date('Ymd')) {
                $vcodeData['count'] = 0;
            }       
        }
        $vcode = \Utils::randomkeys($this->vcode_length);
        $vcodeData['account'] = $account;
        $vcodeData['vcode'] = $vcode;
        $vcodeData['count']+= 1;
        $vcodeData['createtime'] = date('Ymd');
        $vcodeData['lastmodify'] = time();
        $key = $this->_get_vcode_key($account, $type);
        $this->fileCache->save($key, $vcodeData, $this->ttl);
        return $vcode;
    }
    public function getVcode($account ,$type='vcode'){
        $key =$this->_get_vcode_key($account ,$type);
        return $this->fileCache->get($key);
    }

    public function deleteVcode($account, $type, $vcodeData) {
        $vcode = \Utils::randomkeys($this->vcode_length);
        $vcodeData['vcode'] = $vcode;
        $key = $this->_get_vcode_key($account, $type);
        $this->fileCache->save($key, $vcodeData, $this->ttl);
        return $vcodeData;
    }

    public function verify($account, $type ,$vcode) {
        if (empty($vcode)) return false;
        $vcodeData = $this->getVcode($account, $type);
        if ($vcodeData && $vcodeData['vcode'] == $vcode) {
            $this->deleteVcode($account, $type, $vcodeData);
            return true;
        }
        return false;
    }

    private function _get_vcode_key($account, $type = 'vcode') {
        return md5($account . $type);
    }
}
