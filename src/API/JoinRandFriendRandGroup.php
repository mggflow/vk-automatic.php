<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class JoinRandFriendRandGroup
{
    static function join(API $api)
    {
        $result = (object)[
            'friendsAmount' => false,
            'friendsOffset' => false,
            'friends' => false,
            'groupsAmount' => false,
            'groupsOffset' => false,
            'groups' => false,
            'joined' => false,
        ];

        $randFriend = GetRand::get($api, 'friends', 'get', 1);
        $result->friendsAmount = $randFriend->amount;
        $result->friendsOffset = $randFriend->offset;
        $result->friends = $randFriend->items;

        if (empty($result->friends)) return $result;
        $friendId = $result->friends[0];

        $friendGroups = GetRand::get($api, 'groups', 'get', 1, ['user_id' => $friendId]);
        $result->groupsAmount = $friendGroups->amount;
        $result->groupsOffset = $friendGroups->offset;
        $result->groups = $friendGroups->items;

        if (empty($result->groups)) return $result;
        $groupId = $result->groups[0];

        $result->joined = $api->groups->join([
            'group_id' => $groupId
        ])->explore(true, 3, true);

        return $result;
    }
}