<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductController extends Controller
{
    /**
     * @Route("/product/create", name="homepage")
     */
    public function createAction(Request $request)
    {
        $productSeed = array(
            0   =>   array(
                'name'          =>      'Easy Polo Black Edition',
                'price'         =>      56,
                'description'   =>      'Ergonomic and stylish!',
                'image'         =>      'no-image.png',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      18,
            ),
            1   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      5,
            ),
            2   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      4,
            ),
            3   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      5,
            ),
            4   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      7,
            ),
            5   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      14,
            ),
            6   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      12,
            ),
            7   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      9,
            ),
            8   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      7,
            ),
            9   =>   array(
                'name'          =>      'Easy Polo Blue Edition',
                'price'         =>      26,
                'description'   =>      'Ergonomic and stylish. Clean and sleek!',
                'image'         =>      '',
                'qty'           =>      '10',
                'status'        =>      '1',
                'category_id'   =>      4,
            ),
        );
        foreach($productSeed as $key => $val){
            $product = new Product();
            $product->setName($val['name']);
            $product->setPrice($val['price']);
            $product->setCategory($val['category_id']);
            $product->setImage($val['image']);
            $product->setQty($val['qty']);
            $product->setStatus($val['status']); 
            $product->setDescription($val['description']);
            
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
        }
       
        return new Response('Saved new product with id '.$product->getId());

    }

    /**
     * @Route("/products/{catId}", name="products")
     */
    public function productsAction($catId=null)
    {
        /*
        *   Displaying all active products when no extra-parameter 
        *   passed in route, else displaying all products related to
        *   a particular category
        */
        if($catId == null){
            $products = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->findAll();
            $category = null;
        }
        else{
            $products = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->findByCategory($catId);
            //Fetching the category name
            $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->findOneById($catId);
        }
        
        if(!$products){
            throw $this->createNotFoundException(
                'No Product found!'
            );
        }

        return $this->render('default/products.html.twig', array(
            'products'    =>    $products,
            'category'    =>    $category     
        ));
    }

    /**
     * @Route("/product/detail/{id}", name="product_detail")
     */
    public function productDetailAction($id)
    {
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->find($id);

        if(!$product){
            throw $this->createNotFoundException(
                'No Product found for id '. $id
            );
        }

        return $this->render('default/product-view.html.twig', array(
            'product'    =>    $product     
        ));

    }

    /**
     * @Route("/admin/productDeact", name="deactProd")
     */
    public function productDeactAction(Request $request)
    {
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->find($request->query->get('id'));

        $product->setStatus('0');
        
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin/productAct", name="actProd")
     */
    public function productActAction(Request $request)
    {
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->find($request->query->get('id'));

        $product->setStatus('1');
        
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->redirectToRoute('admin_products');
    }

     /**
     * @Route("/category/deleteCat", name="deleteProd")
     */
    public function deleteCat(Request $request)
    {
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->findById($catId);

        $em = $this->getDoctrine()->getManager();

        $em->remove($product);

        // actually executes the queries (i.e. the DELETE query)
        $em->flush();
        
        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin/product/{prodId}", name="admin_product_edit")
     */
    public function productEditAction($prodId)
    {
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->find($prodId);
        //Fetching the category name
        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:Category')
                    ->findAll();

        if(! $product){
            throw $this->createNotFoundException(
                'No product found for id '. $prodId
            );
        }

        return $this->render('admin/product-edit.html.twig', array(
            'title'         => 'Product edit',
            'product'       =>  $product,
            'categories'    =>  $categories
        ));
    }

    /**
     * @Route("/admin/product/update", name="admin_product_update")
     */
    public function productUpdateAction(Request $request)
    {
        die('id');
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->find($request->query->post('id'));
        $product->setName($request->query->post('name'));
        $product->setPrice($request->query->post('price'));
        $product->setCategory($request->query->post('category'));
        $product->setQty($request->query->post('qty'));
        $product->setStatus($request->query->post('status')); 
        $product->setDescription($request->query->post('description'));

        if(! $product){
            throw $this->createNotFoundException(
                'No product found for id '. $request->query->get('id')
            );
        }

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin/product/addNew", name="admin_product_add_new")
     */
    public function productAddNewAction($prodId)
    {
        $product = new Product();

        
        return $this->redirectToRoute('admin_products');

    }
}
