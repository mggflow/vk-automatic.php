<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class LikeUserRandItem
{
    /**
     * Like random post or photo for user by id.
     * @param API $api
     * @param int $userId
     * @return object
     */
    static function like(API $api, int $userId): object
    {
        $result = (object)[
            'postsCount' => 0,
            'photosCount' => 0,
            'type' => false,
            'offset' => false,
            'itemId' => false,
            'like' => false
        ];

        $postsCount = $api->wall->get([
            'owner_id' => $userId,
            'count' => 0,
            'filter' => 'owner',
        ])->explore(true, 3, true);

        $photosCount = $api->photos->getAll([
            'owner_id' => $userId,
            'count' => 0,
        ])->explore(true, 3, true);

        $types = [];

        if (!empty($postsCount) and isset($postsCount->count) and $postsCount->count > 0) {
            $types[] = 'post';
            $result->postsCount = $postsCount->count;
        }

        if (!empty($photosCount) and isset($photosCount->count) and $photosCount->count > 0) {
            $types[] = 'photo';
            $result->photosCount = $photosCount->count;
        }

        if (empty($types)) return $result;

        $type = $types[array_rand($types)];
        $result->type = $type;

        switch ($type) {
            case 'photo':
                $offset = rand(0, $photosCount->count - 1);
                $photoResp = $api->photos->getAll([
                    'owner_id' => $userId,
                    'count' => 1,
                    'offset' => $offset,
                ])->explore(true, 3, true);
                if (empty($photoResp)) return $result;
                $photo = $photoResp->items[0];
                $itemId = $photo->id;
                break;
            default:
                $offset = rand(0, $postsCount->count - 1);
                $postResp = $api->wall->get([
                    'owner_id' => $userId,
                    'count' => 1,
                    'offset' => $offset,
                    'filter' => 'owner',
                ])->explore(true, 3, true);
                if (empty($postResp)) return $result;
                $post = $postResp->items[0];
                $itemId = $post->id;
                break;
        }
        $result->offset = $offset;
        $result->itemId = $itemId;

        $result->like = $api->likes->add([
            'type' => $type,
            'owner_id' => $userId,
            'item_id' => $itemId,
        ])->explore(true, 3, true);

        return $result;
    }
}