<?php

/**
 * Created by PhpStorm.
 * User: wl
 * Date: 16/8/29
 * Time: 下午10:48
 */
class  Utils
{
    public static function client_source()
    {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $mobile_agents = array('240x320', 'acer', 'acoon', 'acs-', 'abacho', 'ahong', 'airness', 'alcatel', 'amoi', 'android', 'anywhereyougo.com', 'applewebkit/525', 'applewebkit/532', 'asus', 'audio', 'au-mic', 'avantogo', 'becker', 'benq', 'bilbo', 'bird', 'blackberry', 'blazer', 'bleu', 'cdm-', 'compal', 'coolpad', 'danger', 'dbtel', 'dopod', 'elaine', 'eric', 'etouch', 'fly ', 'fly_', 'fly-', 'go.web', 'goodaccess', 'gradiente', 'grundig', 'haier', 'hedy', 'hitachi', 'htc', 'huawei', 'hutchison', 'inno', 'ipad', 'ipaq', 'ipod', 'jbrowser', 'kddi', 'kgt', 'kwc', 'lenovo', 'lg ', 'lg2', 'lg3', 'lg4', 'lg5', 'lg7', 'lg8', 'lg9', 'lg-', 'lge-', 'lge9', 'longcos', 'maemo', 'mercator', 'meridian', 'micromax', 'midp', 'mini', 'mitsu', 'mmm', 'mmp', 'mobi', 'mot-', 'moto', 'nec-', 'netfront', 'newgen', 'nexian', 'nf-browser', 'nintendo', 'nitro', 'nokia', 'nook', 'novarra', 'obigo', 'palm', 'panasonic', 'pantech', 'philips', 'phone', 'pg-', 'playstation', 'pocket', 'pt-', 'qc-', 'qtek', 'rover', 'sagem', 'sama', 'samu', 'sanyo', 'samsung', 'sch-', 'scooter', 'sec-', 'sendo', 'sgh-', 'sharp', 'siemens', 'sie-', 'softbank', 'sony', 'spice', 'sprint', 'spv', 'symbian', 'tablet', 'talkabout', 'tcl-', 'teleca', 'telit', 'tianyu', 'tim-', 'toshiba', 'tsm', 'up.browser', 'utec', 'utstar', 'verykool', 'virgin', 'vk-', 'voda', 'voxtel', 'vx', 'wap', 'wellco', 'wig browser', 'wii', 'windows ce', 'wireless', 'xda', 'xde', 'zte');
        foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                return 'mobile';
            }
        }
        return 'pc';
    }

    public static function is_mobile()
    {
        return self::client_source()=='mobile'? true :false;
    }

    public static function is_wechat(){
        return self::source()=='wechat'? true :false;
    }

    public static function source()
    {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $mobile_agents = array('240x320', 'acer', 'acoon', 'acs-', 'abacho', 'ahong', 'airness', 'alcatel', 'amoi', 'android', 'anywhereyougo.com', 'applewebkit/525', 'applewebkit/532', 'asus', 'audio', 'au-mic', 'avantogo', 'becker', 'benq', 'bilbo', 'bird', 'blackberry', 'blazer', 'bleu', 'cdm-', 'compal', 'coolpad', 'danger', 'dbtel', 'dopod', 'elaine', 'eric', 'etouch', 'fly ', 'fly_', 'fly-', 'go.web', 'goodaccess', 'gradiente', 'grundig', 'haier', 'hedy', 'hitachi', 'htc', 'huawei', 'hutchison', 'inno', 'ipad', 'ipaq', 'ipod', 'jbrowser', 'kddi', 'kgt', 'kwc', 'lenovo', 'lg ', 'lg2', 'lg3', 'lg4', 'lg5', 'lg7', 'lg8', 'lg9', 'lg-', 'lge-', 'lge9', 'longcos', 'maemo', 'mercator', 'meridian', 'micromax', 'midp', 'mini', 'mitsu', 'mmm', 'mmp', 'mobi', 'mot-', 'moto', 'nec-', 'netfront', 'newgen', 'nexian', 'nf-browser', 'nintendo', 'nitro', 'nokia', 'nook', 'novarra', 'obigo', 'palm', 'panasonic', 'pantech', 'philips', 'phone', 'pg-', 'playstation', 'pocket', 'pt-', 'qc-', 'qtek', 'rover', 'sagem', 'sama', 'samu', 'sanyo', 'samsung', 'sch-', 'scooter', 'sec-', 'sendo', 'sgh-', 'sharp', 'siemens', 'sie-', 'softbank', 'sony', 'spice', 'sprint', 'spv', 'symbian', 'tablet', 'talkabout', 'tcl-', 'teleca', 'telit', 'tianyu', 'tim-', 'toshiba', 'tsm', 'up.browser', 'utec', 'utstar', 'verykool', 'virgin', 'vk-', 'voda', 'voxtel', 'vx', 'wap', 'wellco', 'wig browser', 'wii', 'windows ce', 'wireless', 'xda', 'xde', 'zte');
        foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                if (stristr($user_agent, 'MicroMessenger')) {
                    return 'wechat';
                }
                return 'h5';
            }
        }
        return 'pc';
    }

    public static function priceFormat($price)
    {
        return round($price, 2);
    }


    public static function &array_change_key(&$items, $key, $is_resultset_array = false)
    {
        if (is_array($items)) {
            $result = array();
            if (!empty($key) && is_string($key)) {
                foreach ($items as $_k => $_item) {
                    if ($is_resultset_array) {
                        $result[$_item[$key]][] = &$items[$_k];
                    } else {
                        $result[$_item[$key]] = &$items[$_k];
                    }
                }

                return $result;
            }
        }

        return false;
    }

    public static function get_tree($items, $id_name)
    {
        $items = \Utils::array_change_key($items, $id_name);
        $tree = array();
        foreach ($items as $item) {
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][$item[$id_name]] = &$items[$item[$id_name]];
            } else {
                $tree[$item[$id_name]] = &$items[$item[$id_name]];
            }
        }
        return $tree;
    }

    public static function base_url()
    {
        $request = new Phalcon\Http\Request();
        return strtolower($request->getScheme() . '://' . $request->getHttpHost());
    }

    public static function qrcode($message ,$size=5 ,$margin=3){
        $message = urlencode($message);
        $params =array(
            'message' =>$message,
            'size' =>$size ,
            'margin' =>$margin
        );
        $url =  '/openapi/qrcode?'.http_build_query($params);
        return '<img src="'.$url.'" class="qrcode-image">';
    }

    //过滤用户输入的数据，防范xss攻击
    public static function _RemoveXSS($val)
    {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@avascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

            // @ @ search for the hex values
            $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }

        return $val;
    }

    public static function inputFilter($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $v) {
                $data[$key] = self::inputFilter($data[$key]);
            }
        } else {
            if (strlen($data)) {
                $data = self::_RemoveXSS($data);
            }
        }
        return $data;
    }

    /**
     * 可逆加密.
     */
    public static function encrypt($data)
    {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, 'abcdefgh', $prep_code, MCRYPT_MODE_ECB);
        $str = base64_encode($encrypt);
        $str = str_replace('+','-',$str);
        $str = str_replace('/','_',$str);
        return $str;
    }
    /**
     * 可逆解密.
     */
    public static function decrypt($str)
    {
        $str = str_replace('-','+',$str);
        $str = str_replace('_','/',$str);
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, 'abcdefgh', $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/'.chr($pad).'{'.$pad.'}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }


    public static function encode_args($args)
    {
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                $args[$key] = str_replace(array(
                    '-',
                    '+',
                    '.',
                    '/',
                    '?',
                    '=',
                    '&',
                    '%2F',
                ), array(
                    '_h_',
                    '_j_',
                    '_d_',
                    '_x_',
                    '_w_',
                    '_e_',
                    '_a_',
                    '_x_',
                ), $val);
            }
        } else {
            $args = str_replace(array(
                '-',
                '+',
                '.',
                '/',
                '?',
                '=',
                '&',
                '%2F',
            ), array(
                '_h_',
                '_j_',
                '_d_',
                '_x_',
                '_w_',
                '_e_',
                '_a_',
                '_x_',
            ), $args);
        }

        return $args;
    }
    /*
     * 参数特殊解码
     * @var array $args
     * @access public
     * @return void
    */
    public static function decode_args($args)
    {
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                $args[$key] = str_replace(array(
                    '_h_',
                    '_j_',
                    '_d_',
                    '_x_',
                    '_w_',
                    '_e_',
                    '_a_',
                    '_x_',
                ), array(
                    '-',
                    '+',
                    '.',
                    '/',
                    '?',
                    '=',
                    '&',
                    '%2F',
                ), $val);
            }
        } else {
            $args = str_replace(array(
                '_h_',
                '_j_',
                '_d_',
                '_x_',
                '_w_',
                '_e_',
                '_a_',
                '_x_',
            ), array(
                '-',
                '+',
                '.',
                '/',
                '?',
                '=',
                '&',
                '%2F',
            ), $args);
        }

        return $args;
    }

    public static function http_build_query($arr, $prefix = '', $arg_separator = '&')
    {
        if (version_compare(phpversion(), '5.1.2', '>=')) {
            return http_build_query($arr, $prefix, $arg_separator);
        } else {
            $org = ini_get('arg_separator.output');
            if ($org !== $arg_separator) {
                ini_set('arg_separator.output', $arg_separator);
                $replace = $org;
            }
            $string = http_build_query($arr, $prefix);
            if (isset($replace)) {
                ini_set('arg_separator.output', $replace);
            }

            return $string;
        }
    }

    public static function random($length=32){
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    public static function randomkeys($length=6) {
        $key = '';
        $pattern = '1234567890'; //字符池
        for ($i = 0;$i < $length;$i++) {
            $key.= $pattern{mt_rand(0, 9) }; //生成php随机数

        }
        return $key;
    }

    public static function curl_client($url, $data=null, $method = 'GET' ,$is_string=false){
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL =>$url,
            CURLOPT_HTTPHEADER => array(
                'Authorization: FkJll3ddhiwx3Yq2ceVw'
            )
        );
        $method = strtoupper($method);
        if($method =='GET'){
            $opts[CURLOPT_URL] = $url . (is_array($data) ? '?' . http_build_query($data) :'');
        }else{
            switch ($method){
                case "POST":
                    $opts[CURLOPT_POST] = 1;
                    break;
                case "PUT" :
                    $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
                    break;
                case "PATCH":
                    $opts[CURLOPT_CUSTOMREQUEST] = 'PATCH';
                    break;
                case "DELETE":
                    $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                    break;
            }
            if(is_string($data)){ //发送JSON数据
                $opts[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: ' . strlen($data),
                    'Authorization: FkJll3ddhiwx3Yq2ceVw'
                );
            }
            $opts[CURLOPT_POSTFIELDS] = $is_string ? http_build_query($data) :$data;
        }
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) return false ;
        return  $data;
    }

    public static function addUrlQuery($url, $parameters = array()) {
        if (!is_array($parameters)) {
            return $url;
        }
        $p = parse_url($url);
        if (isset($p['query']) && isset($p['fragment'])) {
            parse_str($p['query'], $arr);
            $q = http_build_query(array_merge($arr, $parameters));
            return strstr($url, '?', true) . '?' . $q . '#' . $p['fragment'];
        } elseif (isset($p['query']) && !isset($p['fragment'])) {
            parse_str($p['query'], $arr);
            $q = http_build_query(array_merge($arr, $parameters));
            return strstr($url, '?', true) . '?' . $q;
        } elseif (!isset($p['query']) && isset($p['fragment'])) {
            $q = http_build_query($parameters);
            return strstr($url, '#', true) . '?' . $q . '#' . $p['fragment'];
        } else {
            $q = http_build_query($parameters);
            return $url  . '?' . $q;
        }
    }

    public static function isMobile($string){
       return preg_match('/^1[34578]{1}[0-9]{9}$/', $string) ? true :false;
    }

    public static function isEmail($string){
      return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $string) ? true :false;
    }
}
