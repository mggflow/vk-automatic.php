<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class GetRand
{
    /**
     * Get rand objects.
     * @param API $api
     * @param string $object
     * @param string $action
     * @param int $count
     * @param array $options
     * @return object
     */
    public static function get(API $api, string $object, string $action, int $count = 1, array $options = []): object
    {
        $result = (object)[
            'amount' => false,
            'offset' => false,
            'items' => false,
        ];

        $countParams = array_merge($options, ['count' => 0]);
        $countResponse = $api->{$object}->{$action}($countParams)->explore(true, 3, true);

        if (empty($countResponse)) return $result;
        $result->amount = $countResponse->count;

        if ($count >= $result->amount) {
            $offset = 0;
        } else {
            $offset = rand(0, $result->amount - $count);
        }
        $result->offset = $offset;

        $itemsParams = array_merge($options, ['offset' => $offset, 'count' => $count]);
        $itemsResponse = $api->{$object}->{$action}($itemsParams)->explore(true, 3, true);

        if (empty($itemsResponse)) return $result;
        $result->items = $itemsResponse->items;

        return $result;
    }
}