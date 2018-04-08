<?php

namespace Component\Sms\Socket;

use Phalcon\Mvc\User\Component;

class Base extends Component
{

 /*   public $description = <<<TOP
<div class="box box-solid">
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-condensed">
                <tbody>
                <tr>
                  <td><font><font>ENV_accounts</font></font></td>
                  <td><font><font>String</font></font></td>
                  <td><font><font>通道账号</font></font></td>
                </tr>
                <tr>
                  <td><font><font>ENV_pwd</font></font></td>
                  <td><font><font>String</font></font></td>
                  <td><font><font>通道密码</font></font></td>
                </tr>
                <tr>
                  <td><font><font>ENV_target</font></font></td>
                  <td><font><font>String</font></font></td>
                  <td><font><font>手机号码</font></font></td>
                </tr>
                <tr>
                  <td><font><font>ENV_content</font></font></td>
                  <td><font><font>String</font></font></td>
                  <td><font><font>短信内容</font></font></td>
                </tr>
                <tr>
                  <td><font><font>ENV_sign</font></font></td>
                  <td><font><font>String</font></font></td>
                  <td><font><font>请求安全签名</font></font></td>
                </tr>
                <tr>
                  <td><font><font>ENV_ex</font></font></td>
                  <td><font><font>String</font></font></td>
                  <td><font><font>拓展码</font></font></td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>

TOP;*/

    /**
     * 获取配置参数
     *
     * @param $key string
     * @param $pkey api interface class name
     * @return mixed
     */
    public function getConf($key, $pkey)
    {
        $val = \Setting::getConf($pkey);
        return $val[$key];
    }
}