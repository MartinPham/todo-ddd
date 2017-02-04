<?php

namespace spec\Todo\Domain\Service;

use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Service\TaskValidationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaskValidationServiceSpec extends ObjectBehavior
{
    protected $taskRepository;

    function it_is_initializable()
    {
        $this->shouldHaveType(TaskValidationService::class);
    }

    function let(TaskRepositoryInterface $taskRepository)
    {
        $this->beConstructedWith($taskRepository);
    }
}
