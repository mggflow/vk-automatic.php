<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\API\GetRandFriendRandFriends;
use MGGFLOW\VK\Automatic\API\LikeUserRandItem;
use MGGFLOW\VK\Automatic\Interfaces\Task;

class FriendsFriendsPleasure extends VKTask implements Task
{

    public function execute(): object
    {
        $result = (object)[
            'friendFriendsResult' => false,
            'likesResult' => [],
        ];

        $result->friendFriendsResult = GetRandFriendRandFriends::get(
            $this->api,
            $this->taskData->volume,
        );
        if (empty($result->friendFriendsResult->friendFriends)) return $result;

        foreach ($result->friendFriendsResult->friendFriends as $userId){
            $result->likesResult[$userId] = LikeUserRandItem::like($this->api, $userId);
        }

        return $result;
    }
}