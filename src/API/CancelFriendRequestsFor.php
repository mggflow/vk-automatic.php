<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class CancelFriendRequestsFor
{
    static function cancel(API $api, array $userIds): object
    {
        $result = (object)[
            'cancelled' => []
        ];

        foreach ($userIds as $userId) {
            $result->cancelled[$userId] = $api->friends->delete([
                'user_id' => $userId,
            ])->explore(true, 3, true);
        }

        return $result;
    }
}