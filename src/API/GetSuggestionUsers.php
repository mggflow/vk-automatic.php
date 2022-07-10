<?php

namespace MGGFLOW\VK\Automatic\API;

use MGGFLOW\VK\API;

class GetSuggestionUsers
{
    /**
     * Get users from friend suggestions.
     * @param API $api
     * @param array $params
     * @return object
     */
    static function get(API $api, array $params)
    {
        $result = (object)[
            'suggestionsAmount' => false,
            'suggestionUsers' => false,
        ];

        $suggestionResp = $api->friends->getSuggestions($params)->explore(true, 3, true);
        if (empty($suggestionResp)) return $result;

        $result->suggestionsAmount = $suggestionResp->count;
        $result->suggestionUsers = $suggestionResp->items;

        return $result;
    }
}