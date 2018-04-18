<?php

namespace Sender;


interface SenderModelInterface
{
    public function getName($id);
    public function getAll($filter = array());
    public function getById($id);
    public function getByName($class_name);
    public function getObj($driver_name);
    public function getInfo($driver_name);
}