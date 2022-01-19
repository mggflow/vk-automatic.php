<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\GroupFriendsAddition;
use PHPUnit\Framework\TestCase;

class GroupFriendsAdditionTest extends TestCase
{
    protected API $api;
    protected ?object $result;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);
    }

    public function testExecute()
    {
        $taskData = (object)[
            'groupId' => 160740184,
            'volume' => 500,
            'successVolume' => 1,
        ];

        $task = new GroupFriendsAddition($this->api, $taskData);
        $this->result = $task->execute();

        $this->assertNotEmpty($this->result->groupUsersResult->members);
        $this->assertNotEmpty($this->result->ratedUsers->rating);

        if (!empty($this->result->ratedUsers->rating)) {
            $topUserId = array_key_first($this->result->ratedUsers->rating);
            $addedUserId = array_key_first($this->result->addition->addedFriends);
            $this->assertEquals($topUserId, $addedUserId);
        }
    }

    protected function tearDown(): void
    {
        if (!empty($this->result->addition->addedFriends)) {
            $id = array_key_first($this->result->addition->addedFriends);

            $this->api->friends->delete([
                'user_id' => $id,
            ])->explore(true, 3, true);
        }
    }
}