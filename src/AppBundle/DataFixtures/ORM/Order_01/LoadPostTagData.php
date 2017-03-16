<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PostTag;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostTagData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        for($i = 1; $i <= 3; $i++) {
            $postTag = new PostTag();
            $postTag->setLabel('Tag #'.$i);

            $em->persist($postTag);
            $this->addReference('postTag-'.$i, $postTag);
        }
        $em->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}