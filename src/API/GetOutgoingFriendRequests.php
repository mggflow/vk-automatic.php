<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class GetOutgoingFriendRequests
{
    static function get(API $api, Iterator $iterator): object
    {
        $result = (object)[
            'amount' => 0,
            'users' => [],
        ];

        $countRequests = $api->friends->getRequests([
            'out' => 1,
            'count' => 0,
        ])->explore(true, 3, true);
        if (empty($countRequests)) return $result;
        $iterator->amount = $countRequests->count;
        $result->amount = $countRequests->count;

        while ($iterator->continue()) {
            $requestsResponse = $api->friends->getRequests([
                'out' => 1,
                'offset' => $iterator->offset,
                'count' => $iterator->count,
            ])->explore(true, 3, true);
            if (empty($requestsResponse)) break;
            $iterator->incInert(count($requestsResponse->items));

            $result->users = array_merge($result->users, $requestsResponse->items);
        }

        return $result;
    }
}