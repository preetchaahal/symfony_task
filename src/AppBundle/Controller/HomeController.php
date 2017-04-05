<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Category;

class HomeController extends Controller
{
    /**
     * @Route("/")
     */
    public function defaultAction(Request $request)
    {
        // fetching all available categories
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
        $categories = $repository->findAll();
        
        return $this->render('default/index.html.twig', array(
            'categories'    =>    $categories     
        ));
        // return $this->render('test/number.html.twig', array()
        // );
    }
}
