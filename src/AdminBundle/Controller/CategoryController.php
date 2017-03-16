<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\PostCategory;
use AppBundle\Form\PostCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category/list", name="admin_category_list")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:PostCategory')
            ->findAll();

        return $this->render('AdminBundle:Element/category:list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/{id}/simple", name="admin_category_simple")
     * @ParamConverter("category", class="AppBundle:postCategory")
     */
    public function simpleAction(PostCategory $category)
    {
        return $this->render('AdminBundle:Element/category:simple.html.twig', [
            'category' => $category
        ]);
    }
    
    /**
     * @Route("/category/add", name="admin_category_add")
     */
    public function addAction(Request $request)
    {
        $category = new PostCategory;
        $form = $this->createForm(PostCategoryType::class, $category)
            ->add('save', SubmitType::class, array(
                'label' => 'Add category'
            ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('AdminBundle:Element/category:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/{id}/edit", name="admin_category_edit")
     * @ParamConverter("category", class="AppBundle:postCategory")
     */
    public function editAction(PostCategory $category, Request $request)
    {
        $form = $this->createForm(PostCategoryType::class, $category, [
            'action' => $this->generateUrl('admin_category_edit', [
                'id' => $category->getId()
            ]),
        ])
        ->add('save', SubmitType::class, array(
            'label' => 'Save'
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('AdminBundle:Element/category:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/{id}/remove", name="admin_category_remove")
     * @ParamConverter("category", class="AppBundle:postCategory")
     */
    public function removeAction(PostCategory $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * @Route("/category/{id}/visibility/swap", name="admin_category_visibility_swap")
     * @ParamConverter("category", class="AppBundle:postCategory")
     */
    public function visibilitySwapAction(PostCategory $category)
    {
        $em = $this->getDoctrine()->getManager();
        if($category->isVisible()){
            $category->setIsVisible(false);
        } else {
            $category->setIsVisible(true);
        }
        $em->persist($category);
        $em->flush();

        $response = new \stdClass;
        $response->valid = true;
        return new JsonResponse($response);
    }
}
