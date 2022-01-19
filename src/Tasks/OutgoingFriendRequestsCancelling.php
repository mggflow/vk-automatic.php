<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\CancelFriendRequestsFor;

class OutgoingFriendRequestsCancelling extends VKTask implements Task
{
    public function execute(): object
    {
        return (object)[
            'cancelling' => CancelFriendRequestsFor::cancel($this->api, $this->taskData->userIds)
        ];
    }

}