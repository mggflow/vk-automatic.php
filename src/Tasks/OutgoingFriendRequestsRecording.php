<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\GetOutgoingFriendRequests;
use MGGFLOW\VK\Automatic\API\Iterator;

class OutgoingFriendRequestsRecording extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'iteratorSummary' => false,
            'requestsResult' => false,
        ];

        $iterator = $this->makeIterator();
        $result->requestsResult = GetOutgoingFriendRequests::get($this->api, $iterator);
        $result->iteratorSummary = $iterator->createSummary();

        return $result;
    }

    protected function makeIterator(): Iterator
    {
        $iterator = new Iterator(
            $this->taskData->offset,
            $this->taskData->count,
            $this->taskData->volume
        );
        $iterator->decOffsetBySuccess = false;
        $iterator->staticOffset = false;

        return $iterator;
    }
}