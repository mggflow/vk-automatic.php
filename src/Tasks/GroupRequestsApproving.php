<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\ApproveGroupMemberRequests;
use MGGFLOW\VK\Automatic\API\Iterator;

class GroupRequestsApproving extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'iteratorSummary' => false,
            'approveResult' => false,
        ];

        $iterator = $this->makeIterator();
        $result->approveResult = ApproveGroupMemberRequests::approve($this->api, $iterator, $this->taskData->groupId);
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