<?php
/**
 * File: TaskRepositoryInterface.php - todo
 * zzz - 03/02/17 13:19
 * PHP Version 7
 *
 * @category None
 * @package  Todo
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */

namespace Todo\Domain\Repository;

use Todo\Application\Task\Exception\TaskCannotBeRemovedException;
use Todo\Application\Task\Exception\TaskCannotBeSavedException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Task;

/**
 * Interface TaskRepositoryInterface
 *
 * @category None
 * @package  Todo\Domain\Repository
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
interface TaskRepositoryInterface
{
    /**
     * FindAll
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Find
     *
     * @param mixed $id Id
     *
     * @return Task
     * @throws TaskNotFoundException
     */
    public function find($id): Task;

    /**
     * FindAllByStatus
     *
     * @param mixed $status Status
     *
     * @return array
     */
    public function findAllByStatus($status): array;

    /**
     * FindByName
     *
     * @param string $name Name
     *
     * @return Task
     * @throws TaskNotFoundException
     */
    public function findByName(string $name): Task;

    /**
     * Save
     *
     * @param Task $task Task
     *
     * @return void
     * @throws TaskCannotBeSavedException
     */
    public function save(Task $task);

    /**
     * Remove
     *
     * @param Task $task Task
     *
     * @return void
     * @throws TaskCannotBeRemovedException
     */
    public function remove(Task $task);

    /**
     * RemoveByStatus
     *
     * @param mixed $status Status
     *
     * @return void
     * @throws TaskCannotBeRemovedException
     */
    public function removeByStatus($status);

}
