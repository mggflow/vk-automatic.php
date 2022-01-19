<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\LikeUserRandItem;

class UserRandItemPleasure extends VKTask implements Task
{
    public function execute(): object
    {
        return (object)[
            'like' => LikeUserRandItem::like($this->api, $this->taskData->userId)
        ];
    }
}