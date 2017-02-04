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
        $this->taskRepository = $taskRepository;
        $this->taskFactory = new TaskFactory($this->taskRepository);
        $this->taskValidationService = new TaskValidationService($this->taskRepository);
    }


    /**
     * Add New Task
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
        try {
            $task = $this->taskFactory->createFromName($name);
        } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException $e) {
            throw $e;
        }

        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        return $task;
    }

    /**
     * Complete Task
     *
     * @param string $taskId Task ID
     *
     * @return Task
     * @throws TaskNotFoundException
     * @throws TaskCannotBeSavedException
     */
    public function completeTask($taskId) : Task
    {
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        $task->setStatus(Task::STATUS_COMPLETED);

        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        return $task;
    }

    /**
     * Redo Task
     *
     * @param string $taskId Task ID
     *
     * @return Task
     * @throws TaskNotFoundException
     * @throws TaskCannotBeSavedException
     */
    public function redoTask(string $taskId) : Task
    {
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        $task->setStatus(Task::STATUS_REMAINING);

        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        return $task;
    }

    /**
     * EditTask
     *
     * @param string $taskId ID
     * @param array  $data   Field
     *
     * @return Task
     * @throws TaskCannotBeSavedException
     * @throws TaskNotFoundException
     * @throws TaskNameIsAlreadyExistedException
     * @throws TaskNameIsEmptyException
     */
    public function editTask(string $taskId, array $data) : Task
    {
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        if (isset($data['name'])) {
            try {
                $this->taskValidationService->validateName($data['name'], $taskId);
            } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException $e) {
                throw $e;
            }


            $task->setName($data['name']);
        }



        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        try {
            $this->taskRepository->save($task);
        } catch (TaskCannotBeSavedException $e) {
            throw $e;
        }

        return $task;
    }


    /**
     * RemoveTask
     *
     * @param string $taskId ID
     *
     * @return void
     * @throws TaskCannotBeRemovedException
     * @throws TaskNotFoundException
     */
    public function removeTask($taskId)
    {
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        try {
            $this->taskRepository->remove($task);
        } catch (TaskCannotBeRemovedException $e) {
            throw $e;
        }
    }

    /**
     * CleanAllCompletedTasks
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
