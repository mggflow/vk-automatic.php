<?php

namespace MGGFLOW\VK\Automatic\API;

class Iterator
{
    public int $offset = 0;
    public int $count = 10;
    public int $volume = 10;
    public ?int $successVolume = null;
    public ?int $amount = null;

    public int $maxIteration = 100000;

    public bool $staticOffset = false;
    public bool $decOffsetBySuccess = false;

    protected int $inertCounter = 0;
    protected int $inertIncrement = 0;
    protected int $successCounter = 0;
    protected int $successIncrement = 0;

    protected int $iteration = 0;

    public function __construct($offset, $count, $volume, $successVolume = 0)
    {
        $this->offset = $offset;
        $this->count = $count;
        $this->volume = $volume;
        $this->successVolume = $successVolume;
    }

    public function continue(): bool
    {
        $this->handlePresetAmount();

        $this->handleCurrentIteration();

        if ($this->maxIterationReached()) return false;
        if ($this->offsetReachedAmount()) return false;
        if ($this->iterationHappened()) {
            if ($this->volumeReached()) return false;
            if ($this->successVolumeReached()) return false;
        }

        $this->incIteration();

        return true;
    }

    public function incInert($increment = null)
    {
        $this->inertIncrement += $increment ?? $this->count;
    }

    public function incSuccess($increment = 1)
    {
        $this->successIncrement += $increment;
    }

    public function successVolumeReached(): bool
    {
        return isset($this->successVolume) and (($this->successCounter + $this->successIncrement) >= $this->successVolume);
    }

    public function createSummary(): object
    {
        return (object)[
            'offset' => $this->offset,
            'count' => $this->count,
            'volume' => $this->volume,
            'successVolume' => $this->successVolume,
            'amount' => $this->amount,
            'iterations' => $this->iteration,
        ];
    }

    protected function handleCurrentIteration()
    {
        $this->inertCounter += $this->inertIncrement;
        $this->successCounter += $this->successIncrement;
        $this->offset += $this->inertIncrement;
        if ($this->decOffsetBySuccess) {
            $this->offset -= $this->successIncrement;
        }

        $this->inertIncrement = 0;
        $this->successIncrement = 0;
    }

    protected function handlePresetAmount()
    {
        if ($this->offsetReachedAmount() and $this->availableOffsetReset()) {
            $this->resetOffset();
        }
    }

    protected function volumeReached(): bool
    {
        return $this->inertCounter >= $this->volume;
    }

    protected function maxIterationReached(): bool
    {
        return $this->iteration >= $this->maxIteration;
    }

    protected function offsetReachedAmount(): bool
    {
        return isset($this->amount) and $this->offset >= $this->amount;
    }

    protected function availableOffsetReset(): bool
    {
        return !$this->staticOffset and $this->iteration === 0;
    }

    protected function resetOffset()
    {
        $this->offset = 0;
    }

    protected function iterationHappened(): bool
    {
        return $this->iteration > 0;
    }

    protected function incIteration()
    {
        $this->iteration++;
    }
}