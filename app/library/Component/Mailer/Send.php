<?php
namespace Component\Mailer;
/**
 *  发送邮件
 */

class Send
{

    private $driver;

    public function __construct()
    {
        $mailer = new \Mailer();
        $drivers = $mailer->getAll();

        // 获取被启用的配置项
        foreach ($drivers as $k => &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $v['mailer_name'];
                break;
            }
        }
    }

    public function send($params,$type = 'reg')
    {
        $config = \Setting::getConf($this->driver);
        unset($config['order_num']);
        unset($config['status']);
        if ($config['email'] && $config['name']) {
            $config['from']['email'] = $config['email'];
            $config['from']['name'] = $config['name'];
        }
        unset($config['email']);
        unset($config['name']);

        $mailer = new \Phalcon\Mailer\Manager($config);

        switch ($type) {
            case 'reg':
                $viewPath = 'mailer/themes/reg';
                break;

            case 'reset':
                $viewPath = 'mailer/themes/reset';
                break;

            default:
                $viewPath = 'mailer/theme/default';
                break;
        }
        $params['send_time'] = date('Y年m月d日',time());
        $message = $mailer->createMessageFromView($viewPath, $params)
            ->to($params['email'], $params['email'])
            ->subject($params['subject']);

        if($message->send()){
            $msg = new \Message();
            $data['target'] = $params['email'];
            $data['type'] = 2;
            $data['title'] = $params['subject'];
            $data['content'] = $message->getContent();
            $data['create_time'] = time();
            if($msg->create($data)){
                unset($msg);
                return true;
            }else{
                return false;
            }
        }
    }
}
