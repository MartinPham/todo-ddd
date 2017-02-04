<?php

namespace Cli\CliBundle\Command;

use Application\Task\Query;
use Domain\Task;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskListCommand
 *
 * @category None
 * @package  Cli\CliBundle\Command
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
        $this
            ->setName('task:list')
            ->setDescription('...');
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
            throw $e;

        }

        $output->writeln('Remaining');

        $remainingTasks = $this->taskQuery->getAllRemainingTasks();

        /** @var Task $task */
        foreach ($remainingTasks as $task) {
            $output->writeln(' - ' . $task->getName());
        }

        $output->writeln('');

        $output->writeln('Completed');

        $completedTasks = $this->taskQuery->getAllCompletedTasks();

        /** @var Task $task */
        foreach ($completedTasks as $task) {
            $output->writeln(' - ' . $task->getName());
        }
    }

}
