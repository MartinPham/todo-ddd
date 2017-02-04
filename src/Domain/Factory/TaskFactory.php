<?php

namespace Domain\Factory;

use Domain\Exception\TaskNameIsAlreadyExistedException;
use Domain\Exception\TaskNameIsEmptyException;
use Domain\Repository\TaskRepositoryInterface;
use Domain\Specification\TaskNameIsNotEmptySpecification;
use Domain\Specification\TaskNameIsUniqueSpecification;
use Domain\Task;

/**
 * Class TaskFactory
 *
 * @category None
 * @package  Domain\Factory
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskFactory
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * TaskFactory constructor
     *
     * @param TaskRepositoryInterface $taskRepository
     *
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }


    /**
     * Create Task From Name
     *
     * @param string $name Name
     *
     * @return Task
     * @throws TaskNameIsAlreadyExistedException
     * @throws TaskNameIsEmptyException
     */
    public function createFromName(string $name) : Task
    {
        $task = new Task();

        $emptyNameValidator = new TaskNameIsNotEmptySpecification();
        if (!$emptyNameValidator->isSatisfiedBy($name)) {
            throw new TaskNameIsEmptyException("Task's name should not be empty.");
        }

        $uniqueNameValidator = new TaskNameIsUniqueSpecification(
            $this->taskRepository
        );
        if (!$uniqueNameValidator->isSatisfiedBy($name)) {
            throw new TaskNameIsAlreadyExistedException(
                "Task's name $name is already existed"
            );
        }

        $task->setName($name);

        return $task;
    }


}
