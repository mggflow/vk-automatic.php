<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\GroupInvitation;
use PHPUnit\Framework\TestCase;

class GroupInvitationTest extends TestCase
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
            'groupId' => 162333442,
            'offset' => 0,
            'count' => 1,
            'volume' => 1,
            'successVolume' => 1,
        ];

        $task = new GroupInvitation($this->api, $taskData);
        $result = $task->execute();

        $this->assertNotEmpty($result->iteratorSummary->amount);
        $this->assertEquals(1, $result->iteratorSummary->count);
        $this->assertEquals(1, $result->iteratorSummary->volume);
        $this->assertEquals(1, $result->iteratorSummary->successVolume);
        $this->assertEquals(1, $result->iteratorSummary->iterations);
        $this->assertNotEmpty($result->inviteResult->invited);
    }
}