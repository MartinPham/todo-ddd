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

use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Task;

/**
 * Interface TaskRepositoryInterface
 *
 * @category None
 * @package  Domain\Repository
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
     * @return bool
     */
    public function save(Task $task): bool;

    /**
     * Remove
     *
     * @param Task $task Task
     *
     * @return bool
     */
    public function remove(Task $task): bool;

    /**
     * RemoveByStatus
     *
     * @param mixed $status Status
     *
     * @return bool
     */
    public function removeByStatus($status) : bool;

}
