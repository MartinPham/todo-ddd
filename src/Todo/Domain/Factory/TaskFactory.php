<?php

namespace Todo\Domain\Factory;

use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Service\TaskValidationService;
use Todo\Domain\Specification\TaskNameIsNotEmptySpecification;
use Todo\Domain\Specification\TaskNameIsUniqueSpecification;
use Todo\Domain\Task;

/**
 * Class TaskFactory
 *
 * @category None
 * @package  Todo\Domain\Factory
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
     * TaskValidationService
     *
     * @var TaskValidationService
     */
    protected $taskValidationService;

    /**
     * TaskFactory constructor
     *
     * @param TaskRepositoryInterface $taskRepository
     *
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskValidationService = new TaskValidationService($this->taskRepository);
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

        try {
            $this->taskValidationService->validateName($name);
        } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException $e) {
            throw $e;
        }

        $task->setName($name);

        return $task;
    }


}
