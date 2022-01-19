<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class AcceptIncomingFriendRequests
{
    public static function accept(API $api, Iterator $iterator): object
    {
        $result = (object)[
            'requestsAmount' => false,
            'accepted' => []
        ];

        $countFriendRequests = $api->friends->getRequests([
            'count' => 0,
        ])->explore(true, 3, true);
        if (empty($countFriendRequests)) return $result;
        $iterator->amount = $countFriendRequests->count;
        $result->requestsAmount = $countFriendRequests->count;

        while ($iterator->continue()) {
            $requestsResponse = $api->friends->getRequests([
                'offset' => $iterator->offset,
                'count' => $iterator->count,
            ])->explore(true, 3, true);
            if (empty($requestsResponse)) break;

            foreach ($requestsResponse->items as $userId) {
                $iterator->incInert(1);
                if ($iterator->successVolumeReached()) break;

                $result->accepted[$userId] = $api->friends->add([
                    'user_id' => $userId
                ])->explore(true, 3, true);
                $iterator->incSuccess();
            }
        }

        return $result;
    }
}