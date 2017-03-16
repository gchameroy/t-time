<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        for($i = 1; $i <= 15; $i++) {
            $post = new Post();
            
            $publishedAt = new \DateTime();
            $post->setTitle('Post #'.$i)
                ->setContent('Donec dolor est, ullamcorper in felis sed, dictum tincidunt felis. Aenean pulvinar bibendum odio, non rutrum velit tempus elementum. Donec hendrerit lorem vitae ullamcorper posuere. Sed mattis sem eget leo lobortis, sit amet volutpat risus ultricies. Ut facilisis blandit metus, facilisis luctus neque blandit ut. Praesent nec ultricies justo. Vestibulum vel massa turpis. Integer vestibulum massa id gravida lobortis. In ut sollicitudin ante. Duis tempus imperdiet lacinia. Donec aliquet quam interdum nisi iaculis, nec aliquet ligula viverra. Nulla vitae dolor ex.')
                ->setView(rand(1, 100))
                ->setPublishedAt($publishedAt)
                ->setCategory($this->getReference('postCategory-'.rand(1, 5)));
            for($j = 1; $j <= rand(1, 3); $j++) {
                $post->addTag($this->getReference('postTag-'.$j));
            }

            $em->persist($post);
            $this->setReference('post-'.$i, $post);
        }
        $em->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}