<?php

namespace Task\Queue\Jobs;

interface JobInterface
{

    public function fire();

    public function delete();

    public function isDeleted();

    public function release($delay = 0);

    public function isDeletedOrReleased();

    public function attempts();

    public function getName();

    public function failed();

    public function getQueue();

    public function getRawBody();
}
