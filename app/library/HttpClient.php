<?php
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise;

class HttpClient{

    private $method;

    private $headers=[];

    private $timeout=60;

    private $errors=[];

    private $return=[];

    private $max_concurrency =3000;

    public function getErrors(){
        return $this->errors;
    }

    //同一个请求地址
    public function  simpleMultiple($url ,$method='GET' , $params=[] ,$headers=[] ,$timeout=60){
        $method =strtoupper($method);
        if(isset($_SERVER['SHELL']) && in_array($method ,array('GET', 'POST'))){
            return $this->_cliMultiple($url ,$method ,$params ,$headers ,$timeout);
        }else{
            $request_list = [];
            foreach($params as $data){
                $request_list[] =[
                    'url' =>$url,
                    'data' =>$data
                ];
            }
            return $this->multiple($request_list ,$method ,$headers ,$timeout);            
        }
    }


    //不同请求地址
    public function multiple($request_list ,$method='GET'  ,$headers=array() ,$timeout=60){
        
        $this->method =strtoupper($method);
        $this->timeout =$timeout;
        $this->headers =$headers;
        $client = new Client(array(
            'timeout'  => $timeout,
        ));
        //        $client->setDefaultOption('verify', false);

        $requests = function ($request_count)use($request_list ,$client ) {
            foreach($request_list as $request){
                $this->getHeaders($request['data']);
                $url =$request['url'];
                $request_data =is_array($request['data']) ? http_build_query($request['data']) : '';

                if($this->method =='GET'){
                    $url .= $request_data ? ('?'.$request_data)  :'';
                    $request_data =null;
                }
                yield new Request(
                    $this->method,
                    $request['url'] ,
                    $this->headers,
                    $request_data
                );
                /*
                yield function ()use($request ,$client){
                    return $client->requestAsync($this->method ,$request['url']);
                };
                */
            }

        };

        $request_count = count($request_list);
        $concurrency = $request_count < $this->max_concurrency ? $request_count :$this->max_concurrency;
        $pool = new Pool($client, $requests($request_count), [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) {
                $this->return[]=array(
                    'index'=>$index,
                    'content'=>$response->getBody()->getContents()
                );
            },
            'rejected' => function ($reason, $index) {
                $this->errors[]=array(
                    'index'=>$index,
                    'reason'=>$reason->getMessage()
                );
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
        return $this->return;
    }

    private function _cliMultiple($url, $method, $params  ,$headers=array() ,$timeout=60){
        $this->method =strtoupper($method);
        $this->headers =$headers;
        $timeout =$timeout ? : -1;
        $result =[];
        $options =$this->_getOptions($url);
        $host =$options['host'];
        $port =$options['port'];
        $ssl =$options['ssl'];
        foreach($params as $request_data) {
            $uri =$options['path'];

            $this->getHeaders($request_data);
            $client = new \swoole_http_client($host , $port, $ssl);
            $client->set(['timeout'=>$timeout]);
            $client->setHeaders($this->headers);
            $request_data =is_array($request_data) ? http_build_query($request_data) : '';
            if($method =='GET'){
                $uri .= $request_data ? '?'.$request_data : '';
                $client->get($uri, function ($o) use($client ,&$result) {
                    $result[] =$o->body;
                    $client->close();
                });                
            }else{
                $client->post($uri, $request_data, function ($o) use($client ,&$result) {
                    $result[] =$o->body;
                    $client->close();
                });                  
            }
        }
        \Swoole\Event::wait();
        return $result;
    }


    private function getHeaders($data){
        if($this->method !='GET' && !isset($this->headers['Content-Type'])){
            $this->headers['Content-Type']=['application/x-www-form-urlencoded'];
            if(is_string($data)){
                $this->headers['Content-Type'] =['application/json; charset=utf-8'];
                $this->headers['Content-Length'] =strlen($data);
            }
        }
        return $this->headers;
    }

    private function _getOptions($url){
        $url_arr =parse_url($url);
        $return['host']= $url_arr['host'];
        $return['path']= $url_arr['path'];
        $return['ssl'] =$url_arr['scheme']=='https' ? true :false;
        $return['port'] =isset($url_arr['port']) ? $url_arr['port'] :($url_arr['scheme']=='https' ?443: 80);
        return $return;
    }
}
