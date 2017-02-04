<?php

namespace Web\ApiBundle\Controller;

use Application\Task\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
     * @Route("/list")
     * @Method({"GET", "POST"})
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

}
