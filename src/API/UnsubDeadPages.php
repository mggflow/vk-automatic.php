<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class UnsubDeadPages
{
    public static function unsub(API $api, Iterator $iterator): object
    {
        $result = (object)[
            'unsub' => [],
            'checkedCounter' => 0,
        ];

        $countFriends = $api->friends->get([
            'count' => 0,
        ])->explore(true, 3, true);
        if (empty($countFriends)) return $result;
        $iterator->amount = $countFriends->count;

        while ($iterator->continue()) {
            $friendsResponse = $api->friends->get([
                'offset' => $iterator->offset,
                'count' => $iterator->count,
                'fields' => 'online',
            ])->explore(true, 3, true);
            if (empty($friendsResponse)) break;
            $iterator->incInert(count($friendsResponse->items));

            foreach ($friendsResponse->items as $user) {
                $result->checkedCounter++;
                if (isset($user->deactivated)) {
                    if ($iterator->successVolumeReached()) continue;
                    $result->unsub[$user->id] = $api->friends->delete([
                        'user_id' => $user->id
                    ])->explore(true, 3, true);
                    $iterator->incSuccess();
                }
            }
        }

        return $result;
    }
}