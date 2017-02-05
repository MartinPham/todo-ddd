<?php

namespace Todo\Domain\Specification;

use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskNameIsUniqueSpecification
 *
 * A specification describes that Task's name should be unique
 *
 * @category None
 * @package  Todo\Domain\Specification
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskNameIsUniqueSpecification
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * TaskNameIsUniqueSpecification constructor
     *
     * @param TaskRepositoryInterface $taskRepository Task Repository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        // Inject repository, since we need to check tasks on repository
        $this->taskRepository = $taskRepository;
    }

    /**
     * Check the Task's name is already used or not
     * If it's already used by a Task which is the same Task we are trying to check
     * then it will be fine
     *
     * @param string $name Name
     * @param mixed $id ID
     *
     * @return bool
     */
    public function isSatisfiedBy(string $name, $id = null)
    {
        // Find the Task with given name
        try {
            $task = $this->taskRepository->findByName($name);
        } catch (TaskNotFoundException $e) {
            return true;
        }

        // there is task with same name
        // but if this task's id === given id
        // then it's OK

        /** @noinspection TypeUnsafeComparisonInspection */
        return $task->getId() == $id;
    }
}
