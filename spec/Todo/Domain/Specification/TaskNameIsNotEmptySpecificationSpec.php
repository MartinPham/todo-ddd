<?php

namespace spec\Todo\Domain\Specification;

use Todo\Domain\Specification\TaskNameIsNotEmptySpecification;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaskNameIsNotEmptySpecificationSpec extends ObjectBehavior
{
    protected $valid = 'Buying pc';
    protected $invalid = '';

    function it_is_initializable()
    {
        $this->shouldHaveType(TaskNameIsNotEmptySpecification::class);
    }

    function it_is_satisfied()
    {
        $this->isSatisfiedBy($this->valid)->shouldBe(true);
        $this->isSatisfiedBy($this->invalid)->shouldBe(false);
    }
}
