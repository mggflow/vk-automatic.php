<?php

namespace MGGFLOW\VK\Automatic\Tasks;

use MGGFLOW\VK\Automatic\Interfaces\Task;
use MGGFLOW\VK\Automatic\API\Iterator;
use MGGFLOW\VK\Automatic\API\UnsubDeadPages;

class DeadFriendsCleaning extends VKTask implements Task
{
    public function execute(): object
    {
        $result = (object)[
            'iteratorSummary' => false,
            'unsubResult' => false,
        ];

        $iterator = $this->makeIterator();
        $result->unsubResult = UnsubDeadPages::unsub($this->api, $iterator);
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