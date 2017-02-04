<?php

namespace Todo\Cli\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Todo\Application\Task\Command;
use Todo\Application\Task\Exception\TaskCannotBeSavedException;
use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;

/**
 * Class TaskCreateCommand
 *
 * @category None
 * @package  Todo\Cli\CliBundle\Command
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskCreateCommand extends ContainerAwareCommand
{
    /**
     * TaskCommand
     *
     * @var Command
     */
    protected $taskCommand;

    /**
     * TaskListCommand constructor
     *
     * @param Command $taskCommand Task Command
     *
     * @throws LogicException
     */
    public function __construct(Command $taskCommand)
    {
        $this->taskCommand = $taskCommand;

        try {
            parent::__construct();
        } catch (LogicException $e) {
            throw $e;
        }
    }

    /**
     * Configure
     *
     * @return void
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        try {
            $this
                ->setName('task:create')
                ->setDescription('...')
                ->addArgument('name', InputArgument::REQUIRED, 'Task name');
        } catch (InvalidArgumentException $e) {
            // no catch exception
        }
    }

    /**
     * Execute
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws TaskNameIsEmptyException
     * @throws TaskNameIsAlreadyExistedException
     * @throws TaskCannotBeSavedException
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $name = $input->getArgument('name');
        } catch (InvalidArgumentException $e) {
            throw $e;
        }

        try {
            $task = $this->taskCommand->addNewTask($name);

            $output->writeln('Task created with #' . $task->getId());
        } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
            throw $e;
        }

    }

}
