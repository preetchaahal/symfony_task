<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Category;

class CategoryController extends Controller
{
    /**
     * @Route("/category/create", name="createCategory")
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $category->setName('Men');
        $category->setParentId('0');
        $category->setStatus('1');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new Category with id '.$category->getId());

    }

}
