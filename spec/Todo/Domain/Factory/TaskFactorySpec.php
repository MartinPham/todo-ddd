<?php

namespace spec\Todo\Domain\Factory;

use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Factory\TaskFactory;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaskFactorySpec extends ObjectBehavior
{
    protected $nameExists = 'Buying toys';
    protected $nameNotExists = 'Buying caffe';

    /** @var  TaskRepositoryInterface */
    protected $taskRepository;

    function it_is_initializable()
    {
        $this->shouldHaveType(TaskFactory::class);
    }

    function let(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;

        $taskExists = new Task();
        $taskExists->setName($this->nameExists);
        $this->taskRepository->findByName($this->nameExists)
            ->willReturn($taskExists);

        $this->taskRepository->findByName($this->nameNotExists)
            ->willThrow(TaskNotFoundException::class);

        $this->beConstructedWith($taskRepository);
    }

    function it_can_create_task_from_name()
    {
        $this->shouldThrow(TaskNameIsEmptyException::class)
            ->duringCreateFromName('');
        $this->shouldThrow(TaskNameIsAlreadyExistedException::class)
            ->duringCreateFromName($this->nameExists);

        /** @var Task $task */
        $task = $this->createFromName($this->nameNotExists);
        $task->shouldBeAnInstanceOf(Task::class);
        $task->getName()->shouldReturn($this->nameNotExists);
        $task->getStatus()->shouldReturn(Task::STATUS_REMAINING);
    }
}
