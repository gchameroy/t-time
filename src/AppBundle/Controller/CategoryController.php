<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * @Route("/post/list/", name="app_category_list")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:PostCategory')
            ->findShown();

        return $this->render('AppBundle:Element/category:list.html.twig', [
            'categories' => $categories
        ]);
    }
}
