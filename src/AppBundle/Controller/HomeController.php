<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function defaultAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository('AppBundle:Category')
                                    ->findAllCategoriesAndSubCategories();

        return $this->render('default/index.html.twig', array(
            'categories'    =>    $categories     
        ));
    }
}