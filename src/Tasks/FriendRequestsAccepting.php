<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\AcceptIncomingFriendRequests;
use MGGFLOW\VK\Automatic\API\Iterator;

class FriendRequestsAccepting extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'iteratorSummary' => false,
            'acceptResult' => false,
        ];
        $iterator = $this->makeIterator();

        $result->acceptResult = AcceptIncomingFriendRequests::accept($this->api, $iterator);
        $result->iteratorSummary = $iterator->createSummary();

        return $result;
    }

    protected function makeIterator(): Iterator
    {
        $iterator = new Iterator(
            $this->taskData->offset,
            $this->taskData->count,
            $this->taskData->volume,
            $this->taskData->successVolume
        );
        $iterator->decOffsetBySuccess = true;
        $iterator->staticOffset = false;

        return $iterator;
    }
}