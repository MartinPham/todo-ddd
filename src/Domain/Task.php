<?php

namespace Domain;

/**
 * Class Task
 *
 * @category None
 * @package  Domain\Model
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class Task implements \JsonSerializable
{

    const STATUS_REMAINING = 'remaining';
    const STATUS_COMPLETED = 'completed';

    protected $id;
    protected $name;
    protected $status = Task::STATUS_REMAINING;
    protected $createdAt;
    protected $updatedAt;

    /**
     * Get Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param mixed $id Id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param mixed $name Name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get Status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Status
     *
     * @param mixed $status Status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get CreatedAt
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set CreatedAt
     *
     * @param mixed $createdAt CreatedAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get UpdatedAt
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set UpdatedAt
     *
     * @param mixed $updatedAt UpdatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'status' => $this->getStatus()
        ];
    }
}
