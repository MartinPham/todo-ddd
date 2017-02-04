<?php

namespace Web\FrontendBundle\Controller;

use Application\Task\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("/list")
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
     * @Route("/create")
     *
     * @return array
     */
    public function createAction()
    {
        return [];
    }

    /**
     * UpdateStatusAction
     *
     * @Route("/updateStatus")
     *
     * @return array
     */
    public function updateStatusAction()
    {
        return [];
    }

    /**
     * UpdateAction
     *
     * @Route("/update")
     *
     * @return array
     */
    public function updateAction()
    {
        return [];
    }

    /**
     * DeleteAction
     *
     * @Route("/delete")
     *
     * @return array
     */
    public function deleteAction()
    {
        return [];
    }

    /**
     * CleanAction
     *
     * @Route("/clean")
     *
     * @return array
     */
    public function cleanAction()
    {
        return [];
    }

}
