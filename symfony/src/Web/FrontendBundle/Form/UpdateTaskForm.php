<?php
/**
 * File: UpdateTaskForm.php - todo
 * zzz - 04/02/17 17:46
 * PHP Version 7
 *
 * @category None
 * @package  Todo
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */

namespace Web\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Todo\Domain\Task;

/**
 * Class UpdateTaskForm
 *
 * @category None
 * @package  Web\FrontendBundle\Form
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class UpdateTaskForm extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    Task::STATUS_REMAINING => Task::STATUS_REMAINING,
                    Task::STATUS_COMPLETED => Task::STATUS_COMPLETED
                ]
            ])
            ->add('submit', SubmitType::class);
    }
}
