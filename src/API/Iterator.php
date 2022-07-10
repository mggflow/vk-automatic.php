<?php
/**
 * This class using for handle getting collection of elements from VK API.
 */

namespace MGGFLOW\VK\Automatic\API;

class Iterator
{
    /**
     * Offset in collection.
     * @var int
     */
    public int $offset = 0;
    /**
     * Step count per request.
     * @var int
     */
    public int $count = 10;
    /**
     * Volume of collection elements in requests.
     * @var int
     */
    public int $volume = 10;
    /**
     * Volume of selection of elements from requests.
     * @var int|mixed|null
     */
    public ?int $successVolume = null;
    /**
     * Amount elements in collection.
     * @var int|null
     */
    public ?int $amount = null;

    /**
     * Maximum of iterations.
     * @var int
     */
    public int $maxIteration = 100000;

    /**
     * If true in end of collection offset will not reset.
     * @var bool
     */
    public bool $staticOffset = false;
    /**
     * If true offset will be decreasing by success volume of elements.
     * @var bool
     */
    public bool $decOffsetBySuccess = false;

    protected int $inertCounter = 0;
    protected int $inertIncrement = 0;
    protected int $successCounter = 0;
    protected int $successIncrement = 0;

    protected int $iteration = 0;

    /**
     * Create object with initial values.
     * @param $offset
     * @param $count
     * @param $volume
     * @param $successVolume
     */
    public function __construct($offset, $count, $volume, $successVolume = 0)
    {
        $this->offset = $offset;
        $this->count = $count;
        $this->volume = $volume;
        $this->successVolume = $successVolume;
    }

    /**
     * Check available to continue iterations.
     * @return bool
     */
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

    /**
     * Increase common volume of elements.
     * @param $increment
     * @return void
     */
    public function incInert($increment = null)
    {
        $this->inertIncrement += $increment ?? $this->count;
    }

    /**
     * Increase success volume of elements.
     * @param $increment
     * @return void
     */
    public function incSuccess($increment = 1)
    {
        $this->successIncrement += $increment;
    }

    /**
     * Check if reaches success volume of elements.
     * @return bool
     */
    public function successVolumeReached(): bool
    {
        return isset($this->successVolume) and (($this->successCounter + $this->successIncrement) >= $this->successVolume);
    }

    /**
     * Create summary of iterations.
     * @return object
     */
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