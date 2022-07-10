<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\API;

class VKTask
{
    protected API $api;
    protected ?object $taskData;

    /**
     * Make Task from API and Data object.
     * @param API $api
     * @param object|null $taskData
     */
    public function __construct(API $api, object $taskData = null)
    {
        $this->api = $api;
        $this->taskData = $taskData;
    }
}