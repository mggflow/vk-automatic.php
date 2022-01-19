<?php

namespace MGGFLOW\VK\Automatic\Interfaces;

interface Task
{
    public function execute(): object;
}