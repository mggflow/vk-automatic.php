<?php

namespace Tests\VKIntegrate;

use MGGFLOW\VK\API;
use MGGFLOW\VK\Automatic\Tasks\EternalOnline;
use PHPUnit\Framework\TestCase;

class EternalOnlineTest extends TestCase
{
    protected API $api;

    protected function setUp(): void
    {
        $token = include './testAccessToken.php';
        $this->api = new API($token, 5.131);
    }

    public function testExecute()
    {
        $task = new EternalOnline($this->api);
        $this->assertEquals(1, $task->execute()->onlineResult);
    }
}