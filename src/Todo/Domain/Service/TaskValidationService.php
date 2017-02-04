<?php
/**
 * File: TaskValidationService.php - todo
 * zzz - 04/02/17 19:12
 * PHP Version 7
 *
 * @category None
 * @package  Todo
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */

namespace Todo\Domain\Service;
use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Specification\TaskNameIsNotEmptySpecification;
use Todo\Domain\Specification\TaskNameIsUniqueSpecification;

/**
 * Class TaskValidationService
 *
 * @category None
 * @package  Todo\Domain\Service
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskValidationService
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * TaskValidationService constructor
     *
     * @param TaskRepositoryInterface $taskRepository
     *
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * ValidateName
     *
     * @param string $name Name
     * @param mixed  $id   ID
     *
     * @return bool
     * @throws TaskNameIsEmptyException
     * @throws TaskNameIsAlreadyExistedException
     */
    public function validateName(string $name, $id = null): bool
    {
        $emptyNameValidator = new TaskNameIsNotEmptySpecification();
        if (!$emptyNameValidator->isSatisfiedBy($name)) {
            throw new TaskNameIsEmptyException("Task's name should not be empty.");
        }

        $uniqueNameValidator = new TaskNameIsUniqueSpecification(
            $this->taskRepository
        );
        if (!$uniqueNameValidator->isSatisfiedBy($name, $id)) {
            throw new TaskNameIsAlreadyExistedException(
                "Task's name $name is already existed"
            );
        }

        return true;
    }

}