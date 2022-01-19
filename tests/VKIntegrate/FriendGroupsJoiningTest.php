<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\FriendGroupsJoining;
use PHPUnit\Framework\TestCase;

class FriendGroupsJoiningTest extends TestCase
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
        $task = new FriendGroupsJoining($this->api);
        $this->result = $task->execute();

        $this->assertNotEmpty($this->result->joinResult->friendsAmount);
        $this->assertNotEmpty($this->result->joinResult->friends);
        $this->assertNotEmpty($this->result->joinResult->groupsAmount);
        $this->assertEquals(1, $this->result->joinResult->joined);
    }

    protected function tearDown(): void
    {
        if (!empty($this->result->joinResult->groups)) {
            $this->api->groups->leave([
                'group_id' => $this->result->joinResult->groups[0]
            ])->explore(true, 3, true);
        }
    }
}