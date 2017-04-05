<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;

class CategoryController extends Controller
{
    /**
     * @Route("/admin/categories")
     */
    public function categoryAction()
    {
        $category = new Category();

        return $this->render('admin/categories.html.twig', array(
        	'title' => 'Categories',
        ));

    }
}