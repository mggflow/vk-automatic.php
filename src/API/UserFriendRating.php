<?php

namespace MGGFLOW\VK\Automatic\API;

class UserFriendRating
{
    const NECESSARY_FIELDS = 'is_closed,blacklisted,can_post,can_see_all_posts,can_see_audio,can_send_friend_request,can_write_private_message,common_count,connections,followers_count,friend_status,last_seen,has_photo';

    public static function takeTopIds(array $rating, int $count = 1): array
    {
        $partToAdd = array_slice($rating, 0, $count, true);
        return array_keys($partToAdd);
    }

    public static function createRating($users): object
    {
        $result = (object)[
            'filteredUsers' => false,
            'rating' => false,
        ];
        $filteredUsers = array_filter($users, function ($user) {
            return self::userAvailableToFriend($user);
        });
        $result->filteredUsers = $filteredUsers;

        $ratedUsers = [];
        foreach ($filteredUsers as $filteredUser) {
            $ratedUsers[$filteredUser->id] = self::rateUser($filteredUser);
        }

        arsort($ratedUsers);
        $result->rating = $ratedUsers;

        return $result;
    }

    public static function userAvailableToFriend(object $user): bool
    {
        if (isset($user->deactivated)) return false;
        if (isset($user->blacklisted) and $user->blacklisted) return false;
        if (isset($user->friend_status) and !self::nonFriendStatus($user)) return false;

        return true;
    }

    public static function rateUser(object $user): float
    {
        $rating = 0;

        if (isset($user->can_send_friend_request) and $user->can_send_friend_request) $rating += 3;

        if (isset($user->can_access_closed) and $user->can_access_closed) $rating += 1;
        if (isset($user->is_closed) and !$user->is_closed) $rating += 1;
        if (isset($user->has_photo) and $user->has_photo) $rating += 1;
        if (isset($user->can_post) and $user->can_post) $rating += 1.5;
        if (isset($user->can_see_all_posts) and $user->can_see_all_posts) $rating += 1;
        if (isset($user->can_see_audio) and $user->can_see_audio) $rating += 1;
        if (isset($user->can_write_private_message) and $user->can_write_private_message) $rating += 1;

        if (isset($user->followers_count)) {
            $rating += 1 / ($user->followers_count + 1);
        }

        if (isset($user->common_count)) {
            $rating += $user->common_count / 17;
        }

        if (isset($user->last_seen)) {
            $nowDif = abs(time() - $user->last_seen->time);
            if ($nowDif < 3 * 60 * 60) {
                $rating += 1;
            } elseif ($nowDif < 7 * 60 * 60) {
                $rating += 0.7;
            } elseif ($nowDif < 12 * 60 * 60) {
                $rating += 0.6;
            } elseif ($nowDif < 24 * 60 * 60) {
                $rating += 0.5;
            } elseif ($nowDif < 3 * 24 * 60 * 60) {
                $rating += 0.3;
            } elseif ($nowDif < 7 * 24 * 60 * 60) {
                $rating += 0.1;
            } else {
                $rating -= 1;
            }
        }

        if (!empty($user->skype)) $rating += 0.5;
        if (!empty($user->facebook)) $rating += 0.5;
        if (!empty($user->twitter)) $rating += 0.5;
        if (!empty($user->livejournal)) $rating += 0.5;
        if (!empty($user->instagram)) $rating += 0.5;

        return $rating;
    }

    protected static function nonFriendStatus($user): bool
    {
        return $user->friend_status == 0;
    }

}