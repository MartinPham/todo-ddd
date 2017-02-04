<?php

namespace Todo\Web\FrontendBundle\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Todo\Application\Task\Command;
use Todo\Application\Task\Exception\TaskCannotBeRemovedException;
use Todo\Application\Task\Exception\TaskCannotBeSavedException;
use Todo\Application\Task\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Task;
use Todo\Web\FrontendBundle\Form\CreateTaskForm;
use Todo\Web\FrontendBundle\Form\UpdateTaskForm;

/**
 * Class TaskController
 *
 * @category None
 * @package  Todo\Web\FrontendBundle\Controller
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * List
     *
     * @param Query $taskQuery Task Query
     *
     * @Route("/list",name="task.list")
     * @Method({"GET"})
     * @Template()
     *
     * @return array
     */
    public function listAction(
        Query $taskQuery
    ) {
        return [
            'remaining_tasks' => $taskQuery->getAllRemainingTasks(),
            'completed_tasks' => $taskQuery->getAllCompletedTasks()
        ];
    }

    /**
     * CreateAction
     *
     * @Route("/create",name="task.create")
     * @Template()
     * @Method({"GET","POST"})
     *
     * @return array
     * @throws \LogicException
     */
    public function createAction(
        FormFactoryInterface $formFactory,
        Request $request,
        Session $session,
        Command $taskCommand
    ) {
        $errors = [];

        try {
            $createTaskForm = $formFactory->create(
                CreateTaskForm::class,
                $request->request->all()
            );
        } catch (InvalidOptionsException $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) === 0) {
            $createTaskForm->handleRequest($request);
            if ($createTaskForm->isSubmitted() && $createTaskForm->isValid()) {
                try {
                    $name = $createTaskForm->getData()['name'];
                } catch (\OutOfBoundsException $e) {
                    $errors[] = $e->getMessage();
                }

                if (count($errors) === 0) {
                    try {
                        $taskCommand->addNewTask($name);
                    } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
                        $errors[] = $e->getMessage();
                    }

                    if (count($errors) === 0) {
                        return $this->redirectToRoute('task.list');
                    }
                }



            }
        }



        return [
            'errors' => $errors,
            'create_task_form' => $createTaskForm->createView()
        ];
    }

    /**
     * UpdateStatusAction
     *
     * @Route("/{taskId}/updateStatus/{taskStatus}",name="task.updateStatus")
     * @Method({"GET"})
     *
     * @return array
     * @throws \Exception
     */
    public function updateStatusAction(
        Command $taskCommand,
        $taskId,
        $taskStatus
    ) {
        if ($taskStatus === Task::STATUS_COMPLETED) {
            try {
                $taskCommand->completeTask($taskId);
            } catch (TaskCannotBeSavedException $e) {
                throw $e;
            }
        } else if ($taskStatus === Task::STATUS_REMAINING) {
            try {
                $taskCommand->redoTask($taskId);
            } catch (TaskCannotBeSavedException $e) {
                throw $e;
            }
        } else {
            throw new \Exception('Unknown status');
        }

        return $this->redirectToRoute('task.list');
    }

    /**
     * UpdateAction
     *
     * @Route("/{taskId}/update",name="task.update")
     * @Method({"GET","POST"})
     * @Template()
     *
     * @return array
     */
    public function updateAction(
        FormFactoryInterface $formFactory,
        Request $request,
        Session $session,
        Query $taskQuery,
        Command $taskCommand,
        $taskId
    ) {
        $errors = [];
        try {
            $task = $taskQuery->getTaskById($taskId);
        } catch (TaskNotFoundException $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) === 0) {
            try {
                $updateTaskForm = $formFactory->create(
                    UpdateTaskForm::class,
                    ($request->get('name') !== null) ? $request->request->all() : $task
                );
            } catch (InvalidOptionsException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) === 0) {
            $updateTaskForm->handleRequest($request);
            if ($updateTaskForm->isSubmitted() && $updateTaskForm->isValid()) {
                try {
                    /** @var Task $task */
                    $task = $updateTaskForm->getData();

                    $name = $task->getName();
                    $status = $task->getStatus();

                } catch (\OutOfBoundsException | \LogicException $e) {
                    $errors[] = $e->getMessage();
                }

                if (count($errors) === 0) {
                    try {
                        $taskCommand->editTask(
                            $taskId,
                            [
                                'name'   => $name,
                                'status' => $status,
                            ]
                        );
                    } catch (TaskNotFoundException | TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
                        $errors[] = $e->getMessage();

                    }
                }

                if (count($errors) === 0) {
                    return $this->redirectToRoute('task.list');
                }


            }

        }


        return [
            'errors' => $errors,
            'update_task_form' => $updateTaskForm->createView()
        ];
    }

    /**
     * DeleteAction
     *
     * @Route("/{taskId}/delete",name="task.delete")
     * @Method({"GET"})
     *
     * @return array
     * @throws TaskNotFoundException
     * @throws TaskCannotBeRemovedException
     */
    public function deleteAction(
        Command $taskCommand,
        $taskId
    ) {
        try {
            $taskCommand->removeTask($taskId);
        } catch (TaskNotFoundException | TaskCannotBeRemovedException $e) {
            throw $e;
        }

        return $this->redirectToRoute('task.list');

    }

    /**
     * CleanAction
     *
     * @Route("/clean")
     * @Method({"GET"})
     *
     * @return array
     */
    public function cleanAction()
    {
        return [];
    }

}
