<?php

namespace Component\Oauth;
interface OauthInterface
{

    public function setting();

    public function authorize_url();

    public function callback(&$params);

}

