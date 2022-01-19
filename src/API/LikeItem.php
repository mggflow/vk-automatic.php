<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class LikeItem
{
    public static function like(API $api, string $type, int $ownerId, int $itemId)
    {
        return $api->likes->add([
            'type' => $type,
            'owner_id' => $ownerId,
            'item_id' => $itemId,
        ])->explore(true, 3, true);
    }
}