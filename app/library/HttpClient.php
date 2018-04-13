<?php
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class HttpClient{

    private $method;

    private $headers=[];

    private $timeout=60;

    private $errors=[];

    private $return=[];

    public function getErrors(){
        return $this->errors;
    }

    public function multiple($request_list ,$method='GET' ,$timeout=20 ,$headers=array() ){
        $this->method =strtoupper($method);
        $this->headers =array(
            'Content-Type'=>'application/x-www-form-urlencoded'
        );
        $this->timeout =$timeout;

        $client = new Client(array(
            'timeout'  => $timeout
        ));

        $pool = new Pool($client, $this->request($request_list), [
            'concurrency' => count($request_list),
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

    /*
    eg:  headers 
    array(
        'Content-Type' =>'application/json; charset=utf-8',
        'Content-Length' =>strlen($data),
    )
    */
    public function request($request_list){
        foreach ($request_list as $request) {
            $url =$request['url'];
            yield new Request(
                $this->method,
                $url,
                $this->headers,
                http_build_query($request['data'])
            );
        }
    }
}
