<?php

namespace spec\Todo\Application\Task;

use Todo\Application\Task\Command;
use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Factory\TaskFactory;
use Todo\Domain\Repository\TaskRepositoryInterface;
use Todo\Domain\Service\TaskValidationService;
use Todo\Domain\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    /** @var TaskRepositoryInterface */
    protected $taskRepository;

    /** @var TaskFactory */
    protected $taskFactory;

    /** @var  TaskValidationService */
    protected $taskValidationService;

    protected $tasks;


    /** @var Task */
    protected $newTask;


    /** @var Task */
    protected $remainingTask;

    /** @var Task */
    protected $completedTask;

    function it_is_initializable()
    {
        $this->shouldHaveType(Command::class);
    }

    function generate_task(string $name) : Task
    {
        $task = new Task();
        $task->setName($name);

        return $task;
    }

    function let(TaskRepositoryInterface $taskRepository)
    {
        $this->newTask = $this->generate_task('Buying salt');
        $this->newTask->setStatus(Task::STATUS_REMAINING);

        $this->remainingTask = $this->generate_task('Buying sugar');
        $this->remainingTask->setId(1);
        $this->remainingTask->setStatus(Task::STATUS_REMAINING);

        $this->completedTask = $this->generate_task('Buying milk');
        $this->completedTask->setId(2);
        $this->completedTask->setStatus(Task::STATUS_COMPLETED);

        $this->tasks = [
            $this->remainingTask,
            $this->completedTask
        ];


        $this->taskRepository = $taskRepository;



        $this->taskRepository->find($this->remainingTask->getId())
            ->willReturn($this->remainingTask);
        $this->taskRepository->find($this->completedTask->getId())
            ->willReturn($this->completedTask);
        $this->taskRepository->findByName($this->remainingTask->getName())
            ->willReturn($this->remainingTask);
        $this->taskRepository->findByName($this->completedTask->getName())
            ->willReturn($this->completedTask);
        $this->taskRepository->findByName('Buying salt')
            ->willThrow(TaskNotFoundException::class);
        $this->taskRepository->findByName('')
            ->willThrow(TaskNotFoundException::class);

        $this->taskRepository->remove($this->remainingTask)
            ->willReturn(true);

        $this->taskRepository->save($this->newTask)
            ->willReturn(true);

        $this->taskRepository->save($this->remainingTask)
            ->willReturn(true);
        $this->taskRepository->save($this->completedTask)
            ->willReturn(true);
        $this->taskRepository->removeByStatus(Task::STATUS_COMPLETED)
            ->willReturn(true);

        $this->beConstructedWith($this->taskRepository);
    }

    function it_can_add_new_task()
    {
        $this->addNewTask($this->newTask->getName());
    }

    function it_cannot_add_existed_task()
    {
        $this->shouldThrow(TaskNameIsAlreadyExistedException::class)
            ->duringAddNewTask($this->remainingTask->getName());
    }

    function it_cannot_add_empty_task()
    {
        $this->shouldThrow(TaskNameIsEmptyException::class)
            ->duringAddNewTask('');
    }



    function it_can_complete_task()
    {
        $task = $this->completeTask($this->remainingTask->getId());

        $task->getStatus()->shouldBe(Task::STATUS_COMPLETED);
    }

    function it_can_redo_task()
    {
        $task = $this->redoTask($this->completedTask->getId());

        $task->getStatus()->shouldBe(Task::STATUS_REMAINING);
    }

    function it_can_edit_task()
    {
        $this->taskRepository->findByName('New name')
            ->willThrow(TaskNotFoundException::class);

        $task = $this->editTask(
            $this->remainingTask->getId(),
            [
                'name' => 'New name',
                'status' => Task::STATUS_COMPLETED
            ]
        );

        $task->getName()->shouldBe('New name');
        $task->getStatus()->shouldBe(Task::STATUS_COMPLETED);


        $task = $this->editTask(
            $this->remainingTask->getId(),
            [
                'name' => $this->remainingTask->getName(),
                'status' => Task::STATUS_COMPLETED
            ]
        );

        $task->getName()->shouldBe($this->remainingTask->getName());
        $task->getStatus()->shouldBe(Task::STATUS_COMPLETED);

        $this->shouldThrow(TaskNameIsAlreadyExistedException::class)
            ->duringEditTask(
                $this->remainingTask->getId(),
                [
                    'name' => 'Buying milk',
                ]
            );
        $this->shouldThrow(TaskNameIsEmptyException::class)
            ->duringEditTask(
                $this->remainingTask->getId(),
                [
                    'name' => '',
                ]
            );
    }


    function it_can_remove_task()
    {
        $this->removeTask(
            $this->remainingTask->getId(),
            [
                'name' => 'New name',
                'status' => Task::STATUS_COMPLETED
            ]
        );
    }

    function it_can_clean_completed_task()
    {
        $this->cleanAllCompletedTasks();
    }

}
