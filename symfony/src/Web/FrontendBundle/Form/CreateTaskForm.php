<?php
/**
 * File: CreateTaskForm.php - todo
 * zzz - 04/02/17 10:58
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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Class CreateTaskForm
 *
 * @category None
 * @package  Web\FrontendBundle\Form
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class CreateTaskForm extends AbstractType
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
            ->add('submit', SubmitType::class);
    }
}
