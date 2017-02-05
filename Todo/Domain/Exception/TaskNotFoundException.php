<?php
/**
 * File: TaskNotFoundException.php - todo
 * zzz - 03/02/17 13:37
 * PHP Version 7
 *
 * @category None
 * @package  Todo
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */

namespace Todo\Domain\Exception;

/**
 * Class TaskNotFoundException
 *
 * An exception triggers when we try to find a Task which is not existed
 * in Task repository
 *
 * @category None
 * @package  Todo\Domain\Exception
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskNotFoundException extends \Exception
{

}
