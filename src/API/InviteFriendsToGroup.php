<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class InviteFriendsToGroup
{
    public static function invite(API $api, Iterator $iterator, int $groupId): object
    {
        $result = (object)[
            'friendsAmount' => false,
            'invited' => []
        ];

        $countFriends = $api->friends->get([
            'count' => 0,
        ])->explore(true, 3, true);
        if (empty($countFriends)) return $result;
        $iterator->amount = $countFriends->count;
        $result->friendsAmount = $countFriends->count;

        while ($iterator->continue()) {
            $requestsResponse = $api->friends->get([
                'offset' => $iterator->offset,
                'count' => $iterator->count,
            ])->explore(true, 3, true);
            if (empty($requestsResponse)) break;
            $iterator->incInert(count($requestsResponse->items));

            $userIdsString = implode(',', $requestsResponse->items);
            $memberResponse = $api->groups->isMember([
                'group_id' => $groupId,
                'user_ids' => $userIdsString,
                'extended' => 1,
            ])->explore(true, 3, true);
            if (empty($memberResponse)) break;

            foreach ($memberResponse as $member) {
                if ((isset($member->invitation) and $member->invitation)
                    or (isset($member->can_invite) and !$member->can_invite)
                    or (isset($member->member) and $member->member)) continue;

                $result->invited[$member->user_id] = $api->groups->invite([
                    'group_id' => $groupId,
                    'user_id' => $member->user_id,
                ])->explore(true, 3, true);
                $iterator->incSuccess();
            }
        }

        return $result;
    }
}