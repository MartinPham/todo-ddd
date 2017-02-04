<?php

namespace Todo\Cli\CliBundle\Command;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Todo\Application\Task\Query;
use Todo\Domain\Task;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskListCommand
 *
 * @category None
 * @package  Todo\Cli\CliBundle\Command
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskListCommand extends ContainerAwareCommand
{
    /**
     * TaskQuery
     *
     * @var Query
     */
    protected $taskQuery;

    /**
     * TaskListCommand constructor
     *
     * @param Query $taskQuery Task Query
     *
     * @throws LogicException
     */
    public function __construct(Query $taskQuery)
    {
        $this->taskQuery = $taskQuery;

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
     */
    protected function configure()
    {
        try {
            $this
                ->setName('task:list')
                ->setDescription('...');
        } catch (InvalidArgumentException $e) {
            // no catch exception
        }
    }

    /**
     * Execute
     *
     * @param InputInterface  $input  Input
     * @param OutputInterface $output Output
     *
     * @return void
     * @throws LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            parent::execute($input, $output);
        } catch (LogicException $e) {
        }

        $output->writeln('Remaining');

        $remainingTasks = $this->taskQuery->getAllRemainingTasks();

        /** @var Task $task */
        foreach ($remainingTasks as $task) {
            $output->writeln(' - ' . $task->getName() . '');
        }

        $output->writeln('');

        $output->writeln('Completed');

        $completedTasks = $this->taskQuery->getAllCompletedTasks();

        foreach ($completedTasks as $task) {
            $output->writeln(' - ' . $task->getName());
        }
    }

}
