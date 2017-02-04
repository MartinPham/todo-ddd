<?php

namespace Todo\Domain\Specification;

/**
 * Class TaskNameIsNotEmptySpecification
 *
 * @category None
 * @package  Domain\Specification
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class TaskNameIsNotEmptySpecification
{
    /**
     * Is Satisfied By
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
