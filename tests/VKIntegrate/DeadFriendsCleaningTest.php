<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\DeadFriendsCleaning;
use PHPUnit\Framework\TestCase;

class DeadFriendsCleaningTest extends TestCase
{
    protected API $api;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);
    }

    public function testExecute()
    {
        $taskData = (object)[
            'offset' => 0,
            'count' => 1,
            'volume' => 1,
            'successVolume' => 1,
        ];

        $task = new DeadFriendsCleaning($this->api, $taskData);
        $result = $task->execute();

        $this->assertNotEmpty($result->iteratorSummary->amount);
        $this->assertEquals(1, $result->iteratorSummary->count);
        $this->assertEquals(1, $result->iteratorSummary->volume);
        $this->assertEquals(1, $result->iteratorSummary->successVolume);
        $this->assertEquals(1, $result->iteratorSummary->iterations);
        $this->assertEquals(1, $result->unsubResult->checkedCounter);
    }
}