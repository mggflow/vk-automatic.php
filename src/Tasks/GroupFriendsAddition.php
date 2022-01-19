<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\AddFriendsByIds;
use MGGFLOW\VK\Automatic\API\GetGroupRandUsers;
use MGGFLOW\VK\Automatic\API\UserFriendRating;

class GroupFriendsAddition extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'groupUsersResult' => false,
            'ratedUsers' => false,
            'addition' => false,
        ];

        $usersParams = [
            'group_id' => $this->taskData->groupId,
            'fields' => UserFriendRating::NECESSARY_FIELDS,
        ];
        $result->groupUsersResult = GetGroupRandUsers::get(
            $this->api,
            $this->taskData->volume,
            $usersParams,
        );
        if (empty($result->groupUsersResult->members)) return $result;

        $result->ratedUsers = UserFriendRating::createRating($result->groupUsersResult->members);
        $futureFriendsIds = UserFriendRating::takeTopIds($result->ratedUsers->rating, $this->taskData->successVolume);
        $result->addition = AddFriendsByIds::add($this->api, $futureFriendsIds);

        return $result;
    }
}