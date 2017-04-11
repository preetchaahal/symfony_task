<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

class HomeController extends Controller
{
    /**
     * @Route("/admin/index", name="admin_home")
     */
    // public function indexAction()
    // {
    //     return $this->render('admin/category.html.twig', array(
    //     	'title' => 'Categories',
    //     ));
    // }

    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function productsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository('AppBundle:Product')
                                    ->findAll();

        return $this->render('admin/products.html.twig', array(
            'title'         => 'Products',
            'products'    =>  $products
        ));

    }

    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function categoryAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository('AppBundle:Category')
                                    ->findAllCategoriesAndSubCategories();

        return $this->render('admin/category.html.twig', array(
            'title'         => 'Categories',
            'categories'    =>  $categories
        ));

    }

    /**
     * @Route("/admin/category/{catId}", name="admin_category_edit")
     */
    public function categoryEditAction($catId)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->find($catId);
        $childCategories = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->findAllChildCategories($catId);
        if(!$category){
            throw $this->createNotFoundException(
                'No Category found for id '. $catId
            );
        }

        return $this->render('admin/category-edit.html.twig', array(
            'title'             => 'Category edit',
            'category'          =>  $category,
            'childCategories'   =>  $childCategories
        ));

    }
}