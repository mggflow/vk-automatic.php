<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\OutgoingFriendRequestsRecording;
use PHPUnit\Framework\TestCase;

class OutgoingFriendRequestsRecordingTest extends TestCase
{
    protected API $api;
    protected object $taskData;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);

        $this->taskData = (object)[
            'offset' => 0,
            'count' => 1,
            'volume' => 1
        ];
    }

    public function testExecute()
    {
        $task = new OutgoingFriendRequestsRecording($this->api, $this->taskData);
        $result = $task->execute();

        if (empty($result->requstsResult->amount)) {
            $this->assertEmpty($result->requstsResult->users);
        } else {
            $this->assertNotEmpty($result->requstsResult->users);
        }
    }
}