<?php

namespace Todo\Application\Task;
use Todo\Application\Task\Exception\TaskCannotBeRemovedException;
use Todo\Application\Task\Exception\TaskCannotBeSavedException;
use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Factory\TaskFactory;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Service\TaskValidationService;
use Todo\Domain\Specification\TaskNameIsNotEmptySpecification;
use Todo\Domain\Specification\TaskNameIsUniqueSpecification;
use Todo\Domain\Task;

/**
 * Class Command
 *
 * Executes Task's commands to update/remove Task object from repository
 *
 * @category None
 * @package  Todo\Application\Task
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class Command
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
     * TaskFactory
     *
     * @var TaskFactory
     */
    protected $taskFactory;

    /**
     * Command constructor
     *
     * @param TaskRepositoryInterface $taskRepository Task Repository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository
    ) {
        // Inject Task repository
        $this->taskRepository = $taskRepository;

        // Init Task factory
        $this->taskFactory = new TaskFactory($this->taskRepository);

        // Init Task validation service
        $this->taskValidationService = new TaskValidationService(
            $this->taskRepository
        );
    }


    /**
     * Add new Task into repository
     *
     * @param string $name Name
     *
     * @return Task
     * @throws TaskCannotBeSavedException
     * @throws TaskNameIsAlreadyExistedException
     * @throws TaskNameIsEmptyException
     */
    public function addNewTask(string $name) : Task
    {
        // Try to init Task object from given name
        // Trigger exceptions when Task's name is empty or already existed
        try {
            $task = $this->taskFactory->createFromName($name);
        } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException $e) {
            throw $e;
        }

        // Persist Task object into repository
        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        // Return Task object
        return $task;
    }

    /**
     * Update Task's status to completed
     *
     * @param string $taskId Task ID
     *
     * @return Task
     * @throws TaskNotFoundException
     * @throws TaskCannotBeSavedException
     */
    public function completeTask($taskId) : Task
    {
        // Try to find Task object from repository, from given ID
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Update Task's status to completed
        $task->setStatus(Task::STATUS_COMPLETED);

        // Try to save Task object into repository
        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        // Return Task object
        return $task;
    }

    /**
     * Update Task's status to remaining
     *
     * @param string $taskId Task ID
     *
     * @return Task
     * @throws TaskNotFoundException
     * @throws TaskCannotBeSavedException
     */
    public function redoTask(string $taskId) : Task
    {
        // Try to find Task object from repository
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Set Task's status to remaining
        $task->setStatus(Task::STATUS_REMAINING);

        // Then try to save Task object into repository
        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        // Return Task object
        return $task;
    }

    /**
     * Update Task's data
     *
     * @param string $taskId Task ID
     * @param array  $data   Fields (name, status)
     *
     * @return Task
     * @throws TaskCannotBeSavedException
     * @throws TaskNotFoundException
     * @throws TaskNameIsAlreadyExistedException
     * @throws TaskNameIsEmptyException
     */
    public function editTask(string $taskId, array $data) : Task
    {
        // Try to find Task object from repository
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Update Task's name
        if (isset($data['name'])) {
            // Wait, we want to validate the name before set new Task's name
            try {
                $this->taskValidationService->validateName($data['name'], $taskId);
            } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException $e) {
                throw $e;
            }

            // It's ok, we set new name
            $task->setName($data['name']);
        }


        // Update Task's status
        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        // Save Task object into repository
        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        // Return Task object
        return $task;
    }


    /**
     * Remove Task out of repository
     *
     * @param string $taskId Task ID
     *
     * @return void
     * @throws TaskCannotBeRemovedException
     * @throws TaskNotFoundException
     */
    public function removeTask($taskId)
    {
        // First we need to find the Task object
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Now we try to remove this Task out of repository
        try {
            $this->taskRepository->remove($task);
        } catch (TaskCannotBeRemovedException $e) {
            throw $e;
        }
    }

    /**
     * Remove all completed tasks from repository
     *
     * @return void
     * @throws TaskCannotBeRemovedException
     */
    public function cleanAllCompletedTasks()
    {
        try {
            $this->taskRepository->removeByStatus(Task::STATUS_COMPLETED);
        } catch (TaskCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
