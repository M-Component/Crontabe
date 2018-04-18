<?php
namespace Component\MsgPush\Driver;
use Component\MsgPush\MsgPushInterface;

use JPush\Client as JpushClient;
class Jpush extends Base implements MsgPushInterface {

    public $name = '极光推送';

    private $conf;

    public function __construct(){
        $this->conf =$this->getConf(null ,'JPush');
    }
    public function setting()
    {
        return array(
            'display_name' => array(
                'title' => '通道名称',
                'type' => 'text',
                'default' => $this->name
            ),
            'app_key' => array(
                'title' => 'APP_KEY',
                'type' => 'text',
                'default' => ''
            ),
            'master_secret' => array(
                'title' => 'Master Secret',
                'type' => 'password',
                'default' => ''
            ),
            'status' => array(
                'title' => '是否启用',
                'type' => 'select',
                'options' => array(
                    'true' => '是',
                    'false' => '否'
                ),
                'default' => 'false'
            ),
            'order_num' => array(
                'title' => '排序',
                'type' => 'number',
                'default' => 0
            ),
        );
    }

    public function send(array $registrationId,$message,$title,$alert){
        $jpush_client = new JpushClient($this->conf['app_key'],$this->conf['master_secret']);
        $push_payload = $jpush_client->push()
            ->setPlatform(array('ios','android'))
            ->addRegistrationId($registrationId)
            ->setNotificationAlert($alert)  // 统一通知的内容
            ->iosNotification('',array(
                'sound' => 'sound.caf',
            ))
            ->androidNotification('',array(
                'title' => $title,
            ))
            ->message($message,array(
                'title' =>$title,
            ));
        try {
            $response = $push_payload->send();
            return true;
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            return false;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            return false;
        }
    }
    public function sendOne($registrationId,$alert,$title,$message){
        $this->send([$registrationId],$alert,$title,$message);
    }
    
    public function batchSend(array $registrationid_alerts){
        foreach ($registrationid_alerts as $item){
            $this->sendOne($item['registrationId'],$item['alert'],$item['title'],$item['message']);
        }
    }
}
