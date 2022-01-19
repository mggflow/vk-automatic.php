<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\UserRandItemPleasure;
use PHPUnit\Framework\TestCase;

class UserRandItemPleasureTest extends TestCase
{
    protected API $api;
    protected object $taskData;
    protected object $result;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);

        $this->taskData = (object)[
            'userId' => 576936177,
        ];
    }

    public function testExecute()
    {
        $task = new UserRandItemPleasure($this->api, $this->taskData);
        $this->result = $task->execute();
        $this->assertNotEmpty($this->result->like->like->likes);
    }

    protected function tearDown(): void
    {
        $this->api->likes->delete([
            'type' => $this->result->like->type,
            'owner_id' => $this->taskData->userId,
            'item_id' => $this->result->like->itemId
        ])->explore(true, 3, true);
    }
}