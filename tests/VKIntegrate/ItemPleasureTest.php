<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\ItemPleasure;
use PHPUnit\Framework\TestCase;

class ItemPleasureTest extends TestCase
{
    protected API $api;
    protected object $taskData;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);

        $this->taskData = (object)[
            'type' => 'photo',
            'ownerId' => 576936177,
            'itemId' => 457239018
        ];
    }

    public function testExecute()
    {
        $task = new ItemPleasure($this->api, $this->taskData);
        $result = $task->execute();
        $this->assertNotEmpty($result->like->likes);
    }

    protected function tearDown(): void
    {
        $this->api->likes->delete([
            'type' => $this->taskData->type,
            'owner_id' => $this->taskData->ownerId,
            'item_id' => $this->taskData->itemId
        ])->explore(true, 3, true);
    }
}