<?php
/**
 * File: TaskController.php - ltodo
 * zzz - 04/02/17 23:33
 * PHP Version 7
 *
 * @category None
 * @package  Ltodo
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */

namespace App\Http\Controllers;

use Todo\Application\Task\Command;
use Todo\Application\Task\Query;

/**
 * Class TaskController
 *
 * @category None
 * @package  App\Http\Controllers
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskController extends Controller
{
    public function listAction(
        Query $taskQuery
    ) {
        return view('task.list', [
            'remaining_tasks' => $taskQuery->getAllRemainingTasks(),
            'completed_tasks' => $taskQuery->getAllCompletedTasks()
        ]);
    }
}