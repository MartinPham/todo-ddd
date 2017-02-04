<?php

namespace Web\FrontendBundle\Controller;

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
use Web\FrontendBundle\Form\CreateTaskForm;
use Web\FrontendBundle\Form\UpdateTaskForm;

/**
 * Class TaskController
 *
 * @category None
 * @package  Web\FrontendBundle\Controller
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
     * @return mixed
     * @throws \LogicException
     */
    public function createAction(
        FormFactoryInterface $formFactory,
        Request $request,
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

                /** @noinspection NotOptimalIfConditionsInspection */
                if (count($errors) === 0) {
                    try {
                        $taskCommand->addNewTask($name);
                    } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
                        $errors[] = $e->getMessage();
                    }

                    /** @noinspection NotOptimalIfConditionsInspection */
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
     * CompleteAction
     *
     * @Route("/{taskId}/complete",name="task.complete")
     * @Method({"GET"})
     *
     * @return mixed
     * @throws TaskCannotBeSavedException
     * @throws TaskNotFoundException
     */
    public function completeAction(
        Command $taskCommand,
        $taskId
    ) {
        try {
            $taskCommand->completeTask($taskId);
        } catch (TaskNotFoundException | TaskCannotBeSavedException $e) {
            throw $e;
        }

        return $this->redirectToRoute('task.list');
    }

    /**
     * RedoAction
     *
     * @Route("/{taskId}/redo",name="task.redo")
     * @Method({"GET"})
     *
     * @return mixed
     * @throws TaskCannotBeSavedException
     * @throws TaskNotFoundException
     */
    public function redoAction(
        Command $taskCommand,
        $taskId
    ) {
        try {
            $taskCommand->redoTask($taskId);
        } catch (TaskNotFoundException | TaskCannotBeSavedException $e) {
            throw $e;
        }

        return $this->redirectToRoute('task.list');
    }/** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * UpdateAction
     *
     * @Route("/{taskId}/update",name="task.update")
     * @Method({"GET","POST"})
     * @Template()
     *
     * @return mixed
     */
    public function updateAction(
        FormFactoryInterface $formFactory,
        Request $request,
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

        $updateTaskForm = null;
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

        if ($updateTaskForm !== null && count($errors) === 0) {
            $updateTaskForm->handleRequest($request);
            if ($updateTaskForm->isSubmitted() && $updateTaskForm->isValid()) {
                $name = null;
                $status = null;
                try {
                    /** @var Task $task */
                    $task = $updateTaskForm->getData();

                    $name = $task->getName();
                    $status = $task->getStatus();

                } catch (\OutOfBoundsException | \LogicException $e) {
                    $errors[] = $e->getMessage();
                }

                /** @noinspection NotOptimalIfConditionsInspection */
                if ($name !== null && $status !== null && count($errors) === 0) {
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

                /** @noinspection NotOptimalIfConditionsInspection */
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
     * @return mixed
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
     * @Route("/clean",name="task.clean")
     * @Method({"GET"})
     *
     * @return mixed
     * @throws TaskCannotBeRemovedException
     */
    public function cleanAction(
        Command $taskCommand
    ) {
        try {
            $taskCommand->cleanAllCompletedTasks();
        } catch (TaskCannotBeRemovedException $e) {
            throw $e;
        }
        return $this->redirectToRoute('task.list');
    }

}
