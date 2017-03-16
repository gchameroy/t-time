<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/post/list", name="admin_post_list")
     */
    public function listAction()
    {
        $posts = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Post')
            ->findAll();

        return $this->render('AdminBundle:Element/post:list.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/{id}/simple", name="admin_post_simple")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function simpleAction(Post $post)
    {
        return $this->render('AdminBundle:Element/post:simple.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/post/form/add", name="admin_post_form_add")
     */
    public function addAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('admin_post_form_add'),
        ])
        ->add('save', SubmitType::class, array(
            'label' => 'Save'
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('AdminBundle:Element/post:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/post/{id}/form/edit", name="admin_post_form_edit")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function editAction(Post $post, Request $request)
    {
        $form = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('admin_post_form_edit', [
                'id' => $post->getId()
            ]),
        ])
        ->add('save', SubmitType::class, array(
            'label' => 'Save'
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $post->setUpdatedAt(new \DateTime());
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('AdminBundle:Element/post:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/post/{id}/delete", name="admin_post_delete")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $post->setDeletedAt(new \DateTime());
        $em->persist($post);
        $em->flush();

        $response = new \StdClass;
        $response->valid = true;

        return new JsonResponse($response);
    }

    /**
     * @Route("/post/{slug}/publish", name="admin_post_publish")
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function publishAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $post->setPublishedAt(new \DateTime());
        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('admin_posts');
    }
}
