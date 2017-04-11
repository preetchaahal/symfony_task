<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CommonController extends Controller
{
    public function generateMainNavAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository('AppBundle:Category')
                                    ->findAllCategoriesAndSubCategories();

        return $this->render('common/main_nav.html.twig', array(
            'categories'    =>    $categories     
        ));
    }

    public function generateCategoriesAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository('AppBundle:Category')
                                    ->findAllCategoriesAndSubCategories();

        return $this->render('common/category-accordian.html.twig', array(
            'categories'    =>    $categories
        ));
    }
}