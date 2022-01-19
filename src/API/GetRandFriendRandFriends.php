<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class GetRandFriendRandFriends
{
    static function get(API $api, int $count, array $params = [])
    {
        $result = (object)[
            'friendsAmount' => false,
            'friendsOffset' => false,
            'friends' => false,
            'friendFriendsAmount' => false,
            'friendFriendsOffset' => false,
            'friendFriends' => false,
        ];

        $randFriend = GetRand::get($api, 'friends', 'get', 1);
        $result->friendsAmount = $randFriend->amount;
        $result->friendsOffset = $randFriend->offset;
        $result->friends = $randFriend->items;

        if (empty($result->friends)) return $result;
        $friendId = $result->friends[0];

        $friendFriendsParams = array_merge($params, ['user_id' => $friendId]);
        $friendFriends = GetRand::get($api, 'friends', 'get', $count, $friendFriendsParams);
        $result->friendFriendsAmount = $friendFriends->amount;
        $result->friendFriendsOffset = $friendFriends->offset;
        $result->friendFriends = $friendFriends->items;

        return $result;
    }
}