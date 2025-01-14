<?php

namespace App\Services;

use App\Repositories\CommandRepository;

class CommandService
{
    protected $command;

    public function __construct(CommandRepository $command)
    {
        $this->command = $command;
    }

    public function getAllCommands(array $filters)
    {
        return $this->command->all($filters);
    }

    public function findCommandById(string $id)
    {
        return $this->command->find($id);
    }

    public function createCommand(array $data)
    {
        return $this->command->create($data);
    }

    // public function updateCommand(array $data, string $id)
    // {
    //     return $this->command->update($data, $id);
    // }

    // public function deleteCommand(string $id)
    // {
    //     return $this->command->delete($id);
    // }
}
