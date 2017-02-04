<?php

namespace Web\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Todo\Application\Task\Command;
use Todo\Application\Task\Exception\TaskCannotBeRemovedException;
use Todo\Application\Task\Exception\TaskCannotBeSavedException;
use Todo\Application\Task\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Todo\Domain\Exception\TaskNameIsAlreadyExistedException;
use Todo\Domain\Exception\TaskNameIsEmptyException;
use Todo\Domain\Exception\TaskNotFoundException;
use Todo\Domain\Task;
use Web\FrontendBundle\Form\CreateTaskForm;

/**
 * Class TaskController
 *
 * @category None
 * @package  Web\ApiBundle\Controller
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * ListAction
     *
     * @param Query $taskQuery Task Query
     *
     * @Route("/list",name="api.task.list")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAction(
        Query $taskQuery
    ) {
        return $this->json(
            [
            'remaining_tasks' => $taskQuery->getAllRemainingTasks(),
            'completed_tasks' => $taskQuery->getAllCompletedTasks()
            ]
        );
    }

    /**
     * CreateAction
     *
     * @Route("/create",name="api.task.create")
     * @Method({"POST"})
     *
     * @return mixed
     */
    public function createAction(
        Request $request,
        Command $taskCommand
    ) {
        $errors = [];

        $name = $request->get('name');
        try {
            $taskCommand->addNewTask($name);
        } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
            $errors[] = $e->getMessage();
        }


        return $this->json([
            'errors' => $errors
        ]);
    }

    /**
     * CompleteAction
     *
     * @Route("/{taskId}/complete",name="api.task.complete")
     * @Method({"GET"})
     *
     * @return mixed
     */
    public function completeAction(
        Command $taskCommand,
        $taskId
    ) {
        $errors = [];

        try {
            $taskCommand->completeTask($taskId);
        } catch (TaskNotFoundException | TaskCannotBeSavedException $e) {
            $errors[] = $e->getMessage();
        }

        return $this->json([
            'errors' => $errors
        ]);
    }

    /**
     * RedoAction
     *
     * @Route("/{taskId}/redo",name="api.task.redo")
     * @Method({"GET"})
     *
     * @return mixed
     */
    public function redoAction(
        Command $taskCommand,
        $taskId
    ) {
        $errors = [];
        try {
            $taskCommand->redoTask($taskId);
        } catch (TaskNotFoundException | TaskCannotBeSavedException $e) {
            $errors[] = $e->getMessage();
        }


        return $this->json([
            'errors' => $errors
        ]);
    }

    /**
     * UpdateAction
     *
     * @Route("/{taskId}/update",name="api.task.update")
     * @Method({"POST"})
     *
     * @return mixed
     */
    public function updateAction(
        Request $request,
        Command $taskCommand,
        $taskId
    ) {
        $errors = [];

        $name = $request->get('name');
        $status = $request->get('status');


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


        return $this->json([
            'errors' => $errors
        ]);
    }

    /**
     * DeleteAction
     *
     * @Route("/{taskId}/delete",name="api.task.delete")
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
        $errors = [];

        try {
            $taskCommand->removeTask($taskId);
        } catch (TaskNotFoundException | TaskCannotBeRemovedException $e) {
            $errors[] = $e->getMessage();
        }


        return $this->json([
            'errors' => $errors
        ]);

    }

    /**
     * CleanAction
     *
     * @Route("/clean",name="api.task.clean")
     * @Method({"GET"})
     *
     * @return mixed
     * @throws TaskCannotBeRemovedException
     */
    public function cleanAction(
        Command $taskCommand
    ) {
        $errors = [];

        try {
            $taskCommand->cleanAllCompletedTasks();
        } catch (TaskCannotBeRemovedException $e) {
            throw $e;
        }

        return $this->json([
            'errors' => $errors
        ]);
    }

}
