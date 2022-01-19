<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\AddFriendsByIds;
use MGGFLOW\VK\Automatic\API\GetSuggestionUsers;
use MGGFLOW\VK\Automatic\API\UserFriendRating;

class SuggestionFriendsAddition extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'suggestionsResult' => false,
            'ratedUsers' => false,
            'addition' => false,
        ];

        $suggestionsParams = [
            'count' => $this->taskData->volume,
            'fields' => UserFriendRating::NECESSARY_FIELDS,
        ];
        $result->suggestionsResult = GetSuggestionUsers::get(
            $this->api,
            $suggestionsParams
        );
        if (empty($result->suggestionsResult->suggestionUsers)) return $result;

        $result->ratedUsers = UserFriendRating::createRating($result->suggestionsResult->suggestionUsers);
        $futureFriendsIds = UserFriendRating::takeTopIds($result->ratedUsers->rating, $this->taskData->successVolume);
        $result->addition = AddFriendsByIds::add($this->api, $futureFriendsIds);

        return $result;
    }
}