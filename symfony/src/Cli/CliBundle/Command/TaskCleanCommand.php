<?php

namespace Cli\CliBundle\Command;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Todo\Application\Task\Command;
use Todo\Application\Task\Exception\TaskCannotBeRemovedException;
use Todo\Domain\Task;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskCleanCommand
 *
 * @category None
 * @package  Cli\CliBundle\Command
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskCleanCommand extends ContainerAwareCommand
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
     */
    protected function configure()
    {
        try {
            $this
                ->setName('task:clean')
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
     * @throws TaskCannotBeRemovedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            parent::execute($input, $output);
        } catch (LogicException $e) {
            // no catch
        }

        try {
            $this->taskCommand->cleanAllCompletedTasks();
        } catch (TaskCannotBeRemovedException $e) {
            throw $e;
        }


        $output->writeln('Cleaned');
    }

}
