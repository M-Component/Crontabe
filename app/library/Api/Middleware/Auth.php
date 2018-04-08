<?php
//------------------------------------------------------------
//----------------------------wl------------------------------
//------------------------------------------------------------
namespace Api\Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class Auth implements MiddlewareInterface{

    public function call(\Phalcon\Mvc\Micro $application)
    {
        if (!$this->hasAccess($application['request'])) {
            $application['response']->setStatusCode(401, "Unauthorized");
            $application['response']->setContent("Access is not authorized");
            $application['response']->send();
            exit;
        }
        return true;
    }

    private function hasAccess($request){
        if(!$token=$request->getHeader('Authorization')){
                       return true;
        }
        if($token !='FkJll3ddhiwx3Yq2ceVw'){
            return false;
        }
        return true;
    }
}
