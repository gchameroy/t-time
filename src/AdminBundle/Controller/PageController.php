<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     */
    public function homeAction()
    {
        return $this->render('AdminBundle:Page:home.html.twig');
    }

    /**
     * @Route("/comments", name="admin_comments")
     */
    public function commentsAction()
    {
        return $this->render('AdminBundle:Page:comments.html.twig');
    }

    /**
     * @Route("/categories", name="admin_categories")
     */
    public function categoriesAction()
    {
        return $this->render('AdminBundle:Page:categories.html.twig');
    }

    /**
     * @Route("/posts", name="admin_posts")
     */
    public function postsAction()
    {
        return $this->render('AdminBundle:Page:posts.html.twig');
    }

    /**
     * @Route("/post/{id}", name="admin_post", requirements={"id": "\d+"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function postAction(Post $post)
    {
        return $this->render('AdminBundle:Page/post.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/post/add", name="admin_post_add")
     */
    public function postAddAction()
    {
        return $this->render('AdminBundle:Page/post:add.html.twig');
    }

    /**
     * @Route("/post/{id}/edit", name="admin_post_edit")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function postEditAction(Post $post)
    {
        return $this->render('AdminBundle:Page/post:edit.html.twig', [
            'post' => $post
        ]);
    }
}
