<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\InviteFriendsToGroup;
use MGGFLOW\VK\Automatic\API\Iterator;

class GroupInvitation extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'iteratorSummary' => false,
            'inviteResult' => false,
        ];

        $iterator = $this->makeIterator();
        $result->inviteResult = InviteFriendsToGroup::invite($this->api, $iterator, $this->taskData->groupId);
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
        $iterator->decOffsetBySuccess = false;
        $iterator->staticOffset = false;

        return $iterator;
    }
}