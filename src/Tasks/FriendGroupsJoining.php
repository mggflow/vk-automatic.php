<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\JoinRandFriendRandGroup;

class FriendGroupsJoining extends VKTask implements Task
{
    public function execute(): object
    {
        return (object)[
            'joinResult' => JoinRandFriendRandGroup::join($this->api)
        ];
    }
}