<?php
/**
 * File: TaskRepository.php - ltodo
 * zzz - 04/02/17 23:37
 * PHP Version 7
 *
 * @category None
 * @package  Ltodo
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */

namespace Todo\Infrastructure\Persistence\Eloquent\Repository;

use Illuminate\Support\Facades\DB;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Task;

/**
 * Class TaskRepository
 *
 * @category None
 * @package  Todo\Infrastructure\Persistence\Eloquent\Repository
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskRepository implements TaskRepositoryInterface
{
    private function hydrateTask(\stdClass $object) {
        $task = new Task();
        $task->setId($object->id);
        $task->setName($object->name);
        $task->setStatus($object->status);
        $task->setCreatedAt(new \DateTime($object->created_at));
        $task->setUpdatedAt(new \DateTime($object->updated_at));

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $return = [];
        $results = DB::select('SELECT * FROM tasks');

        foreach($results as $taskObject) {
            $return[] = $this->hydrateTask($taskObject);
        }


        return $return;
    }/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

    /**
     * @inheritDoc
     */
    public function find($id): Task
    {
        $results = DB::table('tasks')->where('id', $id)->get();
        if (count($results) === 0) {
            throw new TaskNotFoundException("Cannot find task with id $id");
        }

        return $this->hydrateTask($results[0]);
    }

    /**
     * @inheritDoc
     */
    public function findAllByStatus($status): array
    {
        $return = [];
        $results = DB::table('tasks')->where('status', $status)->get();

        foreach($results as $taskObject) {
            $return[] = $this->hydrateTask($taskObject);
        }


        return $return;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name): Task
    {
        $results = DB::table('tasks')->where('name', $name)->get();

        if (count($results) === 0) {
            throw new TaskNotFoundException("Cannot find task with name $name");
        }

        return $this->hydrateTask($results[0]);
    }

    /**
     * @inheritDoc
     */
    public function save(Task $task): bool
    {
        if ($task->getCreatedAt() === null) {
            $task->setCreatedAt(new \DateTime());
        }
        $task->setUpdatedAt(new \DateTime());

        if ($task->getId() === null) {
            DB::table('tasks')->insert([
                'name' => $task->getName(),
                'status' => $task->getStatus(),
                'created_at' => $task->getCreatedAt(),
                'updated_at' => $task->getUpdatedAt(),
            ]);
            $task->setId(DB::getPdo()->lastInsertId());
        } else {
            DB::table('tasks')->where('id', $task->getId())->update([
                'name' => $task->getName(),
                'status' => $task->getStatus(),
                'created_at' => $task->getCreatedAt(),
                'updated_at' => $task->getUpdatedAt(),
            ]);
        }




        return true;
    }

    /**
     * @inheritDoc
     */
    public function remove(Task $task): bool
    {
        return DB::table('tasks')->where('id', $task->getId())->delete();
    }

    /**
     * @inheritDoc
     */
    public function removeByStatus($status): bool
    {
        return DB::table('tasks')->where('status', 'status')->delete();
    }

}
