<?php

namespace MGGFLOW\VK\Automatic\Interfaces;

interface Task
{
    /**
     * Execute task and get result.
     * @return object
     */
    public function execute(): object;
}