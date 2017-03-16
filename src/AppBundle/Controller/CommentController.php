<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostComment;
use AppBundle\Form\PostCommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CommentController extends Controller
{
    /**
     * @Route("/post/{postId}/comment/list/{page}", name="app_comment_list", requirements={"postId": "\d+", "page": "\d+"})
     */
    public function listAction($postId, $page = 1)
    {
        $comments = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:PostComment')
            ->findByPost($postId, $page, 10);

        return $this->render('AppBundle:Element/comment:list.html.twig', [
            'comments' => $comments,
            'postId' => $postId,
            'page' => $page
        ]);
    }

    /**
     * @Route("/comment/full/{id}", name="app_comment_full", requirements={"id": "\d+"})
     * @ParamConverter("comment", class="AppBundle:PostComment")
     */
    public function fullAction(PostComment $comment)
    {
        return $this->render('AppBundle:Element/comment:full.html.twig', [
            'comment' => $comment
        ]);
    }

    /**
     * @Route("/post/{id}/comment/add", name="app_comment_add", requirements={"id": "\d+"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function addAction(Post $post, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('AppBundle:Element/comment:add.html.twig', array(
                'post' => $post
            ));
        }

        $comment = new PostComment();
        $form = $this->createForm(PostCommentType::class, $comment, [
            'action' => $this->generateUrl('app_comment_add', [
                'id' => $post->getId()
            ]),
        ])
        ->add('save', SubmitType::class, array(
            'label' => 'Send comment'
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->setPost($post)
                ->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_post', [
                'slug' => $post->getSlug()
            ]);
        }

        return $this->render('AppBundle:Element/comment:add.html.twig', array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }
}
