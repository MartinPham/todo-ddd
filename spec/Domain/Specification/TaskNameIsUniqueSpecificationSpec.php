<?php

namespace spec\Domain\Specification;

use Domain\Exception\TaskNotFoundException;
use Domain\Factory\TaskFactory;
use Domain\Repository\TaskRepositoryInterface;
use Domain\Specification\TaskNameIsUniqueSpecification;
use Domain\Task;
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
    }
}
