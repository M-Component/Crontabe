<?php

namespace Component\Wechat;

class MessageTemplate
{

    private $template_id;
    private $industry;
    private $access_token;

    public function __construct($access_token)
    {
        $this->access_token = $access_token;
    }

    // 设置所属行业 每月可修改行业1次
    public function setIndustry(array $ids)
    {
        $action_url = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=' . $this->access_token;
        $params = array(
            'industry_id1' => $ids[0],
            'industry_id2' => $ids[1]
        );
        $res = json_decode(\Utils::curl_client($action_url, json_encode($params), 'POST'), 1);
        if ($res['errcode'] &&  $res['errcode'] != 0) {
            throw new \Exception($res['errmsg']);
        }
        return $this;
    }

    public function getIndustry()
    {
        $action_url = 'https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=';
        $res = json_decode(\Utils::curl_client($action_url), 1);
        if ($res['errcode'] && $res['errcode'] != 0) {
            throw new \Exception($res['errmsg']);
        }
        $this->industry = $res ['industry'];
        return $this;
    }

    public function setTemplateId($template_id)
    {
        $this->template_id = $template_id;
        return $this;
    }

    // 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式"TM00015"
    // 从行业模板库选择模板到帐号后台，获得模板ID的过程可在微信公众平台后台完成。
    public function addTemplateId($template_id_short = 'TM00015')
    {
        $action_url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $this->access_token;
        $params = array(
            'template_id_short' => $template_id_short
        );
        $res = json_decode(\Utils::curl_client($action_url, json_encode($params), "POST"), 1);
        if ($res['errcode'] && $res['errcode'] != 0) {
            throw new \Exception($res['errmsg']);
        }
        return $res['template_id'];
    }

    public function getTemplateList()
    {
        $action_url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' . $this->access_token;
        $res = json_decode(\Utils::curl_client($action_url), 1);
        if ($res['errcode'] && $res['errcode'] != '0') {
            throw new \Exception($res['errmsg']);
        }
        return $res['template_list'];
    }

    public function deleteTemplate()
    {
        $action_url = "https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=" . $this->access_token;
        $params = array(
            'template_id' => $this->template_id
        );

        $res = json_decode(\Utils::curl_client($action_url, json_encode($params), "POST"), 1);
        if ($res['errmsg'] == 'ok') {
            return true;
        }
        throw new \Exception('删除模版失败');
    }

    // 发送模版消息
    public function sendTempMsg($params)
    {
        $params['template_id'] ?: $params['template_id'] = $this->template_id;
        $action_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->access_token;

        $res = json_decode(\Utils::curl_client($action_url, json_encode($params), "POST"), 1);

        if ($res['errmsg'] == 'ok') {
            return $res['msgid'];
        }
        throw new \Exception('消息发送失败');
    }
}
