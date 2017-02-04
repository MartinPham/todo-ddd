<?php

namespace Todo\Web\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DefaultController
 *
 * @category None
 * @package  Todo\Web\ApiBundle\Controller
 * @author   Martin Pham <i@martinpham.com>
 * @license  None http://
 * @link     None
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return null;
    }
}
