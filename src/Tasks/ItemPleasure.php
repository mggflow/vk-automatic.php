<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\LikeItem;

class ItemPleasure extends VKTask implements Task
{
    public function execute(): object
    {
        return (object)[
            'like' => LikeItem::like($this->api, $this->taskData->type, $this->taskData->ownerId, $this->taskData->itemId),
        ];
    }
}