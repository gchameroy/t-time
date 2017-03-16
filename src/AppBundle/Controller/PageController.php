<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/", name="app_home")
     */
    public function homeAction()
    {
        return $this->render('AppBundle:Page:home.html.twig');
    }
    
    /**
     * @Route("/post/{slug}", name="app_post")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function postAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$post->isPublished()) {
            throw $this->createNotFoundException('The post does not exist');
        }

        $post->setView($post->getView() + 1);
        $em->persist($post);
        $em->flush();

        return $this->render('AppBundle:Page:post.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/category/{slug}", name="app_category")
     */
    public function categoryAction(PostCategory $category)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('AppBundle:Page:category.html.twig', [
            'category' => $category
        ]);
    }
}
