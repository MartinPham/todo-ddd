<?php

namespace spec\Todo\Domain\Specification;

use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Factory\TaskFactory;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Specification\TaskNameIsUniqueSpecification;
use Todo\Domain\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaskNameIsUniqueSpecificationSpec extends ObjectBehavior
{
    protected $taskRepository;

    protected $taskNameExists = 'Buying milk';
    protected $taskNameNonExists = 'Buying sugar';
    protected $task;

    function it_is_initializable()
    {
        $this->shouldHaveType(TaskNameIsUniqueSpecification::class);
    }

    function let(TaskRepositoryInterface $taskRepository)
    {
        $this->task = new Task;
        $this->task->setId(1);
        $this->task->setName($this->taskNameExists);

        $this->taskRepository = $taskRepository;
        $this->taskRepository
            ->findByName($this->taskNameExists)
            ->willReturn($this->task);
        $this->taskRepository
            ->findByName($this->taskNameNonExists)
            ->willThrow(TaskNotFoundException::class);

        $this->beConstructedWith($this->taskRepository);
    }

    function it_is_satisfied()
    {
        $this->isSatisfiedBy($this->taskNameExists)->shouldBe(false);
        $this->isSatisfiedBy($this->taskNameNonExists)->shouldBe(true);

        $this->isSatisfiedBy($this->taskNameExists, 1)->shouldBe(true);
    }
}
