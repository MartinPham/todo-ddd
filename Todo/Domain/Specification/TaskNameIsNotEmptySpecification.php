<?php

namespace Todo\Domain\Specification;

/**
 * Class TaskNameIsNotEmptySpecification
 *
 * A specification describes that Task's name should not be empty
 *
 * @category None
 * @package  Todo\Domain\Specification
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskNameIsNotEmptySpecification
{
    /**
     * Check given name is empty or not, we want it not empty
     *
     * @param string $name Name
     *
     * @return bool
     */
    public function isSatisfiedBy(string $name)
    {
        return $name !== '';
    }
}
