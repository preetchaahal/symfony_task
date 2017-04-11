<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Category;
use Doctrine\ORM\Query;

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

    /**
     * @Route("/category/create", name="createCategory")
     */
    public function createAction(Request $request)
    {
        $dataSeed = array(
            0 => array(
                'name'      =>  'Men',
                'parentId' =>  '0',
                'status'    =>  '1'
            ),
            1 => array(
                'name'      =>  'Women',
                'parentId' =>  '0',
                'status'    =>  '1'
            ),
            2 => array(
                'name'      =>  'Clothing',
                'parentId' =>  '1',
                'status'    =>  '1'
            ),
            3 => array(
                'name'      =>  'Clothing',
                'parentId' =>  '2',
                'status'    =>  '1'
            ),
            4 => array(
                'name'      =>  'Footwear',
                'parentId' =>  '1',
                'status'    =>  '1'
            ),
            5 => array(
                'name'      =>  'Casuals',
                'parentId' =>  '4',
                'status'    =>  '1'
            ),
            6 => array(
                'name'      =>  'Shoes',
                'parentId' =>  '2',
                'status'    =>  '1'
            ),
            7 => array(
                'name'      =>  'Indian',
                'parentId' =>  '4',
                'status'    =>  '1'
            ),
            8 => array(
                'name'      =>  'Ethnic',
                'parentId' =>  '2',
                'status'    =>  '1'
            ),
            9 => array(
                'name'      =>  'Shirts',
                'parentId' =>  '0',
                'status'    =>  '3'
            )
        );

        foreach($dataSeed as $key => $val){
            $category = new Category();
            $category->setName($val['name']);
            $category->setParentId($val['parentId']);
            $category->setStatus($val['status']);

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Category (no queries yet)
            $em->persist($category);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
        }

        return new Response('Saved Categories data to db!');

    }

    /**
     * @Route("/category/rename", name="renameCategory")
     */
    public function renameCategory(Request $request)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->find($request->query->get('id'));

        $nodeParent = ($request->query->get('parent') !== null) && $request->query->get('parent') !== '0' ? (int)$request->query->get('parent') : 0;

        $category->setName($request->query->get('name'));
        
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array(
            'data' => 'Renamed'
        ));
        
        return $response;
    }

    /**
     * @Route("/admin/categoryDeact", name="deactCat")
     */
    public function categoryDeactAction(Request $request)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->find($request->query->get('id'));

        $category->setStatus('0');
        
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->redirectToRoute('admin_category');
    }

    /**
     * @Route("/admin/categoryAct", name="actCat")
     */
    public function categoryActAction(Request $request)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->find($request->query->get('id'));

        $category->setStatus('1');
        
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->redirectToRoute('admin_category');
    }

     /**
     * @Route("/category/deleteCat", name="deleteCat")
     */
    public function deleteCat(Request $request)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->findById($catId);

        $em = $this->getDoctrine()->getManager();

        $em->remove($category);

        // actually executes the queries (i.e. the DELETE query)
        $em->flush();
        
        return $this->redirectToRoute('admin_category');
    }

    /**
     * @Route("/category/getNode", name="ajaxGetNode")
     */
    public function getNode(Request $request, $id=null)
    {
        $q = $this->getDoctrine()
                    ->getRepository('AppBundle:Category')
                    ->createQueryBuilder('c')
                    ->getQuery();
        $categories = $q->getResult(Query::HYDRATE_ARRAY);
                        // ->find($request->query->get('id'));
        // print_r($categories); exit();
        if($categories){
            $data = array();
            foreach($categories as $val){
                // $data[] = $val;
                // if($val->getParentId() == '0')
                //     $parentId = '#';
                // else
                //     $parentId = $val->getParentId();
                // $data[] = $val;
                $data[] = array(
                    'id'        =>      $val['id'],
                    'text'      =>      $val['name'],
                    'parentId'  =>      $val['parentId']
                );
            }
            // print_r($data); exit();

            $itemsByReference = array();

            // Build array of item references:
            foreach($data as $key => &$item) {
               $itemsByReference[$item['id']] = &$item;
               // Children array:
               $itemsByReference[$item['id']]['children'] = array();
               // Empty data class (so that json_encode adds "data: {}" ) 
               $itemsByReference[$item['id']]['data'] = new \StdClass();
            }

            // Set items as children of the relevant parent item.
            foreach($data as $key => &$item)
               if($item['parentId'] && isset($itemsByReference[$item['parentId']]))
                  $itemsByReference [$item['parentId']]['children'][] = &$item;

            // Remove items that were added to parents elsewhere:
            foreach($data as $key => &$item) {
               if($item['parentId'] && isset($itemsByReference[$item['parentId']]))
                  unset($data[$key]);
            }
            $result = $data;
        }else{
            $result = array();
        }
        // print_r($result); exit();
        $response = new JsonResponse();
        $response->setData(array_values($result));
        // print_r(json_encode(array_values($result))); exit();
        return $response;
        // echo $response;
        // die();
    }    

    /**
     * @Route("/category/createNode", name="ajaxCreateNode")
     */
    public function createNode(Request $request)
    {
        $nodeParent = ($request->query->get('id') !== null) && $request->query->get('id') !== '0' ? (int)$request->query->get('id') : 0;

        $category = new Category();

        $category->setName($request->query->get('name'));
        $category->setparentId($nodeParent);
        $category->setStatus('1');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $result = $em->lastInsertId();
        
        $response = new JsonResponse();
        $response->setData(array(
            'data' => $result
        ));
        
        return $response;
    }

    /**
     * @Route("/category/renameNode", name="ajaxRenameNode")
     */
    public function renameNode(Request $request)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->find($request->query->get('id'));

        $nodeParent = ($request->query->get('parent') !== null) && $request->query->get('parent') !== '0' ? (int)$request->query->get('parent') : 0;

        $category->setName($request->query->get('name'));
        $category->setparentId($nodeParent);
        $category->setStatus('1');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Category (no queries yet)
        $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array(
            'data' => 'Renamed'
        ));
        
        return $response;
    }

    /**
     * @Route("/category/deleteNode", name="ajaxDeleteNode")
     */
    public function deleteNode(Request $request)
    {
        $category = $this->getDoctrine()
                        ->getRepository('AppBundle:Category')
                        ->findById($catId);

        $em = $this->getDoctrine()->getManager();

        $em->remove($category);

        // actually executes the queries (i.e. the DELETE query)
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array(
            'data' => 'Deleted'
        ));
        
        return $response;
    }

}