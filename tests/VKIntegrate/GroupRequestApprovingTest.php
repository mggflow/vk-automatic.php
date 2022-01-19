<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\GroupRequestsApproving;
use PHPUnit\Framework\TestCase;

class GroupRequestApprovingTest extends TestCase
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
            'groupId' => 162962973,
            'offset' => 0,
            'count' => 1,
            'volume' => 1,
            'successVolume' => 1,
        ];

        $task = new GroupRequestsApproving($this->api, $taskData);
        $this->result = $task->execute();

        $this->assertNotEmpty($this->result->iteratorSummary->amount);
        $this->assertEquals(1, $this->result->iteratorSummary->count);
        $this->assertEquals(1, $this->result->iteratorSummary->volume);
        $this->assertEquals(1, $this->result->iteratorSummary->successVolume);
        $this->assertEquals(1, $this->result->iteratorSummary->iterations);

        $this->assertNotEmpty($this->result->approveResult->approved);
    }
}