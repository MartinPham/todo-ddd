<?php

namespace spec\Todo\Domain\Service;

use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Service\TaskValidationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Todo\Domain\Task;

class TaskValidationServiceSpec extends ObjectBehavior
{
    protected $taskRepository;

    /** @var Task */
    protected $remainingTask;

    /** @var Task */
    protected $completedTask;

    function it_is_initializable()
    {
        $this->shouldHaveType(TaskValidationService::class);
    }
    function generate_task(string $name) : Task
    {
        $task = new Task();
        $task->setName($name);

        return $task;
    }

    function let(TaskRepositoryInterface $taskRepository)
    {

        $this->remainingTask = $this->generate_task('Buying sugar');
        $this->remainingTask->setId(1);
        $this->remainingTask->setStatus(Task::STATUS_REMAINING);



        $this->taskRepository = $taskRepository;

        $this->taskRepository->findByName($this->remainingTask->getName())
            ->willReturn($this->remainingTask);

        $this->taskRepository->findByName('Buying salt')
            ->willThrow(TaskNotFoundException::class);
        $this->taskRepository->findByName('')
            ->willThrow(TaskNotFoundException::class);





        $this->beConstructedWith($this->taskRepository);
    }

    function it_can_validate()
    {
        $this->shouldThrow(TaskNameIsEmptyException::class)->duringValidateName('');
        $this->shouldThrow(TaskNameIsAlreadyExistedException::class)->duringValidateName($this->remainingTask->getName());

        $this->validateName($this->remainingTask->getName(), $this->remainingTask->getId())->shouldReturn(true);
        $this->validateName('Buying salt')->shouldReturn(true);
    }
}
