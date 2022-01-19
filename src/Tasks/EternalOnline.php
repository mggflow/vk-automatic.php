<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\MakeUserOnline;

class EternalOnline extends VKTask implements Task
{
    public function execute(): object
    {
        return (object)[
            'onlineResult' => MakeUserOnline::online($this->api)
        ];
    }
}