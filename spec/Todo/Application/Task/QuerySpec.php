<?php

namespace spec\Todo\Application\Task;

use Todo\Application\Task\Query;
use Todo\Domain\Factory\TaskFactory;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QuerySpec extends ObjectBehavior
{
    /** @var  TaskRepositoryInterface */
    protected $taskRepository;

    /** @var  TaskFactory */
    protected $taskFactory;

    protected $remainingTasks;
    protected $completedTasks;

    function it_is_initializable()
    {
        $this->shouldHaveType(Query::class);
    }

    function generate_task(string $name)
    {
        $task = new Task();
        $task->setName($name);

        return $task;
    }

    function let(TaskRepositoryInterface $taskRepository)
    {
        $this->remainingTasks = [
            $this->generate_task('Buying sugar'),
            $this->generate_task('Buying milk')
        ];
        $this->completedTasks = [
            $this->generate_task('Withdraw money'),
            $this->generate_task('Take car')
        ];

        $this->taskRepository = $taskRepository;
        $this->taskRepository
            ->findAllByStatus(Task::STATUS_REMAINING)
            ->willReturn($this->remainingTasks);
        $this->taskRepository
            ->find(1)
            ->willReturn($this->remainingTasks[0]);
        $this->taskRepository
            ->findAllByStatus(Task::STATUS_COMPLETED)
            ->willReturn($this->completedTasks);

        $this->beConstructedWith($this->taskRepository);
    }

    function it_can_get_remaining_tasks()
    {
        $this->getAllRemainingTasks()
            ->shouldBe($this->remainingTasks);
    }

    function it_can_get_completed_tasks()
    {
        $this->getAllCompletedTasks()
            ->shouldBe($this->completedTasks);
    }
    function it_can_get_task_by_id()
    {
        $this->getTaskById(1)->shouldReturn($this->remainingTasks[0]);
    }
}
