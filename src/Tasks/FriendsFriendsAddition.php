<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\AddFriendsByIds;
use MGGFLOW\VK\Automatic\API\GetRandFriendRandFriends;
use MGGFLOW\VK\Automatic\API\UserFriendRating;

class FriendsFriendsAddition extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'friendFriendsResult' => false,
            'ratedUsers' => false,
            'addition' => false,
        ];

        $result->friendFriendsResult = GetRandFriendRandFriends::get(
            $this->api,
            $this->taskData->volume,
            ['fields' => UserFriendRating::NECESSARY_FIELDS]
        );
        if (empty($result->friendFriendsResult->friendFriends)) return $result;

        $result->ratedUsers = UserFriendRating::createRating($result->friendFriendsResult->friendFriends);
        $futureFriendsIds = UserFriendRating::takeTopIds($result->ratedUsers->rating, $this->taskData->successVolume);
        $result->addition = AddFriendsByIds::add($this->api, $futureFriendsIds);

        return $result;
    }
}