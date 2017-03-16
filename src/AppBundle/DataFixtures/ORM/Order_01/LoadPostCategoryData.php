<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PostCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $em)
    {
        for($i = 1; $i <= 5; $i++) {
            $postCategory = new PostCategory();
            $postCategory->setLabel('Category #'.$i)
                ->setIsVisible(true);

            $em->persist($postCategory);
            $this->addReference('postCategory-'.$i, $postCategory);
        }
        $em->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}