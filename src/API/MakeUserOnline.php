<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class MakeUserOnline
{
    /**
     * Set user as online.
     * @param API $api
     * @return mixed
     */
    public static function online(API $api)
    {
        return $api->account->setOnline()->explore(true, 3, true);
    }
}