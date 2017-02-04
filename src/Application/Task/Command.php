<?php

namespace Application\Task;
use Application\Task\Exception\TaskCannotBeRemovedException;
use Application\Task\Exception\TaskCannotBeSavedException;
use Domain\Exception\TaskNameIsAlreadyExistedException;
use Domain\Exception\TaskNameIsEmptyException;
use Domain\Exception\TaskNotFoundException;
use Domain\Factory\TaskFactory;
use Domain\Repository\TaskRepositoryInterface;
use Domain\Task;

/**
 * Class Command
 *
 * @category None
 * @package  Application\Task
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
     * TaskFactory
     *
     * @var TaskFactory
     */
    protected $taskFactory;

    /**
     * Command constructor
     *
     * @param TaskRepositoryInterface $taskRepository Task Repository
     * @param TaskFactory             $taskFactory    Task Factory
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TaskFactory $taskFactory
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskFactory = $taskFactory;
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

        $result = $this->taskRepository->save($task);

        if (!$result) {
            throw new TaskCannotBeSavedException(
                'Cannot save task into repository.'
            );
        }

        return $task;
    }

    /**
     * Complete Task
     *
     * @param Task $task Task
     *
     * @return Task
     * @throws TaskCannotBeSavedException
     */
    public function completeTask(Task $task) : Task
    {
        $task->setStatus(Task::STATUS_COMPLETED);

        $result = $this->taskRepository->save($task);

        if (!$result) {
            throw new TaskCannotBeSavedException(
                'Cannot save task into repository.'
            );
        }

        return $task;
    }

    /**
     * Redo Task
     *
     * @param Task $task Task
     *
     * @return Task
     * @throws TaskCannotBeSavedException
     */
    public function redoTask(Task $task) : Task
    {
        $task->setStatus(Task::STATUS_REMAINING);

        $result = $this->taskRepository->save($task);

        if (!$result) {
            throw new TaskCannotBeSavedException(
                'Cannot save task into repository.'
            );
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
     */
    public function editTask(string $taskId, array $data) : Task
    {
        try {
            $task = $this->taskRepository->find($taskId);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        $task->setName($data['name']);
        $task->setStatus($data['status']);

        $result = $this->taskRepository->save($task);

        if (!$result) {
            throw new TaskCannotBeSavedException(
                'Cannot save task into repository.'
            );
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

        $result = $this->taskRepository->remove($task);

        if (!$result) {
            throw new TaskCannotBeRemovedException(
                'Cannot remove task(s) from repository.'
            );
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
        $result = $this->taskRepository->removeByStatus(Task::STATUS_COMPLETED);

        if (!$result) {
            throw new TaskCannotBeRemovedException(
                'Cannot remove task(s) from repository.'
            );
        }
    }
}
