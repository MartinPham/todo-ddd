<?php

namespace Domain\Specification;

use Domain\Exception\TaskNotFoundException;
use Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskNameIsUniqueSpecification
 *
 * @category None
 * @package  Domain\Specification
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
        $this->taskRepository = $taskRepository;
    }

    /**
     * Is Satisfied By
     *
     * @param string $name Name
     *
     * @return bool
     */
    public function isSatisfiedBy(string $name)
    {
        try {
            $this->taskRepository->findByName($name);
        } catch (TaskNotFoundException $e) {
            return true;
        }

        return false;

    }



}
