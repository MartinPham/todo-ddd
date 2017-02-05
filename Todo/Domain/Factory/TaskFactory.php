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
 * A factory to create Task object
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
        // Inject Repository
        $this->taskRepository = $taskRepository;

        // Init Validation service
        $this->taskValidationService = new TaskValidationService($this->taskRepository);
    }


    /**
     * Create Task object from name
     *
     * @param string $name Name
     *
     * @return Task
     * @throws TaskNameIsAlreadyExistedException
     * @throws TaskNameIsEmptyException
     */
    public function createFromName(string $name) : Task
    {
        // First we create a blank Task object
        $task = new Task();

        // Then we need to make sure the Task's name is not empty and not used
        // by another Task
        try {
            $this->taskValidationService->validateName($name);
        } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException $e) {
            throw $e;
        }

        // When we are sure the name is ok, just set the name
        $task->setName($name);

        // Return Task object
        return $task;
    }


}
