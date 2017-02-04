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
        try {
            $createTaskForm = $formFactory->create(CreateTaskForm::class);
        } catch (InvalidOptionsException $e) {
            try {
                $this->addFlash('error', $e->getMessage());
            } catch (\LogicException $e) {
                throw $e;
            }
            return $this->redirectToRoute('task.create');
        }

        $createTaskForm->handleRequest($request);
        if ($createTaskForm->isSubmitted() && $createTaskForm->isValid()) {
            try {
                $name = $createTaskForm->getData()['name'];
                $this->addFlash('form_name', $name);
            } catch (\OutOfBoundsException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.create');
            }

            try {
                $taskCommand->addNewTask($name);
            } catch (TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.create');
            }

            $session->getFlashBag()->set('form_name', null);
            return $this->redirectToRoute('task.list');


        }

        if (count($formName = $session->getFlashBag()->get('form_name')) > 0) {
            try {
                $createTaskForm->get('name')->setData($formName[0]);
            } catch (\OutOfBoundsException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.create');
            }
        }



        return [
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
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.list');
            }
        } else if ($taskStatus === Task::STATUS_REMAINING) {
            try {
                $taskCommand->redoTask($taskId);
            } catch (TaskCannotBeSavedException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.list');
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
     * @throws \LogicException
     */
    public function updateAction(
        FormFactoryInterface $formFactory,
        Request $request,
        Session $session,
        Query $taskQuery,
        Command $taskCommand,
        $taskId
    ) {
        try {
            $task = $taskQuery->getTaskById($taskId);
        } catch (TaskNotFoundException $e) {
            try {
                $this->addFlash('error', $e->getMessage());
            } catch (\LogicException $e) {
                throw $e;
            }
            return $this->redirectToRoute('task.update', [ 'taskId' => $taskId ]);
        }


        try {
            $updateTaskForm = $formFactory->create(
                UpdateTaskForm::class,
                $task
            );
        } catch (InvalidOptionsException $e) {
            try {
                $this->addFlash('error', $e->getMessage());
            } catch (\LogicException $e) {
                throw $e;
            }
            return $this->redirectToRoute('task.update', [ 'taskId' => $taskId ]);
        }

        $updateTaskForm->handleRequest($request);
        if ($updateTaskForm->isSubmitted() && $updateTaskForm->isValid()) {
            try {
                /** @var Task $task */
                $task = $updateTaskForm->getData();

                $name = $task->getName();
                $status = $task->getStatus();

                $this->addFlash('form_name', $name);
                $this->addFlash('form_status', $status);

            } catch (\OutOfBoundsException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.update', [ 'taskId' => $taskId ]);
            } catch (\LogicException $e) {
                throw $e;
            }

            try {
                $taskCommand->editTask(
                    $taskId,
                    [
                        'name' => $name,
                        'status' => $status,
                    ]
                );
            } catch (TaskNotFoundException | TaskNameIsEmptyException | TaskNameIsAlreadyExistedException | TaskCannotBeSavedException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.update', [ 'taskId' => $taskId ]);
            }

            $session->getFlashBag()->set('form_name', null);
            $session->getFlashBag()->set('form_status', null);

            return $this->redirectToRoute('task.list');


        }

        if (count($formName = $session->getFlashBag()->get('form_name')) > 0) {
            try {
                $updateTaskForm->get('name')->setData($formName[0]);
            } catch (\OutOfBoundsException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.update', [ 'taskId' => $taskId ]);
            }
        }

        if (count($formStatus = $session->getFlashBag()->get('form_status')) > 0) {
            try {
                $updateTaskForm->get('status')->setData($formStatus[0]);
            } catch (\OutOfBoundsException $e) {
                try {
                    $this->addFlash('error', $e->getMessage());
                } catch (\LogicException $e) {
                    throw $e;
                }
                return $this->redirectToRoute('task.update', [ 'taskId' => $taskId ]);
            }
        }


        return [
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
