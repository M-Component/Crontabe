<?php
namespace Task;
interface TaskInterface{
    public function exec($job =null ,$params = null);
}