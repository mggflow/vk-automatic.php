<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\OutgoingFriendRequestsCancelling;
use PHPUnit\Framework\TestCase;

class OutgoingFriendRequestsCancellingTest extends TestCase
{
    protected API $api;
    protected object $taskData;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);

        $this->taskData = (object)[
            'userIds' => [
                259712014
            ],
        ];
    }

    public function testExecute()
    {
        $task = new OutgoingFriendRequestsCancelling($this->api, $this->taskData);
        $result = $task->execute();

        $this->assertNotEmpty($result->cancelling->cancelled[$this->taskData->userIds[0]]);
    }
}