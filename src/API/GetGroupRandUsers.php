<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class GetGroupRandUsers
{
    static function get(API $api, int $count, array $params)
    {
        $result = (object)[
            'membersAmount' => false,
            'membersOffset' => false,
            'members' => false,
        ];

        $randMembers = GetRand::get($api, 'groups', 'getMembers', $count, $params);
        $result->membersAmount = $randMembers->amount;
        $result->membersOffset = $randMembers->offset;
        $result->members = $randMembers->items;

        return $result;
    }
}