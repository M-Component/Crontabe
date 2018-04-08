<?php


class RoleAccount extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->belongsTo('role_id', 'Role', 'id');
        $this->belongsTo('account_id', 'Account', 'id');
    }
}