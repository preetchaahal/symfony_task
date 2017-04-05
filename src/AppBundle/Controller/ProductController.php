<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;

class ProductController extends Controller
{
    /**
     * @Route("/product/create", name="homepage")
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $product->setName('Easy Polo Black Edition');
        $product->setPrice(56);
        $product->setCategory('5');
        $product->setDescription('Ergonomic and stylish!');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();


        $product = new Product();
        $product->setName('Easy Polo Blue Edition');
        $product->setPrice(26);
        $product->setCategory('4');
        $product->setDescription('Ergonomic and stylish. Clean and sleek!');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $product = new Product();
        $product->setName('Geogette Golden Edition');
        $product->setPrice(56);
        $product->setCategory('6');
        $product->setDescription('Stylish!');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $product = new Product();
        $product->setName('Easy Polo Green Edition');
        $product->setPrice(23);
        $product->setCategory('5');
        $product->setDescription('Ergonomic and auspicious!');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$product->getId());

    }
}
