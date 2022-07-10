<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class LikeItem
{
    /**
     * Like some object.
     * @param API $api
     * @param string $type
     * @param int $ownerId
     * @param int $itemId
     * @return mixed
     */
    public static function like(API $api, string $type, int $ownerId, int $itemId)
    {
        return $api->likes->add([
            'type' => $type,
            'owner_id' => $ownerId,
            'item_id' => $itemId,
        ])->explore(true, 3, true);
    }
}