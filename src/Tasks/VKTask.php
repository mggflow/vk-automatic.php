<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\API;

class VKTask
{
    protected API $api;
    protected ?object $taskData;

    public function __construct(API $api, object $taskData = null)
    {
        $this->api = $api;
        $this->taskData = $taskData;
    }
}