<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class ApproveGroupMemberRequests
{
    public static function approve(API $api, Iterator $iterator, int $groupId): object
    {
        $result = (object)[
            'requestsAmount' => false,
            'approved' => [],
        ];

        $countMemberRequests = $api->groups->getRequests([
            'group_id' => $groupId,
            'count' => 0,
        ])->explore(true, 3, true);
        if (empty($countMemberRequests)) return $result;
        $iterator->amount = $countMemberRequests->count;
        $result->requestsAmount = $countMemberRequests->count;

        while ($iterator->continue()) {
            $requestsResponse = $api->groups->getRequests([
                'group_id' => $groupId,
                'offset' => $iterator->offset,
                'count' => $iterator->count,
            ])->explore(true, 3, true);
            if (empty($requestsResponse)) break;

            foreach ($requestsResponse->items as $userId) {
                $iterator->incInert(1);
                $result->approved[$userId] = $api->groups->approveRequest([
                    'group_id' => $groupId,
                    'user_id' => $userId,
                ])->explore(true, 3, true);
                $iterator->incSuccess();
            }
        }


        return $result;
    }
}