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

    public function sendByTags(){
        
    }

    /**
     * @params plantform ios\android\all
     * @params title string
     * @params content string
     * @params send_time xxxx-xx-xx xx:xx:xx
     * @params extras array('event_type'=>'push','event_params'=>array('style'=>'style01','present':'NO','url':'http://xxx'))
     */
    public function createTask($params){
        if (!empty($params['send_time'])) {
            $params['send_time'] = strtotime($params['send_time']);
        }
        switch ($params['platform']) {
            case 'ios':
                $platform = array('ios');
                break;
            case 'android':
                $platform = array('android');
                break;
            default:
                $platform = array('ios','android');
                break;
        }
        try {
            $client = new JpushClient($this->conf['app_key'],$this->conf['master_secret']);
            $task = $client->push()
            ->setPlatform($platform)
            ->addAllAudience()
            ->setNotificationAlert($params['content'])
            ->iosNotification($params['content'], array(
            'sound'=>'default',
            'extras' => $params['extras'],
            ))
            ->androidNotification($params['content'], array(
            'title' => $params['title'],
            'extras' => $params['extras'],
            ))
            ->message($params['content'], array(
                'title' => $params['title'],
                'extras' => $params['extras'],
            ));
            if (empty($params['send_time']) || $params['send_time'] < time()) {
                $res = $task->send(); //直接发送
            } else {
                $task_build = $task->build();
                $schedule = $client->schedule();
                $res = $schedule->createSingleSchedule($params['task_mark'], $task_build, array('time' => date('Y-m-d H:i:s', $params['send_time']))); //定时发送
            }
        } catch (Exception $e) {
            $err_msg = $e->getMessage();
            $this->getDi()->get('logger')->error('Jpush report error:'.$err_msg);
            return false;
        }
        return $res;
    }
}
