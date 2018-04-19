namespace Openapi;
class Wechat extends Base{

    private $wechat;

    public function __construct(){
        parent::__construct();
        $this->wechat = new \Component\Wechat();
    }

    public function doRequest(){
        $echostr =$this->request->getQuery('echostr');
        $this->wechat->setRequestParams($this->request->getQuery());
        if($echostr){
            if($this->wechat->checkSignature($signature ,$timestamp ,$nonce)){
                echo $echostr;
            }            
        }
        $post_xml =file_get_contents('php://input');
        if(!empty($post_xml)){
            $this->wechat->doPost($post_xml);
        }else{
            echo '';
        }
    }
}
