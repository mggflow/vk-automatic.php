<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class AddFriendsByIds
{
    static function add(API $api, array $friendIds): object
    {
        $result = (object)[
            'addedFriends' => false,
        ];

        foreach ($friendIds as $id) {
            $result->addedFriends[$id] = $api->friends->add([
                'user_id' => $id
            ])->explore(true, 3, true);
            if (empty($result->addedFriends[$id])) break;
        }

        return $result;
    }
}