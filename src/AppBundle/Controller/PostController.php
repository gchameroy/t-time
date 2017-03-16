<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * @Route("/post/list/{page}", name="app_post_list", requirements={"page": "\d+"})
     */
    public function listAction($page = 1)
    {
        $posts = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Post')
            ->findPublished($page, 10);

        return $this->render('AppBundle:Element/post:list.html.twig', [
            'posts' => $posts,
            'page' => $page
        ]);
    }

    /**
     * @Route("/category/{slug}/post/list/{page}", name="app_category_post_list", requirements={"page": "\d+"})
     * @ParamConverter("category", class="AppBundle:PostCategory")
     */
    public function categoryListAction(PostCategory $category, $page = 1)
    {
        $posts = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Post')
            ->findPublished($page, 10, $category);

        return $this->render('AppBundle:Element/post:listCategory.html.twig', [
            'category' => $category,
            'posts' => $posts,
            'page' => $page
        ]);
    }

    /**
     * @Route("/post/simple/{id}", name="app_post_simple", requirements={"id": "\d+"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function simpleAction(Post $post)
    {
        if ($response = $this->checkPost($post) !== true ) {
            return $response;
        }

        return $this->render('AppBundle:Element/post:simple.html.twig', [
            'post' => $post
        ]);
    }
    
    /**
     * @Route("/post/full/{id}", name="app_post_full", requirements={"id": "\d+"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function fullAction(Post $post)
    {
        $this->checkPost($post);
        return $this->render('AppBundle:Element/post:full.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/sign-in/post/{slug}", name="app_post_signin")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function signinAction(Post $post)
    {
        return $this->redirectToRoute('app_post', [
            'slug' => $post->getSlug()
        ]);
    }

    private function checkPost(Post $post)
    {
        if (!$post->isPublished()) {
            return new NotFoundHttpException();
        }

        if (!$post->isDeleted()) {
            return new GoneHttpException();
        }

        return true;
    }
}
